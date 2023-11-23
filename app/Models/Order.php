<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_date',
        'quantity',
        'detail',
        'status',
        'user_id',
        'menu_id',
        'allocation_id',
        'payment_proof'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function menu() {
        return $this->belongsTo(Menu::class);
    }

    public function allocation() {
        return $this->belongsTo(Allocation::class);
    }
}
