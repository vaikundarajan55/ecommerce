<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Model;

abstract class AdminBase extends BaseController
{
    /** Page sizes offered in the "Show N entries" dropdown ("all" is also allowed). */
    protected const PAGE_SIZES = [10, 20, 25, 50];

    protected function render(string $view, array $data = [])
    {
        return view('admin/' . $view, $data);
    }

    /** Total / active / inactive record counts for the status metrics cards. */
    protected function statusCounts(string $table): array
    {
        $row = db_connect()->table($table)->select('COUNT(*) total, COALESCE(SUM(status = 1), 0) active', false)->get()->getRowArray();
        $total  = (int) $row['total'];
        $active = (int) $row['active'];
        return ['total' => $total, 'active' => $active, 'inactive' => $total - $active];
    }

    /**
     * Apply the ?q= search and ?per_page= size to a prepared model query and fetch the rows.
     * Returns the data the list views and the table_toolbar / table_footer partials expect.
     */
    protected function listing(Model $model, array $searchCols, string $orderBy, string $dir = 'DESC'): array
    {
        $q   = trim((string) $this->request->getGet('q'));
        $raw = (string) $this->request->getGet('per_page');
        $per = $raw === 'all' ? 'all' : (in_array((int) $raw, self::PAGE_SIZES, true) ? (int) $raw : self::PAGE_SIZES[0]);

        if ($q !== '' && $searchCols) {
            $model->groupStart();
            foreach ($searchCols as $col) {
                $model->orLike($col, $q);
            }
            $model->groupEnd();
        }
        $model->orderBy($orderBy, $dir);

        if ($per === 'all') {
            $rows  = $model->findAll();
            $pager = null;
            $total = count($rows);
            $from  = $total ? 1 : 0;
        } else {
            $rows  = $model->paginate($per);
            $pager = $model->pager;
            $total = $pager->getTotal();
            $from  = $total ? ($pager->getCurrentPage() - 1) * $per + 1 : 0;
        }

        return [
            'rows'     => $rows,
            'pager'    => $pager,
            'q'        => $q,
            'perPage'  => $per,
            'sizes'    => self::PAGE_SIZES,
            'total'    => $total,
            'from'     => $from,
            'to'       => $total ? $from + count($rows) - 1 : 0,
        ];
    }
}
