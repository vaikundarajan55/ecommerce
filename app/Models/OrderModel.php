<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table         = 'orders';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['order_no','user_id','name','email','phone','address','city','pincode','subtotal','shipping','total','payment_method','payment_status','txn_id','ip_address','status'];
}
