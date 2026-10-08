<?php

namespace App\Models;

use CodeIgniter\Model;

/** Key / value store for editable website content (About us, Contact details). */
class SettingModel extends Model
{
    protected $table         = 'settings';
    protected $primaryKey    = 'skey';
    protected $returnType    = 'array';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['skey','svalue','updated_at'];

    /** All settings as [key => value]. */
    public function all(): array
    {
        return array_column($this->findAll(), 'svalue', 'skey');
    }

    /** Insert or update several settings at once. */
    public function saveMany(array $values): void
    {
        $now = date('Y-m-d H:i:s');
        foreach ($values as $key => $value) {
            $this->db->table($this->table)->replace(['skey' => $key, 'svalue' => $value, 'updated_at' => $now]);
        }
    }
}
