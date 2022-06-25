<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailOrdenes extends Model
{
    use HasFactory;
    protected $table = 'order_detail';
    protected $guarded = ['id'];
    protected $hidden = ['created_at','updated_at'];
}
