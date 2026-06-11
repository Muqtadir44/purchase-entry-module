<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
