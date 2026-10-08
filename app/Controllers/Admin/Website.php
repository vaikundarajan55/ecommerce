<?php

namespace App\Controllers\Admin;

use App\Libraries\Uploader;
use App\Models\SettingModel;

/** Editable website content: About us page and store contact details. */
class Website extends AdminBase
{
    public function about()
    {
        return $this->render('website/about', ['title' => 'About us page', 's' => (new SettingModel())->all()]);
    }

    public function saveAbout()
    {
        $rules = [
            'about_tagline' => ['label' => 'Header tagline', 'rules' => 'permit_empty|max_length[200]'],
            'about_heading' => ['label' => 'Story heading', 'rules' => 'required|min_length[3]|max_length[150]'],
            'about_body'    => ['label' => 'Story text', 'rules' => 'required|min_length[10]'],
            'image'         => ['label' => 'Story image', 'rules' => 'permit_empty|is_image[image]|max_size[image,3072]'],
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $model = new SettingModel();
        $old   = $model->all()['about_image'] ?? null;
        $image = $this->request->getPost('remove_image') ? null : Uploader::image($this->request->getFile('image'), 'pages', $old);
        if ($image === null && $old) {
            Uploader::delete($old, 'pages');
        }

        $model->saveMany([
            'about_tagline'  => trim((string) $this->request->getPost('about_tagline')),
            'about_heading'  => trim((string) $this->request->getPost('about_heading')),
            'about_body'     => trim((string) $this->request->getPost('about_body')),
            'about_image'    => $image ?? '',
            'about_stats'    => $this->rows('stats', ['value', 'label']),
            'about_promises' => $this->rows('promises', ['title', 'text']),
        ]);
        return redirect()->to(base_url('admin/website/about'))->with('success', 'About us page updated.');
    }

    public function contact()
    {
        return $this->render('website/contact', ['title' => 'Contact details', 's' => (new SettingModel())->all()]);
    }

    public function saveContact()
    {
        $rules = [
            'contact_tagline' => ['label' => 'Contact page tagline', 'rules' => 'permit_empty|max_length[200]'],
            'contact_address' => ['label' => 'Address', 'rules' => 'required|max_length[255]'],
            'contact_phone'   => ['label' => 'Phone', 'rules' => 'required|max_length[30]'],
            'contact_email'   => ['label' => 'Email', 'rules' => 'required|valid_email'],
            'contact_hours'   => ['label' => 'Opening hours', 'rules' => 'permit_empty|max_length[100]'],
            'contact_map'     => ['label' => 'Google Maps embed link', 'rules' => 'permit_empty|valid_url_strict[https]|max_length[1000]'],
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = [];
        foreach (array_keys($rules) as $key) {
            $data[$key] = trim((string) $this->request->getPost($key));
        }
        (new SettingModel())->saveMany($data);
        return redirect()->to(base_url('admin/website/contact'))->with('success', 'Contact details updated.');
    }

    /** Repeating form rows (e.g. stats[0][value]) -> JSON, skipping rows left blank. */
    private function rows(string $field, array $keys): string
    {
        $out = [];
        foreach ((array) $this->request->getPost($field) as $row) {
            $row = array_map(static fn ($k) => trim((string) ($row[$k] ?? '')), array_combine($keys, $keys));
            if (implode('', $row) !== '') {
                $out[] = $row;
            }
        }
        return json_encode($out, JSON_UNESCAPED_UNICODE);
    }
}
