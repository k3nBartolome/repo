<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'inventory_id',
        'transaction_no',
        'transaction_type',
        'request_type',
        'status',
        'award_status',
        'denial_reason',
        'remarks',
        'date_released',
        'date_requested',
        'date_approved',
        'date_denied',
        'date_received',
        'awardee_name',
        'awardee_hrid',
        'file_name',
        'path',
        'item_id',
        'site_id',
        'released_by',
        'approved_by',
        'denied_by',
        'received_by',
        'processed_by',
        'requested_by',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function releasedBy()
    {
        return $this->belongsTo(User::class, 'released_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function deniedBy()
    {
        return $this->belongsTo(User::class, 'denied_by');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
