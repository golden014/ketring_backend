<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'allocation_date',
        'end_order_date',
        'end_order_time',
        'menu_id',
    ];

    public function menu() {
        return $this->belongsTo(Menu::class);
    }

    public function order() {
        return $this->hasMany(Order::class);
    }
}
