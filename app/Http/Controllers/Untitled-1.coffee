 
'region' => optional($employee->lob->first())->region ?? 'N/A',
'month_milestone' => $day15Deadline instanceof Carbon ? $day15Deadline->format('F') : 'N/A',
'saturday_after_deadline' => $saturdayAfterDeadline instanceof Carbon ? $saturdayAfterDeadline->format('Y-m-d') : 'N/A',
'day_5deadline' => $day5Deadline instanceof Carbon ? $day5Deadline->format('Y-m-d') : 'N/A',
'day_10deadline' => $day10Deadline instanceof Carbon ? $day10Deadline->format('Y-m-d') : 'N/A',
'day_15deadline' => $day15Deadline instanceof Carbon ? $day15Deadline->format('Y-m-d') : 'N/A',
'government_numbers' => $isComplete ? 'Complete' : 'Incomplete',
'compliance_poc' => optional($employee->lob->first())->compliance_poc ?? 'N/A',
'critical_reqs' => $finalStatusComplete ? 'Complete' : 'Incomplete',
'employee_hired_month' => $employee->hired_month ?? 'N/A',               
'project_code' => optional($employee->lob->first())->project_code ?? 'N/A',
'employee_account_type' => $employee->account_type ?? 'N/A',
'employee_employee_status' => $employee->employee_status ?? 'N/A',
'employee_position' => $employee->account_associate ?? 'N/A',
'site' => optional(optional($employee->lob->first())->siteName)->name ?? 'N/A',
'team_name' => optional($employee->lob->first())->team_name ?? 'N/A',
'lob' => optional($employee->lob->first())->lob ?? 'N/A',
'employee_hired_date' => $employee->hired_date ?? 'N/A',
'workday_id' => optional($employee->workday->first())->workday_id ?? 'N/A',
'employee_id' => $employee->employee_id ?? 'TBA',
'employee_last_name' => $employee->last_name ?? 'N/A',
'employee_first_name' => $employee->first_name ?? 'N/A',
'employee_middle_name' => $employee->middle_name ?? 'N/A',
'employee_birth_date' => $employee->birthdate ?? 'N/A',
'employee_contact_number' => $employee->contact_number ?? 'N/A',
'employee_email' => $employee->email ?? 'N/A',
'nbi_final_status' => optional($employee->requirements->first())->nbi_final_status ?? 'N/A',
'nbi_remarks' => optional($employee->requirements->first())->nbi_remarks ?? 'N/A',
'nbi_validity_date' => optional($employee->requirements->first())->nbi_validity_date ?? 'N/A',
'nbi_printed_date' => optional($employee->requirements->first())->nbi_printed_date ?? 'N/A',
'nbi_submitted_date' => optional($employee->requirements->first())->nbi_submitted_date ?? 'N/A',
'cibi_final_status' => optional($employee->requirements->first())->cibi_final_status ?? 'N/A',
'cibi_checked_date' => optional($employee->requirements->first())->cibi_checked_date ?? 'N/A',
'cibi_remarks' => optional($employee->requirements->first())->cibi_remarks ?? 'N/A',
'dt_final_status' => optional($employee->requirements->first())->dt_final_status ?? 'N/A',
'dt_transaction_date' => optional($employee->requirements->first())->dt_transaction_date ?? 'N/A',
'dt_results_date' => optional($employee->requirements->first())->dt_results_date ?? 'N/A',
'peme_remarks' => optional($employee->requirements->first())->peme_remarks ?? 'N/A',
'peme_vendor' => null,
'bgc_final_status' => optional($employee->requirements->first())->bgc_final_status ?? 'N/A',
'bgc_remarks' => optional($employee->requirements->first())->bgc_remarks ?? 'N/A',
'bgc_endorsed_date' => optional($employee->requirements->first())->bgc_endorsed_date ?? 'N/A',
'bgc_results_date' => optional($employee->requirements->first())->bgc_results_date ?? 'N/A',
'bgc_vendor' => null,
'sss_proof_submitted_type' => optional($employee->requirements->first())->sss_proof_submitted_type ?? 'N/A',
'sss_remarks' => optional($employee->requirements->first())->sss_remarks ?? 'N/A',
'sss_number' => optional($employee->requirements->first())->sss_number ?? 'N/A',
'sss_submitted_date' => optional($employee->requirements->first())->sss_submitted_date ?? 'N/A',
'phic_proof_submitted_type' => optional($employee->requirements->first())->phic_proof_submitted_type ?? 'N/A',
'phic_remarks' => optional($employee->requirements->first())->phic_remarks ?? 'N/A',
'phic_number' => optional($employee->requirements->first())->phic_number ?? 'N/A',
'phic_submitted_date' => optional($employee->requirements->first())->phic_submitted_date ?? 'N/A',
'pagibig_proof_submitted_type' => optional($employee->requirements->first())->pagibig_proof_submitted_type ?? 'N/A',
'pagibig_remarks' => optional($employee->requirements->first())->pagibig_remarks ?? 'N/A',
'pagibig_number' => optional($employee->requirements->first())->pagibig_number ?? 'N/A',
'pagibig_submitted_date' => optional($employee->requirements->first())->pagibig_submitted_date ?? 'N/A',
'tin_proof_submitted_type' => optional($employee->requirements->first())->tin_proof_submitted_type ?? 'N/A',
'tin_remarks' => optional($employee->requirements->first())->tin_remarks ?? 'N/A',
'tin_number' => optional($employee->requirements->first())->tin_number ?? 'N/A',
'tin_submitted_date' => optional($employee->requirements->first())->tin_submitted_date ?? 'N/A',






                'contract_status' => optional($employee->workday->first())->contract_status ?? 'N/A',
                'contract_remarks' => optional($employee->workday->first())->contract_remarks ?? 'N/A',
                'contract_findings' => optional($employee->workday->first())->contract_findings ?? 'N/A',
                'completion' => optional($employee->workday->first())->completion ?? 'N/A',
                'per_findings' => optional($employee->workday->first())->per_findings ?? 'N/A',
                'ro_feedback' => optional($employee->workday->first())->ro_feedback ?? 'N/A',
            
                
                'nbi' => optional($employee->requirements->first())->nbi_file_name ? asset('storage/dt_files/' . optional($employee->requirements->first())->dt_file_name) : 'N/A',
                'nbi_last_updated_at' => optional($employee->requirements->first())->nbi_last_updated_at ?? 'N/A',
                'nbi_updated_by' => optional(optional($employee->requirements->first())->nbiUpdatedBy)->name ?? 'N/A',
                
               
                

                'dt_endorsed_date' => optional($employee->requirements->first())->dt_endorsed_date ?? 'N/A',
                'dt_remarks' => optional($employee->requirements->first())->dt_remarks ?? 'N/A',
                'dt' => optional($employee->requirements->first())->dt_file_name ? asset('storage/dt_files/' . optional($employee->requirements->first())->dt_file_name) : 'N/A',
                'dt_last_updated_at' => optional($employee->requirements->first())->dt_last_updated_at ?? 'N/A',
                'dt_updated_by' => optional(optional($employee->requirements->first())->dtUpdatedBy)->name ?? 'N/A',
                
                
                'peme' => optional($employee->requirements->first())->peme_file_name ? asset('storage/dt_files/' . optional($employee->requirements->first())->dt_file_name) : 'N/A',
               
                'peme_endorsed_date' => optional($employee->requirements->first())->peme_endorsed_date ?? 'N/A',
                'peme_results_date' => optional($employee->requirements->first())->peme_results_date ?? 'N/A',
                'peme_transaction_date' => optional($employee->requirements->first())->peme_transaction_date ?? 'N/A',
                'peme_final_status' => optional($employee->requirements->first())->peme_final_status ?? 'N/A',
                'peme_last_updated_at' => optional($employee->requirements->first())->peme_last_updated_at ?? 'N/A',
                'peme_updated_by' => optional(optional($employee->requirements->first())->pemeUpdatedBy)->name ?? 'N/A',
               
                
                 'sss_final_status' => optional($employee->requirements->first())->sss_final_status ?? 'N/A',
                'sss' => optional($employee->requirements->first())->sss_file_name ? asset('storage/dt_files/' . optional($employee->requirements->first())->dt_file_name) : 'N/A',
                'sss_last_updated_at' => optional($employee->requirements->first())->sss_last_updated_at ?? 'N/A',
                'sss_updated_by' => optional(optional($employee->requirements->first())->sssUpdatedBy)->name ?? 'N/A',
                
                'phic_final_status' => optional($employee->requirements->first())->phic_final_status ?? 'N/A',
                
               
                
                'phic' => optional($employee->requirements->first())->phic_file_name ? asset('storage/dt_files/' . optional($employee->requirements->first())->dt_file_name) : 'N/A',
                'phic_last_updated_at' => optional($employee->requirements->first())->phic_last_updated_at ?? 'N/A',
                'phic_updated_by' => optional(optional($employee->requirements->first())->phicUpdatedBy)->name ?? 'N/A',

                
                'pagibig_final_status' => optional($employee->requirements->first())->pagibig_final_status ?? 'N/A',

                
                'pagibig' => optional($employee->requirements->first())->pagibig_file_name ? asset('storage/dt_files/' . optional($employee->requirements->first())->dt_file_name) : 'N/A',
                'pagibig_last_updated_at' => optional($employee->requirements->first())->pagibig_last_updated_at ?? 'N/A',
                'pagibig_updated_by' => optional(optional($employee->requirements->first())->pagibigUpdatedBy)->name ?? 'N/A',

                
                'tin_final_status' => optional($employee->requirements->first())->tin_final_status ?? 'N/A',
                
                'tin' => optional($employee->requirements->first())->tin_file_name ? asset('storage/dt_files/' . optional($employee->requirements->first())->dt_file_name) : 'N/A',
                'tin_last_updated_at' => optional($employee->requirements->first())->tin_last_updated_at ?? 'N/A',
                'tin_updated_by' => optional(optional($employee->requirements->first())->tinUpdatedBy)->name ?? 'N/A',
                'health_certificate_validity_date' => optional($employee->requirements->first())->health_certificate_validity_date ?? 'N/A',
                'health_certificate_submitted_date' => optional($employee->requirements->first())->health_certificate_submitted_date ?? 'N/A',
                'health_certificate_remarks' => optional($employee->requirements->first())->health_certificate_remarks ?? 'N/A',
                'health_certificate' => optional($employee->requirements->first())->health_certificate_file_name ? asset('storage/dt_files/' . optional($employee->requirements->first())->dt_file_name) : 'N/A',
                'health_certificate_final_status' => optional($employee->requirements->first())->health_certificate_final_status ?? 'N/A',
                'health_certificate_last_updated_at' => optional($employee->requirements->first())->health_certificate_last_updated_at ?? 'N/A',
                'health_certificate_updated_by' => optional(optional($employee->requirements->first())->healthCertificateUpdatedBy)->name ?? 'N/A',
                'occupational_permit_validity_date' => optional($employee->requirements->first())->occupational_permit_validity_date ?? 'N/A',
                'occupational_permit_submitted_date' => optional($employee->requirements->first())->occupational_permit_submitted_date ?? 'N/A',
                'occupational_permit_remarks' => optional($employee->requirements->first())->occupational_permit_remarks ?? 'N/A',
                'occupational_permit' => optional($employee->requirements->first())->occupational_permit_file_name ? asset('storage/dt_files/' . optional($employee->requirements->first())->dt_file_name) : 'N/A',
                'occupational_permit_final_status' => optional($employee->requirements->first())->occupational_permit_final_status ?? 'N/A',
                'occupational_permit_last_updated_at' => optional($employee->requirements->first())->occupational_permit_last_updated_at ?? 'N/A',
                'occupational_permit_updated_by' => optional(optional($employee->requirements->first())->occupationalPermitUpdatedBy)->name ?? 'N/A',
                'ofac_checked_date' => optional($employee->requirements->first())->ofac_checked_date ?? 'N/A',
                'ofac_final_status' => optional($employee->requirements->first())->ofac_final_status ?? 'N/A',
                'ofac_remarks' => optional($employee->requirements->first())->ofac_remarks ?? 'N/A',
                'ofac' => optional($employee->requirements->first())->ofac_file_name ? asset('storage/dt_files/' . optional($employee->requirements->first())->dt_file_name) : 'N/A',
                'ofac_last_updated_at' => optional($employee->requirements->first())->ofac_last_updated_at ?? 'N/A',
                'ofac_updated_by' => optional(optional($employee->requirements->first())->ofacUpdatedBy)->name ?? 'N/A',
                'sam_checked_date' => optional($employee->requirements->first())->sam_checked_date ?? 'N/A',
                'sam_final_status' => optional($employee->requirements->first())->sam_final_status ?? 'N/A',
                'sam_remarks' => optional($employee->requirements->first())->sam_remarks ?? 'N/A',
                'sam' => optional($employee->requirements->first())->sam_file_name ? asset('storage/dt_files/' . optional($employee->requirements->first())->dt_file_name) : 'N/A',
                'sam_last_updated_at' => optional($employee->requirements->first())->sam_last_updated_at ?? 'N/A',
                'sam_updated_by' => optional(optional($employee->requirements->first())->samUpdatedBy)->name ?? 'N/A',
                'oig_checked_date' => optional($employee->requirements->first())->oig_checked_date ?? 'N/A',
                'oig_final_status' => optional($employee->requirements->first())->oig_final_status ?? 'N/A',
                'oig_remarks' => optional($employee->requirements->first())->oig_remarks ?? 'N/A',
                'oig' => optional($employee->requirements->first())->oig_file_name ? asset('storage/dt_files/' . optional($employee->requirements->first())->dt_file_name) : 'N/A',
                'oig_last_updated_at' => optional($employee->requirements->first())->oig_last_updated_at ?? 'N/A',
                'oig_updated_by' => optional(optional($employee->requirements->first())->oigUpdatedBy)->name ?? 'N/A',
               
               
               
                
                'cibi' => optional($employee->requirements->first())->cibi_file_name ? asset('storage/dt_files/' . optional($employee->requirements->first())->dt_file_name) : 'N/A',
                'cibi_last_updated_at' => optional($employee->requirements->first())->cibi_last_updated_at ?? 'N/A',
                'cibi_updated_by' => optional(optional($employee->requirements->first())->cibiUpdatedBy)->name ?? 'N/A',
               
               
                
                
                
                'bgc' => optional($employee->requirements->first())->bgc_file_name ? asset('storage/dt_files/' . optional($employee->requirements->first())->dt_file_name) : 'N/A',
                'bgc_last_updated_at' => optional($employee->requirements->first())->bgc_last_updated_at ?? 'N/A',
                'bgc_updated_by' => optional(optional($employee->requirements->first())->bgcUpdatedBy)->name ?? 'N/A',
                'bc' => optional($employee->requirements->first())->bc_file_name ? asset('storage/dt_files/' . optional($employee->requirements->first())->dt_file_name) : 'N/A',
                'bc_submitted_date' => optional($employee->requirements->first())->bc_submitted_date ?? 'N/A',
                'bc_proof_type' => optional($employee->requirements->first())->bc_proof_type ?? 'N/A',
                'bc_remarks' => optional($employee->requirements->first())->bc_remarks ?? 'N/A',
                'bc_last_updated_at' => optional($employee->requirements->first())->bc_last_updated_at ?? 'N/A',
                'bc_updated_by' => optional(optional($employee->requirements->first())->birthCertificateUpdatedBy)->name ?? 'N/A',
                'dbc' => optional($employee->requirements->first())->dbc_file_name ? asset('storage/dt_files/' . optional($employee->requirements->first())->dt_file_name) : 'N/A',
                'dbc_submitted_date' => optional($employee->requirements->first())->dbc_submitted_date ?? 'N/A',
                'dbc_proof_type' => optional($employee->requirements->first())->dbc_proof_type ?? 'N/A',
                'dbc_remarks' => optional($employee->requirements->first())->dbc_remarks ?? 'N/A',
                'dbc_last_updated_at' => optional($employee->requirements->first())->dbc_last_updated_at ?? 'N/A',
                'dbc_updated_by' => optional(optional($employee->requirements->first())->dependentBirthCertificateUpdatedBy)->name ?? 'N/A',
                'mc' => optional($employee->requirements->first())->mc_file_name ? asset('storage/dt_files/' . optional($employee->requirements->first())->dt_file_name) : 'N/A',
                'mc_submitted_date' => optional($employee->requirements->first())->mc_submitted_date ?? 'N/A',
                'mc_proof_type' => optional($employee->requirements->first())->mc_proof_type ?? 'N/A',
                'mc_remarks' => optional($employee->requirements->first())->mc_remarks ?? 'N/A',
                'mc_last_updated_at' => optional($employee->requirements->first())->mc_last_updated_at ?? 'N/A',
                'mc_updated_by' => optional(optional($employee->requirements->first())->marriageCertificateUpdatedBy)->name ?? 'N/A',
                'sr' => optional($employee->requirements->first())->sr_file_name ? asset('storage/dt_files/' . optional($employee->requirements->first())->dt_file_name) : 'N/A',
                'sr_submitted_date' => optional($employee->requirements->first())->sr_submitted_date ?? 'N/A',
                'sr_proof_type' => optional($employee->requirements->first())->sr_proof_type ?? 'N/A',
                'sr_remarks' => optional($employee->requirements->first())->sr_remarks ?? 'N/A',
                'sr_last_updated_at' => optional($employee->requirements->first())->sr_last_updated_at ?? 'N/A',
                'sr_updated_by' => optional(optional($employee->requirements->first())->scholasticRecordUpdatedBy)->name ?? 'N/A',
                'pe' => optional($employee->requirements->first())->pe_file_name ? asset('storage/dt_files/' . optional($employee->requirements->first())->dt_file_name) : 'N/A',
                'pe_submitted_date' => optional($employee->requirements->first())->pe_submitted_date ?? 'N/A',
                'pe_proof_type' => optional($employee->requirements->first())->pe_proof_type ?? 'N/A',
                'pe_remarks' => optional($employee->requirements->first())->pe_remarks ?? 'N/A',
                'pe_last_updated_at' => optional($employee->requirements->first())->pe_last_updated_at ?? 'N/A',
                'pe_updated_by' => optional(optional($employee->requirements->first())->previousEmploymentUpdatedBy)->name ?? 'N/A',
                'sd' => optional($employee->requirements->first())->sd_file_name ? asset('storage/dt_files/' . optional($employee->requirements->first())->dt_file_name) : 'N/A',
                'sd_submitted_date' => optional($employee->requirements->first())->sd_submitted_date ?? 'N/A',
                'sd_proof_type' => optional($employee->requirements->first())->sd_proof_type ?? 'N/A',
                'sd_remarks' => optional($employee->requirements->first())->sd_remarks ?? 'N/A',
                'sd_last_updated_at' => optional($employee->requirements->first())->sd_last_updated_at ?? 'N/A',
                'sd_updated_by' => optional(optional($employee->requirements->first())->supportingDocumentsUpdatedBy)->name ?? 'N/A',
                'employee_employment_status' => $employee->employment_status ?? 'N/A',
                'employee_added_by' => optional($employee->userAddedBy)->name ?? 'N/A',
                'employee_created_at' => $employee->created_at
                    ? $employee->created_at->format('Y-m-d')
                    : 'N/A',
                'employee_updated_by' => $employee->updated_by ?? 'N/A',
                'employee_updated_at' => $employee->updated_at
                    ? $employee->created_at->format('Y-m-d')
                    : 'N/A',

                'updated_at' => $employee->created_at
                    ? $employee->updated_at->format('Y-m-d')
                    : 'N/A',