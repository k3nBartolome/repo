<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    use HasFactory;
    protected $table = 'purchase_request';
    protected $fillable = [
        'item_name',
        'quantity',
        'estimated_cost',
        'total_estimated_cost',
        'status',
        'approved_date',
        'denied_date',
        'requested_by',
        'approved_by',
        'denied_by',
        'denial_reason',
        'site_id',
    ];

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function deniedBy()
    {
        return $this->belongsTo(User::class, 'denied_by');
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
}
