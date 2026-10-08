<?php

namespace App\Controllers\Admin;

use App\Models\OrderModel;
use App\Models\UserModel;
use App\Models\VisitorModel;

class Visitors extends AdminBase
{
    public function index()
    {
        $tab = $this->request->getGet('tab') === 'purchases' ? 'purchases' : 'visitors';

        if ($tab === 'purchases') {
            $model = (new OrderModel())->where('orders.ip_address IS NOT NULL')
                ->select('orders.*, (SELECT COUNT(*) FROM orders o2 WHERE o2.ip_address = orders.ip_address) AS ip_orders');
            $data = $this->listing($model, ['orders.ip_address', 'order_no', 'name', 'email'], 'orders.id');
        } else {
            $model = (new VisitorModel())->select('visitors.*, users.name AS user_name, users.email AS user_email')
                ->join('users', 'users.id = visitors.user_id', 'left');
            $data = $this->listing($model, ['visitors.ip_address', 'last_page', 'users.name', 'users.email'], 'visitors.updated_at');
        }

        $db    = db_connect();
        $today = date('Y-m-d');
        $stats = [
            'unique'    => (int) $db->query('SELECT COUNT(DISTINCT ip_address) n FROM visitors')->getRow()->n,
            'today'     => (int) $db->query('SELECT COUNT(*) n FROM visitors WHERE visit_date = ?', [$today])->getRow()->n,
            'views'     => (int) $db->query('SELECT COALESCE(SUM(hits), 0) n FROM visitors')->getRow()->n,
            'buyerIps'  => (int) $db->query('SELECT COUNT(DISTINCT ip_address) n FROM orders WHERE ip_address IS NOT NULL')->getRow()->n,
        ];

        return $this->render('visitors/index', ['title' => 'Visitors & IPs', 'tab' => $tab, 'stats' => $stats] + $data);
    }

    /** JSON list of the pages one visitor (IP + day) opened, for the page views popup. */
    public function pages(int $id)
    {
        $visitor = (new VisitorModel())->find($id);
        if (! $visitor) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Visitor not found.']);
        }

        $views = db_connect()->table('visitor_page_views')->select('page, viewed_at')
            ->where('visitor_id', $id)->orderBy('id', 'DESC')->limit(500)->get()->getResultArray();

        $customer = $visitor['user_id'] ? (new UserModel())->find($visitor['user_id']) : null;

        return $this->response->setJSON([
            'ip'       => $visitor['ip_address'],
            'customer' => $customer['name'] ?? null,
            'date'  => date('d M Y', strtotime($visitor['visit_date'])),
            'hits'  => (int) $visitor['hits'],
            'views' => array_map(static fn ($v) => ['page' => $v['page'], 'time' => date('h:i:s A', strtotime($v['viewed_at']))], $views),
        ]);
    }
}
