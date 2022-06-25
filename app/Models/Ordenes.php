<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordenes extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $guarded = ['id'];
    protected $hidden = ['created_at','updated_at'];

    public function detail()
    {
        return $this->hasMany('App\Models\DetailOrdenes','id_order','id_order')
            ->join('menus','menus.id','=','order_detail.id_menu')
            ->select('order_detail.id_order','menus.name','order_detail.price','order_detail.quantity','order_detail.sub_total');
    }
}
