<?php

namespace App\Controllers\Admin;

use App\Models\EnquiryModel;

class Enquiries extends AdminBase
{
    public function index()
    {
        $model = new EnquiryModel();
        $model->select('enquiries.*, products.name AS product_name')->join('products', 'products.id = enquiries.product_id', 'left');
        return $this->render('enquiries/index', ['title' => 'Enquiries', 'rows' => $model->orderBy('enquiries.id', 'DESC')->paginate(10), 'pager' => $model->pager]);
    }

    public function view(int $id)
    {
        $model = new EnquiryModel();
        $row   = $model->select('enquiries.*, products.name AS product_name')->join('products', 'products.id = enquiries.product_id', 'left')
            ->where('enquiries.id', $id)->first() ?? throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        (new EnquiryModel())->update($id, ['is_read' => 1]);
        return $this->render('enquiries/view', ['title' => 'Enquiry', 'row' => $row]);
    }

    public function delete(int $id)
    {
        (new EnquiryModel())->delete($id);
        return redirect()->to(base_url('admin/enquiries'))->with('success', 'Enquiry deleted.');
    }
}
