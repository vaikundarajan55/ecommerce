<?php

namespace App\Controllers\Admin;

use App\Models\ContactModel;

class Contacts extends AdminBase
{
    public function index()
    {
        $model = new ContactModel();
        return $this->render('contacts/index', ['title' => 'Contact messages', 'rows' => $model->orderBy('id', 'DESC')->paginate(10), 'pager' => $model->pager]);
    }

    public function view(int $id)
    {
        $model = new ContactModel();
        $row   = $model->find($id) ?? throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $model->update($id, ['is_read' => 1]);
        return $this->render('contacts/view', ['title' => 'Message', 'row' => $row]);
    }

    public function delete(int $id)
    {
        (new ContactModel())->delete($id);
        return redirect()->to(base_url('admin/contacts'))->with('success', 'Message deleted.');
    }
}
