<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClassHistoryExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = collect($data);
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'id',
            'within_sla',
            'condition',
            'requested_by',
            'original_start_date',
            'changes',
            'agreed_start_date',
            'approved_date',
            'cancelled_date',
            'wfm_date_requested',
            'notice_weeks',
            'external_target',
            'internal_target',
            'notice_days',
            'pipeline_utilized',
            'total_target',
            'remarks',
            'status',
            'category',
            'type_of_hiring',
            'update_status',
            'approved_status',
            'with_erf',
            'erf_number',
            'approved_by',
            'cancelled_by',
            'ta',
            'wave_no',
            'wf',
            'tr',
            'cl',
            'op',
            'created_by',
            'site_id',
            'program_id',
            'updated_by',
            'date_range_id',
            'created_at',
            'updated_at'

        ];
    }
}
