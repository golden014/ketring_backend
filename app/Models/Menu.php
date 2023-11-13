<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'menu_name',
        'menu_price',
        'modal_price',
        'menu_picture',
        'menu_detail'
    ];

    public function order() {
        return $this->hasMany(Order::class);
    }
}
