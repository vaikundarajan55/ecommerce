<?php

namespace App\Controllers\Admin;

use App\Models\OrderModel;

class Reports extends AdminBase
{
    private const STATUSES = ['placed', 'processing', 'shipped', 'delivered', 'cancelled'];

    public function index()
    {
        [$year, $month, $from, $to] = $this->period();
        $db = db_connect();

        // Headline numbers for the selected period
        $sum = $db->query(
            "SELECT COUNT(*) orders,
                    SUM(payment_status = 'paid') paid_orders,
                    COALESCE(SUM(CASE WHEN payment_status = 'paid' THEN total END), 0) revenue,
                    SUM(status = 'cancelled') cancelled,
                    COUNT(DISTINCT user_id) customers
             FROM orders WHERE created_at >= ? AND created_at < ?",
            [$from, $to]
        )->getRowArray();
        $sum['items']    = (int) ($db->query(
            "SELECT COALESCE(SUM(i.qty), 0) q FROM order_items i JOIN orders o ON o.id = i.order_id
             WHERE o.payment_status = 'paid' AND o.created_at >= ? AND o.created_at < ?",
            [$from, $to]
        )->getRowArray()['q']);
        $sum['avg']      = $sum['paid_orders'] ? $sum['revenue'] / $sum['paid_orders'] : 0;
        $sum['newUsers'] = $db->table('users')->where('created_at >=', $from)->where('created_at <', $to)->countAllResults();

        // Breakdown: per year (all years), per month (a year) or per day (a month)
        [$bucket, $labels] = match (true) {
            $year === 0  => ['YEAR(created_at)', array_combine(array_reverse($this->years()), array_reverse($this->years()))],
            $month === 0 => ['MONTH(created_at)', array_combine(range(1, 12), array_map(static fn ($m) => date('M Y', mktime(0, 0, 0, $m, 1, $year)), range(1, 12)))],
            default      => ['DAY(created_at)', $this->dayLabels($year, $month)],
        };
        $found = [];
        foreach ($db->query(
            "SELECT $bucket k, COUNT(*) orders, SUM(payment_status = 'paid') paid_orders,
                    COALESCE(SUM(CASE WHEN payment_status = 'paid' THEN total END), 0) revenue, SUM(status = 'cancelled') cancelled
             FROM orders WHERE created_at >= ? AND created_at < ? GROUP BY k",
            [$from, $to]
        )->getResultArray() as $r) {
            $found[(int) $r['k']] = $r;
        }
        $breakdown = [];
        foreach ($labels as $k => $label) {
            $r           = $found[$k] ?? ['orders' => 0, 'paid_orders' => 0, 'revenue' => 0, 'cancelled' => 0];
            $breakdown[] = ['label' => $label] + array_map('floatval', array_intersect_key($r, array_flip(['orders', 'paid_orders', 'revenue', 'cancelled'])));
        }

        $statusCounts = array_fill_keys(self::STATUSES, 0);
        foreach ($db->query('SELECT status, COUNT(*) c FROM orders WHERE created_at >= ? AND created_at < ? GROUP BY status', [$from, $to])->getResultArray() as $r) {
            $statusCounts[$r['status']] = (int) $r['c'];
        }

        $topProducts = $db->query(
            "SELECT i.name, SUM(i.qty) qty, SUM(i.total) amount FROM order_items i JOIN orders o ON o.id = i.order_id
             WHERE o.payment_status = 'paid' AND o.created_at >= ? AND o.created_at < ?
             GROUP BY i.name ORDER BY qty DESC, amount DESC LIMIT 10",
            [$from, $to]
        )->getResultArray();

        // Order list for the period (search + page size like the other admin tables)
        $model = (new OrderModel())->where('created_at >=', $from)->where('created_at <', $to);
        $list  = $this->listing($model, ['order_no', 'name', 'email', 'phone'], 'id');

        return $this->render('reports/index', [
            'title'        => 'Reports',
            'year'         => $year,
            'month'        => $month,
            'years'        => $this->years(),
            'periodLabel'  => $this->periodLabel($year, $month),
            'sum'          => $sum,
            'breakdown'    => $breakdown,
            'bucketName'   => $year === 0 ? 'Year' : ($month === 0 ? 'Month' : 'Date'),
            'statusCounts' => $statusCounts,
            'topProducts'  => $topProducts,
        ] + $list);
    }

    /** Download the orders of the selected period as CSV. */
    public function export()
    {
        [$year, $month, $from, $to] = $this->period();
        $rows = (new OrderModel())->where('created_at >=', $from)->where('created_at <', $to)->orderBy('id')->findAll();

        $fh = fopen('php://temp', 'r+');
        fputcsv($fh, ['Order no', 'Date', 'Customer', 'Email', 'Phone', 'City', 'Subtotal', 'Shipping', 'Total', 'Payment', 'Status']);
        foreach ($rows as $o) {
            fputcsv($fh, [$o['order_no'], $o['created_at'], $o['name'], $o['email'], $o['phone'], $o['city'], $o['subtotal'], $o['shipping'], $o['total'], $o['payment_status'], $o['status']]);
        }
        rewind($fh);
        $csv = stream_get_contents($fh);
        fclose($fh);

        $name = 'orders-' . strtolower(str_replace(' ', '-', $this->periodLabel($year, $month))) . '.csv';
        return $this->response->download($name, $csv)->setContentType('text/csv');
    }

    /** Read ?year= (0 = all years) and ?month= (0 = whole year) and turn them into a [from, to) date range. */
    private function period(): array
    {
        $yearIn = $this->request->getGet('year');
        $year   = $yearIn === null ? (int) date('Y') : (int) $yearIn;
        $month  = (int) $this->request->getGet('month');
        if ($year !== 0 && ($year < 2000 || $year > 2100)) {
            $year = (int) date('Y');
        }
        if ($year === 0 || $month < 1 || $month > 12) {
            $month = 0;
        }

        if ($year === 0) {
            return [0, 0, '1970-01-01 00:00:00', '2999-01-01 00:00:00'];
        }
        $from = sprintf('%04d-%02d-01 00:00:00', $year, $month ?: 1);
        $to   = $month ? date('Y-m-d 00:00:00', strtotime("$from +1 month")) : sprintf('%04d-01-01 00:00:00', $year + 1);
        return [$year, $month, $from, $to];
    }

    /** Years that have orders, plus the current year, newest first. */
    private function years(): array
    {
        $years = array_map('intval', array_column(db_connect()->query('SELECT DISTINCT YEAR(created_at) y FROM orders WHERE created_at IS NOT NULL')->getResultArray(), 'y'));
        $years[] = (int) date('Y');
        $years = array_unique($years);
        rsort($years);
        return $years;
    }

    private function dayLabels(int $year, int $month): array
    {
        $days = (int) date('t', mktime(0, 0, 0, $month, 1, $year));
        $out  = [];
        for ($d = 1; $d <= $days; $d++) {
            $out[$d] = date('d M', mktime(0, 0, 0, $month, $d, $year));
        }
        return $out;
    }

    private function periodLabel(int $year, int $month): string
    {
        return $year === 0 ? 'All years' : ($month ? date('F Y', mktime(0, 0, 0, $month, 1, $year)) : (string) $year);
    }
}
