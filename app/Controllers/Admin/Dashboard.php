<?php

namespace App\Controllers\Admin;

use App\Models\ContactModel;
use App\Models\EnquiryModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\UserModel;

class Dashboard extends AdminBase
{
    public function index()
    {
        $orders = new OrderModel();

        // Orders + revenue for the last 7 days
        $labels = $counts = $revenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $d        = date('Y-m-d', strtotime("-$i day"));
            $labels[] = date('d M', strtotime($d));
            $row      = db_connect()->query(
                "SELECT COUNT(*) c, COALESCE(SUM(CASE WHEN payment_status='paid' THEN total END),0) r FROM orders WHERE DATE(created_at) = ?",
                [$d]
            )->getRowArray();
            $counts[]  = (int) $row['c'];
            $revenue[] = (float) $row['r'];
        }

        // Previous 7 days, for the "vs last week" comparison
        $prev = db_connect()->query(
            "SELECT COUNT(*) c, COALESCE(SUM(CASE WHEN payment_status='paid' THEN total END),0) r FROM orders
             WHERE created_at >= ? AND created_at < ?",
            [date('Y-m-d', strtotime('-13 day')), date('Y-m-d', strtotime('-6 day'))]
        )->getRowArray();

        $statusCounts = array_fill_keys(['placed', 'processing', 'shipped', 'delivered', 'cancelled'], 0);
        foreach (db_connect()->query('SELECT status, COUNT(*) c FROM orders GROUP BY status')->getResultArray() as $r) {
            $statusCounts[$r['status']] = (int) $r['c'];
        }

        return $this->render('dashboard', [
            'title'    => 'Dashboard',
            'stats'    => [
                'orders'    => $orders->countAllResults(),
                'revenue'   => (float) ($orders->selectSum('total')->where('payment_status', 'paid')->first()['total'] ?? 0),
                'products'  => (new ProductModel())->countAllResults(),
                'users'     => (new UserModel())->countAllResults(),
                'enquiries' => (new EnquiryModel())->where('is_read', 0)->countAllResults(),
                'contacts'  => (new ContactModel())->where('is_read', 0)->countAllResults(),
            ],
            'recent'   => $orders->orderBy('id', 'DESC')->findAll(6),
            'lowStock' => (new ProductModel())->where('stock <', 6)->orderBy('stock')->findAll(5),
            'chart'    => ['labels' => $labels, 'orders' => $counts, 'revenue' => $revenue],
            'week'     => [
                'orders'      => array_sum($counts),
                'revenue'     => array_sum($revenue),
                'prevOrders'  => (int) $prev['c'],
                'prevRevenue' => (float) $prev['r'],
            ],
            'statusCounts' => $statusCounts,
        ]);
    }
}
