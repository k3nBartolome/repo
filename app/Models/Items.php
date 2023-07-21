<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'item_name',
        'quantity',
        'budget_code',
        'type',
        'category',
        'description',
        'date_expiry',
    ];

    public function inventoryRecords()
    {
        return $this->hasMany(Inventory::class, 'item_id');
    }
}
