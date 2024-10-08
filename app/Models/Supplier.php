<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'supplier_id');
    }
    public function purchasedetail()
    {
        return $this->hasMany(PurchaseDetail::class, 'supplier_id');
    }
}
