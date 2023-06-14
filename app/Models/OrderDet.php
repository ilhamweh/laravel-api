<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDet extends Model
{
    protected $table = 'kring_ord_det';
    public $timestamps = false;
    protected $primaryKey = 'id_ord';
}
