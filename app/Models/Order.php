<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $incrementing = false;
    protected $table = 'kring_ord';
    public $timestamps = false;
    protected $primaryKey = 'id_ord';
    protected $fillable = ['flag_ord','tgl_confirm','u_store'];

}
