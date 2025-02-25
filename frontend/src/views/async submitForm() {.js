async submitForm() {
      if (this.isSubmitting) return;

      this.isSubmitting = true;

      try {
        const apiUrl = `http://127.0.0.1:8000/api/update/employee/${this.$route.params.id}`;

        const formData = new FormData();

        if (!this.form || typeof this.form !== "object") {
          console.error("Form data is undefined or not an object:", this.form);
          alert("Form data is not initialized properly.");
          this.isSubmitting = false;
          return;
        }

        // General form data
        Object.keys(this.form).forEach((key) => {
          formData.append(key, this.form[key] || "");
        });

        // Append all other fields
        formData.append("first_name", this.first_name || "");
        formData.append("middle_name", this.middle_name || "");
        formData.append("last_name", this.last_name || "");
        formData.append("account_associate", this.account_associate || "");
        formData.append("account_type", this.account_type || "");
        formData.append("employee_id", this.employee_id || "");
        formData.append("contact_number", this.contact_number || "");
        formData.append("email", this.email || "");
        formData.append("birthdate", this.birthdate || "");
        formData.append("hired_date", this.hired_date || "");
        formData.append("employee_status", this.employee_status || "");
        formData.append("employment_status", this.employment_status || "");
        formData.append("hired_month", this.hired_month || "");
        formData.append("updated_by", this.$store.state.user_id);

        // LOB Data
        formData.append("region", this.region || "");
        formData.append("site", this.site || "");
        formData.append("lob", this.lob || "");
        formData.append("team_name", this.team_name || "");
        formData.append("project_code", this.project_code || "");
        formData.append("compliance_poc", this.compliance_poc || "");

        // Workday Data
        formData.append("workday_id", this.workday_id || "");
        formData.append("ro_feedback", this.ro_feedback || "");
        formData.append("per_findings", this.per_findings || "");
        formData.append("completion", this.completion || "");
        formData.append("contract_findings", this.contract_findings || "");
        formData.append("contract_remarks", this.contract_remarks || "");
        formData.append("contract_status", this.contract_status || "");

        formData.append(
          "nbi_final_status",
          this.nbi_final_status || "NO STATUS"
        );
        formData.append("dt_final_status", this.dt_final_status || "NO STATUS");
        formData.append(
          "peme_final_status",
          this.peme_final_status || "NO STATUS"
        );
        formData.append(
          "sss_final_status",
          this.sss_final_status || "NO STATUS"
        );
        formData.append(
          "phic_final_status",
          this.phic_final_status || "NO STATUS"
        );
        formData.append(
          "pagibig_final_status",
          this.pagibig_final_status || "NO STATUS"
        );
        formData.append(
          "tin_final_status",
          this.tin_final_status || "NO STATUS"
        );
        formData.append(
          "health_certificate_final_status",
          this.health_certificate_final_status || "NO STATUS"
        );
        formData.append(
          "occupational_permit_final_status",
          this.occupational_permit_final_status || "NO STATUS"
        );
        formData.append(
          "ofac_final_status",
          this.ofac_final_status || "NO STATUS"
        );
        formData.append(
          "sam_final_status",
          this.sam_final_status || "NO STATUS"
        );
        formData.append(
          "oig_final_status",
          this.oig_final_status || "NO STATUS"
        );
        formData.append(
          "cibi_final_status",
          this.cibi_final_status || "NO STATUS"
        );
        formData.append(
          "bgc_final_status",
          this.bgc_final_status || "NO STATUS"
        );
        formData.append(
          "birth_certificate_final_status",
          this.birth_certificate_final_status || "NO STATUS"
        );
        formData.append(
          "dependent_birth_certificate_final_status",
          this.dependent_birth_certificate_final_status || "NO STATUS"
        );
        formData.append(
          "marriage_certificate_final_status",
          this.marriage_certificate_final_status || "NO STATUS"
        );
        formData.append(
          "scholastic_record_final_status",
          this.scholastic_record_final_status || "NO STATUS"
        );
        formData.append(
          "previous_employment_final_status",
          this.previous_employment_final_status || "NO STATUS"
        );
        formData.append(
          "supporting_documents_final_status",
          this.supporting_documents_final_status || "NO STATUS"
        );

        formData.append("nbi_final_status", this.nbi_final_status || "");
        formData.append("nbi_validity_date", this.nbi_validity_date || "");
        formData.append("nbi_submitted_date", this.nbi_submitted_date || "");
        formData.append("nbi_printed_date", this.nbi_printed_date || "");
        formData.append("nbi_remarks", this.nbi_remarks || "");
        formData.append("nbi_updated_by", this.nbi_updated_by || "");
        formData.append("dt_final_status", this.dt_final_status || "");
        formData.append("dt_results_date", this.dt_results_date || "");
        formData.append("dt_transaction_date", this.dt_transaction_date || "");
        formData.append("dt_endorsed_date", this.dt_endorsed_date || "");
        formData.append("dt_remarks", this.dt_remarks || "");
        formData.append("dt_updated_by", this.dt_updated_by || "");
        formData.append("peme_final_status", this.peme_final_status || "");
        formData.append("peme_results_date", this.peme_results_date || "");
        formData.append(
          "peme_transaction_date",
          this.peme_transaction_date || ""
        );
        formData.append("peme_endorsed_date", this.peme_endorsed_date || "");
        formData.append("peme_remarks", this.peme_remarks || "");
        formData.append("peme_updated_by", this.peme_updated_by || "");
        formData.append("sss_final_status", this.sss_final_status || "");
        formData.append("sss_submitted_date", this.sss_submitted_date || "");
        formData.append("sss_remarks", this.sss_remarks || "");
        formData.append("sss_number", this.sss_number || "");
        formData.append(
          "sss_proof_submitted_type",
          this.sss_proof_submitted_type || ""
        );
        formData.append("sss_updated_by", this.sss_updated_by || "");
        formData.append("phic_submitted_date", this.phic_submitted_date || "");
        formData.append("phic_final_status", this.phic_final_status || "");
        formData.append(
          "phic_proof_submitted_type",
          this.phic_proof_submitted_type || ""
        );
        formData.append("phic_remarks", this.phic_remarks || "");
        formData.append("phic_number", this.phic_number || "");
        formData.append("phic_updated_by", this.phic_updated_by || "");
        formData.append(
          "pagibig_submitted_date",
          this.pagibig_submitted_date || ""
        );
        formData.append(
          "pagibig_final_status",
          this.pagibig_final_status || ""
        );
        formData.append(
          "pagibig_proof_submitted_type",
          this.pagibig_proof_submitted_type || ""
        );
        formData.append("pagibig_remarks", this.pagibig_remarks || "");
        formData.append("pagibig_number", this.pagibig_number || "");
        formData.append("pagibig_updated_by", this.pagibig_updated_by || "");
        formData.append("tin_submitted_date", this.tin_submitted_date || "");
        formData.append("tin_final_status", this.tin_final_status || "");
        formData.append(
          "tin_proof_submitted_type",
          this.tin_proof_submitted_type || ""
        );
        formData.append("tin_remarks", this.tin_remarks || "");
        formData.append("tin_number", this.tin_number || "");
        formData.append("tin_updated_by", this.tin_updated_by || "");
        formData.append(
          "health_certificate_validity_date",
          this.health_certificate_validity_date || ""
        );
        formData.append(
          "health_certificate_final_status",
          this.health_certificate_final_status || ""
        );
        formData.append(
          "health_certificate_submitted_date",
          this.health_certificate_submitted_date || ""
        );
        formData.append(
          "health_certificate_remarks",
          this.health_certificate_remarks || ""
        );
        formData.append(
          "health_certificate_updated_by",
          this.health_certificate_updated_by || ""
        );
        formData.append(
          "occupational_permit_validity_date",
          this.occupational_permit_validity_date || ""
        );
        formData.append(
          "occupational_permit_submitted_date",
          this.occupational_permit_submitted_date || ""
        );
        formData.append(
          "occupational_permit_remarks",
          this.occupational_permit_remarks || ""
        );
        formData.append(
          "occupational_permit_final_status",
          this.occupational_permit_final_status || ""
        );
        formData.append(
          "occupational_permit_updated_by",
          this.occupational_permit_updated_by || ""
        );
        formData.append("ofac_checked_date", this.ofac_checked_date || "");
        formData.append("ofac_final_status", this.ofac_final_status || "");
        formData.append("ofac_remarks", this.ofac_remarks || "");
        formData.append("ofac_updated_by", this.ofac_updated_by || "");
        formData.append("sam_checked_date", this.sam_checked_date || "");
        formData.append("sam_final_status", this.sam_final_status || "");
        formData.append("sam_remarks", this.sam_remarks || "");
        formData.append("sam_updated_by", this.sam_updated_by || "");
        formData.append("oig_checked_date", this.oig_checked_date || "");
        formData.append("oig_final_status", this.oig_final_status || "");
        formData.append("oig_remarks", this.oig_remarks || "");
        formData.append("oig_updated_by", this.oig_updated_by || "");
        formData.append("cibi_checked_date", this.cibi_checked_date || "");
        formData.append("cibi_final_status", this.cibi_final_status || "");
        formData.append("cibi_remarks", this.cibi_remarks || "");
        formData.append("cibi_updated_by", this.cibi_updated_by || "");
        formData.append("bgc_endorsed_date", this.bgc_endorsed_date || "");
        formData.append("bgc_results_date", this.bgc_results_date || "");
        formData.append("bgc_final_status", this.bgc_final_status || "");
        formData.append("bgc_remarks", this.bgc_remarks || "");
        formData.append("bgc_updated_by", this.bgc_updated_by || "");
        formData.append(
          "birth_certificate_submitted_date",
          this.birth_certificate_submitted_date || ""
        );
        formData.append(
          "birth_certificate_proof_type",
          this.birth_certificate_proof_type || ""
        );
        formData.append(
          "birth_certificate_remarks",
          this.birth_certificate_remarks || ""
        );
        formData.append(
          "birth_certificate_updated_by",
          this.birth_certificate_updated_by || ""
        );
        formData.append(
          "dependent_birth_certificate_submitted_date",
          this.dependent_birth_certificate_submitted_date || ""
        );
        formData.append(
          "dependent_birth_certificate_proof_type",
          this.dependent_birth_certificate_proof_type || ""
        );
        formData.append(
          "dependent_birth_certificate_remarks",
          this.dependent_birth_certificate_remarks || ""
        );
        formData.append(
          "dependent_birth_certificate_updated_by",
          this.dependent_birth_certificate_updated_by || ""
        );
        formData.append(
          "marriage_certificate_submitted_date",
          this.marriage_certificate_submitted_date || ""
        );
        formData.append(
          "marriage_certificate_proof_type",
          this.marriage_certificate_proof_type || ""
        );
        formData.append(
          "marriage_certificate_remarks",
          this.marriage_certificate_remarks || ""
        );
        formData.append(
          "marriage_certificate_updated_by",
          this.marriage_certificate_updated_by || ""
        );
        formData.append(
          "scholastic_record_submitted_date",
          this.scholastic_record_submitted_date || ""
        );
        formData.append(
          "scholastic_record_proof_type",
          this.scholastic_record_proof_type || ""
        );
        formData.append(
          "scholastic_record_remarks",
          this.scholastic_record_remarks || ""
        );
        formData.append(
          "scholastic_record_updated_by",
          this.scholastic_record_updated_by || ""
        );
        formData.append(
          "previous_employment_submitted_date",
          this.previous_employment_submitted_date || ""
        );
        formData.append(
          "previous_employment_proof_type",
          this.previous_employment_proof_type || ""
        );
        formData.append(
          "previous_employment_remarks",
          this.previous_employment_remarks || ""
        );
        formData.append(
          "previous_employment_updated_by",
          this.previous_employment_updated_by || ""
        );
        formData.append(
          "supporting_documents_submitted_date",
          this.supporting_documents_submitted_date || ""
        );
        formData.append(
          "supporting_documents_proof_type",
          this.supporting_documents_proof_type || ""
        );
        formData.append(
          "supporting_documents_remarks",
          this.supporting_documents_remarks || ""
        );
        formData.append(
          "supporting_documents_updated_by",
          this.supporting_documents_updated_by || ""
        );
       
        if (this.nbi_proof instanceof File) {
          formData.append("nbi_proof", this.nbi_proof);
        }
        if (this.dt_proof instanceof File) {
          formData.append("dt_proof", this.dt_proof);
        }
        if (this.peme_proof instanceof File) {
          formData.append("peme_proof", this.peme_proof);
        }
        if (this.sss_proof instanceof File) {
          formData.append("sss_proof", this.sss_proof);
        }
        if (this.phic_proof instanceof File) {
          formData.append("phic_proof", this.phic_proof);
        }
        if (this.pagibig_proof instanceof File) {
          formData.append("pagibig_proof", this.pagibig_proof);
        }
        if (this.tin_proof instanceof File) {
          formData.append("tin_proof", this.tin_proof);
        }
        if (this.health_certificate_proof instanceof File) {
          formData.append(
            "health_certificate_proof",
            this.health_certificate_proof
          );
        }
        if (this.occupational_permit_proof instanceof File) {
          formData.append(
            "occupational_permit_proof",
            this.occupational_permit_proof
          );
        }
        if (this.ofac_proof instanceof File) {
          formData.append("ofac_proof", this.ofac_proof);
        }
        if (this.sam_proof instanceof File) {
          formData.append("sam_proof", this.sam_proof);
        }
        if (this.oig_proof instanceof File) {
          formData.append("oig_proof", this.oig_proof);
        }
        if (this.cibi_proof instanceof File) {
          formData.append("cibi_proof", this.cibi_proof);
        }
        if (this.bgc_proof instanceof File) {
          formData.append("bgc_proof", this.bgc_proof);
        }
        if (this.birth_certificate_proof instanceof File) {
          formData.append(
            "birth_certificate_proof",
            this.birth_certificate_proof
          );
        }
        if (this.dependent_birth_certificate_proof instanceof File) {
          formData.append(
            "dependent_birth_certificate_proof",
            this.dependent_birth_certificate_proof
          );
        }
        if (this.marriage_certificate_proof instanceof File) {
          formData.append(
            "marriage_certificate_proof",
            this.marriage_certificate_proof
          );
        }
        if (this.scholastic_record_proof instanceof File) {
          formData.append(
            "scholastic_record_proof",
            this.scholastic_record_proof
          );
        }
        if (this.previous_employment_proof instanceof File) {
          formData.append(
            "previous_employment_proof",
            this.previous_employment_proof
          );
        }
        if (this.supporting_documents_proof instanceof File) {
          formData.append(
            "supporting_documents_proof",
            this.supporting_documents_proof
          );
        }
        const response = await axios.post(apiUrl, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });
        alert(response.data.message || "Update successful!");
        this.$router.push({
          name: "OnboardingUpdateSelection",
          params: { id: this.$route.params.id },
        });
      } catch (error) {
        console.error(
          "Error submitting form",
          error.response ? error.response.data : error.message
        );
        alert("An error occurred while updating data.");
      } finally {
        this.isSubmitting = false;
      }
    },