<?php

namespace App\Controllers\Admin;

use App\Models\EnquiryModel;

/** One inbox for product enquiries and messages from the website Contact us form. */
class Enquiries extends AdminBase
{
    public function index()
    {
        $source = (string) $this->request->getGet('source');
        $model  = (new EnquiryModel())->select('enquiries.*, products.name AS product_name')
            ->join('products', 'products.id = enquiries.product_id', 'left');
        if (in_array($source, ['product', 'contact'], true)) {
            $model->where('enquiries.source', $source);
        } else {
            $source = '';
        }
        $data = $this->listing($model, ['enquiries.name', 'enquiries.email', 'enquiries.subject', 'enquiries.message', 'products.name'], 'enquiries.id');

        $counts = [];
        foreach ((new EnquiryModel())->select('source, COUNT(*) total, SUM(is_read = 0) unread', false)->groupBy('source')->findAll() as $c) {
            $counts[$c['source']] = ['total' => (int) $c['total'], 'unread' => (int) $c['unread']];
        }
        return $this->render('enquiries/index', ['title' => 'Enquiries', 'source' => $source, 'counts' => $counts] + $data);
    }

    public function view(int $id)
    {
        $model = new EnquiryModel();
        $row   = $model->select('enquiries.*, products.name AS product_name, products.slug AS product_slug')->join('products', 'products.id = enquiries.product_id', 'left')
            ->where('enquiries.id', $id)->first() ?? throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        (new EnquiryModel())->update($id, ['is_read' => 1]);
        return $this->render('enquiries/view', ['title' => $row['source'] === 'contact' ? 'Contact message' : 'Product enquiry', 'row' => $row]);
    }

    public function delete(int $id)
    {
        (new EnquiryModel())->delete($id);
        return redirect()->to(base_url('admin/enquiries'))->with('success', 'Enquiry deleted.');
    }
}
