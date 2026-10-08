<?php

namespace App\Controllers\Admin;

use App\Models\EnquiryModel;

class Enquiries extends AdminBase
{
    public function index()
    {
        $model = (new EnquiryModel())->select('enquiries.*, products.name AS product_name')
            ->join('products', 'products.id = enquiries.product_id', 'left');
        $data = $this->listing($model, ['enquiries.name', 'enquiries.email', 'enquiries.message', 'products.name'], 'enquiries.id');
        return $this->render('enquiries/index', ['title' => 'Enquiries'] + $data);
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
