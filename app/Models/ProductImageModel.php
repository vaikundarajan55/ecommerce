<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductImageModel extends Model
{
    protected $table         = 'product_images';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $updatedField  = '';
    protected $allowedFields = ['product_id', 'image', 'sort_order'];

    /** Gallery images of one product, in display order. */
    public function forProduct(int $productId): array
    {
        return $this->where('product_id', $productId)->orderBy('sort_order')->orderBy('id')->findAll();
    }
}
