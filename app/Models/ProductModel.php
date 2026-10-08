<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table         = 'products';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['category_id','subcategory_id','name','slug','sku','short_desc','description','price','sale_price','stock','image','featured','is_current','is_peak','status'];
}
