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
        'received_status',
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
        'inventory_item_id',
        'site_id',
        'released_by',
        'approved_by',
        'approved_status',
        'denied_by',
        'received_by',
        'requested_by',
        'quantity_approved',
        'received_status',
        'received_quantity',
        'transferred_by',
        'transferred_from',
        'transferred_quantity',
        'transferred_to',
        'transferred_date',
        'cancelled_by',
        'cancelled_date',
        'cancellation_reason',
        'file_name',
        'path',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function item()
    {
        return $this->belongsTo(Items::class, 'item_id');
    }

    public function siteInventory()
    {
        return $this->belongsTo(SiteInventory::class, 'inventory_item_id');
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

    public function transferredBy()
    {
        return $this->belongsTo(User::class, 'transferred_by');
    }

    public function transferredFrom()
    {
        return $this->belongsTo(Site::class, 'transferred_from');
    }


    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }
}
