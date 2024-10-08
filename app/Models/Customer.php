<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function sales()
    {
        return $this->hasMany(Sale::class, 'customer_id');
    }
    public function salesreturn()
    {
        return $this->hasMany(SaleReturn::class, 'customer_id');
    }

}
