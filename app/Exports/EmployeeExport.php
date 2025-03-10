<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeExport implements FromCollection, WithHeadings, ShouldAutoSize
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
            'REGION',
            'MONTH MILESTONE',
            'WEEK ENDING',
            '5th DAY DEADLINE',
            '10th DAY DEADLINE',
            '15th DAY DEADLINE',
            'GOVERNMENT NUMBERS',
            'COMPLIANCE POC',
            'CRITICAL REQS',
            'HIRE MONTH',
            'PROJECT CODE',
            'ACCOUNT TYPE',
            'EMPLOYEE STATUS',
            'POSITION',
            'SITE',
            'TEAM NAME',
            'LOB',
            'HIRE DATE',
            'WORKDAY ID',
            'EMPLOYEE ID',
            'LAST NAME',
            'FIRST NAME',
            'MIDDLE NAME',
            'DATE OF BIRTH',
            'CONTACT NUMBER',
            'EMAIL ADDRESS',
            'NBI FINAL STATUS',
            'NBI REMARKS',
            'NBI VALIDITY DATE',
            'NBI PRINTED DATE',
            'NBI SUBMITTED DATE',
            'CIBI FINAL STATUS',
            'CIBI SEARCH DATE',
            'CIBI REMARKS',
            'DT FINAL STATUS',
            'DT TRANSACTION DATE',
            'DT RESULTS DATE',
            'PEME FINAL STATUS',
            'PEME REMARKS',
            'PEME VENDOR',
            'BGC FINAL STATUS',
            'BGC REMARKS',
            'BGC RESULTS',
            'BGC ENDORSEMENT DATE',
            'BGC RESULTS DATE',
            'BGC VENDOR',
            'PROOF OF SSS SUBMITTED',
            'SSS REMARKS',
            'SSS NUMBER',
            'SSS SUBMITTED DATE',
            'PROOF OF PHIC SUBMITTED',
            'PHIC REMARKS',
            'PHIC NUMBER',
            'PHIC SUBMITTED DATE',
            'PROOF OF HDMF SUBMITTED',
            'HDMF REMARKS',
            'HDMF NUMBER',
            'HDMF SUBMITTED DATE',
            'PROOF OF TIN SUBMITTED',
            'TIN REMARKS',
            'TIN NUMBER',
            'TIN SUBMITTED DATE',
            'HEALTH CERTIFICATE FINAL STATUS',
            'HEALTH CERTIFICATE REMARKS',
            'HEALTH CERTIFICATE VALIDITY DATE',
            'HEALTH CERTIFICATE SUBMITTED DATE',
            'OCCUPATIONAL FINAL STATUS',
            'OCCUPATIONAL REMARKS',
            'OCCUPATIONAL VALIDITY DATE',
            'OCCUPATIONAL SUBMITTED DATE',
            'BIRTH CERTIFICATE',
            'BIRTH CERTIFICATE SUBMITTED DATE',
            "DEPENDENT'S BIRTH CERTIFICATE",
            "DEPENDENT'S BIRTH CERTIFICATE SUBMITTED DATE",
            'MARRIAGE CERTIFICATE',
            'MARRIAGE CERTIFICATE SUBMITTED DATE',
            'SCHOLASTIC RECORD',
            'SCHOLASTIC RECORD SUBMITTED DATE',
            'PROOF OF SCHOLASTIC RECORD',
            'SCHOLASTIC RECORD REMARKS',
            'PREVIOUS EMPLOYMENT',
            'PREVIOUS EMPLOYMENT SUBMITTED DATE',
            'PROOF OF PREVIOUS EMPLOYMENT',
            'PREVIOUS EMPLOYMENT REMARKS',
            'OFAC FINAL STATUS',
            'OFAC REMARKS',
            'OFAC CHECKED DATE',
            'SAM FINAL STATUS',
            'SAM CHECKED DATE',
            'SAM REMARKS',
            'OIG FINAL STATUS',
            'OIG CHECKED DATE',
            'OIG REMARKS',
            'CONTRACT',
            'CONTRACT REMARKS',
            'CONTRACT FINDINGS',
            'WITH FINDINGS',
            'DATE ENDORSED TO COMPLIANCE',
            'RETURN TO H&S (WITH FINDINGS)',
            'LAST RECEIVED FROM H&S (WITH FINDINGS)',
            '201 STATUS',
            'COMPLIANCE REMARKS',
            'CONTRACT STATUS',
            'PER FINDINGS',
            'RO FEEDBACK',
            'NBI (attachment)',
            'NBI LAST UPDATED AT',
            'NBI UPDATED BY',
            'DT ENDORSEMENT DATE',
            'DT REMARKS',
            'DT (attachment)',
            'DT LAST UPDATED AT',
            'DT UPDATED BY',
            'PEME (attachment)',
            'PEME LAST UPDATED AT',
            'PEME UPDATED BY',
            'SSS (attachment)',
            'SSS FINAL STATUS',
            'SSS LAST UPDATED AT',
            'SSS UPDATED BY',
            'PHIC FINAL STATUS',
            'PHIC (attachment)',
            'PHIC LAST UPDATED AT',
            'PHIC UPDATED BY',
            'HDMF FINAL STATUS',
            'HDMF (attachment)',
            'HDMF LAST UPDATED AT',
            'HDMF UPDATED BY',
            'TIN FINAL STATUS',
            'TIN (attachment)',
            'TIN LAST UPDATED AT',
            'TIN UPDATED BY',
            'HEALTH CERTIFICATE (attachment)',
            'HEALTH CERTIFICATE LAST UPDATED AT',
            'HEALTH CERTIFICATE UPDATED BY',
            'OCCUPATIONAL (attachment)',
            'OCCUPATIONAL LAST UPDATED AT',
            'OCCUPATIONAL UPDATED BY',
            'OFAC (attachment)',
            'OFAC LAST UPDATED AT',
            'OFAC UPDATED BY',
            'SAM (attachment)',
            'SAM LAST UPDATED AT',
            'SAM UPDATED BY',
            'OIG (attachment)',
            'OIG LAST UPDATED AT',
            'OIG UPDATED BY',
            'CIBI CHECKED DATE',
            'CIBI (attachment)',
            'CIBI LAST UPDATED AT',
            'CIBI UPDATED BY',
            'BGC (attachment)',
            'BGC LAST UPDATED AT',
            'BGC UPDATED BY',
            'PROOF OF BIRTH CERTIFICATE (attachment)',
            'BIRTH CERTIFICATE REMARKS',
            'BIRTH CERTIFICATE LAST UPDATED AT',
            'BIRTH CERTIFICATE UPDATED BY',
            "PROOF OF DEPENDENT'S BIRTH CERTIFICATE (attachment)",
            "DEPENDENT'S BIRTH CERTIFICATE REMARKS",
            "DEPENDENT'S BIRTH CERTIFICATE LAST UPDATED AT",
            "DEPENDENT'S BIRTH CERTIFICATE UPDATED BY",
            'PROOF OF MARRIAGE CERTIFICATE (attachment)',
            'MARRIAGE CERTIFICATE REMARKS',
            'MARRIAGE CERTIFICATE LAST UPDATED AT',
            'MARRIAGE CERTIFICATE UPDATED BY',
            'SCHOLASTIC RECORD LAST UPDATED AT',
            'SCHOLASTIC RECORD UPDATED BY',
            'PREVIOUS EMPLOYMENT LAST UPDATED AT',
            'PREVIOUS EMPLOYMENT UPDATED BY',
            'SUPPORTING DOCUMENT (attachment)',
            'SUPPORTING DOCUMENT SUBMITTED DATE',
            'PROOF OF SUPPORTING DOCUMENT',
            'SUPPORTING DOCUMENT REMARKS',
            'SUPPORTING DOCUMENT LAST UPDATED AT',
            'SUPPORTING DOCUMENT UPDATED BY',
            'JO STATUS',
            'EMPLOYEE ADDED BY',
            'EMPLOYEE CREATED AT',
            'EMPLOYEE UPDATED BY',
            'EMPLOYEE UPDATED AT',
            'UPDATED AT',
        ];
    }
}
