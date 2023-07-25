<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'item_less_id',
        'item_name',
        'quantity',
        'budget_code',
        'type',
        'category',
        'date_expiry',
        'site_id',
        'is_active',
        'created_by',
    ];

    public function inventoryRecords()
    {
        return $this->hasMany(Inventory::class, 'item_id');
    }
    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
