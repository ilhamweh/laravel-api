<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'kring_ord';
    public $timestamps = false;
    protected $primaryKey = 'id_ord';

}
