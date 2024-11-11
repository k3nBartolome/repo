<template>
  <h1>asd</h1>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      //employee
      employee_id: "",
      last_name: "",
      first_name: "",
      middle_name: "",
      employee_status: "",
      hired_date: "",
      hired_month: "",
      birthdate: "",
      contact_number: "",
      email: "",
      account_associate: "",
      employee_added_by: "",
      employment_status: "",
      errors: {},
      showModalAdd: false,
      showModalImport: false,
      file: null,
      /*  //lob
      region: "",
      site: "",
      lob: "",
      team_name: "",
      project_code: "",
      //requirements
      nbi: "",
      nbi_remarks: "",
      nbi_validity_date: "",
      nbi_printed_date: "",
      nbi_file_name: "",
      nbi_file_path: null,
      dt_results: "",
      dt_transaction_date: "",
      dt_result_date: "",
      dt_file_name: "",
      dt_file_path: null,
      peme_status: "",
      peme_remarks: "",
      peme_file_name: "",
      peme_file_path: null,
      bgc: "",
      bgc_remarks: "",
      bgc_endorsed_date: "",
      bgc_received_date: "",
      bgc_file_name: "",
      bgc_file_path: null,
      vendor: "",
      sss: "",
      sss_number: "",
      sss_remarks: "",
      sss_file_name: "",
      sss_file_path: null,
      phic_number: "",
      phic_remarks: "",
      phic_proof: "",
      phic_file_name: "",
      phic_file_path: null,
      hdmf_number: "",
      hdmf_remarks: "",
      hdmf_proof: "",
      hdmf_file_name: "",
      hdmf_file_path: null,
      tin: "",
      tin_remarks: "",
      tin_proof: "",
      tin_file_name: "",
      tin_file_path: null,
      form_1902: "",
      attachment_1902: "",
      form_1902_file_name: "",
      form_1902_file_path: null,
      health_certificate: "",
      health_certificate_file_name: "",
      health_certificate_file_path: null,
      vaccination_card: "",
      first_dose_date: "",
      second_dose_date: "",
      vaccination_card_file_name: "",
      vaccination_card_file_path: null,
      signed_bank_form: "",
      type_of_first_valid_id: "",
      type_of_second_valid_id: "",
      type_of_first_valid_id_file_name: "",
      type_of_second_valid_id_file_name: "",
      type_of_first_valid_id_file_path: null,
      type_of_second_valid_id_file_path: null,
      two_by_two: "",
      two_by_two_file_name: "",
      two_by_two_file_path: null,
      form_2316: "",
      form_2316_file_name: "",
      form_2316_file_path: null,
      nso_birth_certificate: "",
      nso_birth_certificate_file_name: "",
      nso_birth_certificate_file_path: null,
      dependents_nso_birth_certificate: "",
      dependents_nso_birth_certificate_file_name: "",
      dependents_nso_birth_certificate_file_path: null,
      marriage_certificate: "",
      marriage_certificate_file_name: "",
      marriage_certificate_file_path: null,
      cibi: "",
      cibi_search_date: "",
      ofac: "",
      sam: "",
      oig: "",
      month_milestone: "",
      week_ending: "",
      fifteenth_day_deadline: "",
      end_of_product_training: "",
      past_due: "",
      on_track: "",
      nbi_dt: "",
      job_offer_letter: "",
      interview_form_compliance: "",
      tmp_notes: "",
      tmp_ii: "",
      tmp_id: "",
      tmp_ov: "",
      tmp_status: "",
      jo_hr_contract_compliance: "",
      data_privacy_form: "",
      undertaking_non_employment_agreement: "",
      addendum_att: "",
      addendum_language_assessment: "",
      social_media_policy: "",
      contract_tmp_remarks: "",
      endorsed_by_hs: "",
      endorsed_to_compliance: "",
      return_to_hs_with_findings: "",
      last_received_from_hs_with_findings: "",
      status_201: "",
      compliance_remarks: "",
      with_findings: "",
      transmittal_to_act_hris_email_subject_sent: "",
      act_hris_remarks: "",
      // Visibility states for each section
      isSection1Visible: true,
      isSection2Visible: false,
      isSection3Visible: true,
      isNBIVisible: false,
      isSSSVisible: false,
      isPHICVisible: false,
      isHDMFVisible: false,
      isTINVisible: false,
      isBGCVisible: false,
      isDTVisible: false,
      isPEMEVisible: false,
      isBIRVisible: false,
      isNSOVisible: false,
      isVaccineVisible: false,
      isPhotoVisible: false,
      nbiFile: null,
      previewNBI: null,
      dtFile: null,
      previewDT: null,
      phicFile: null,
      previewPHIC: null,
      sssFile: null,
      previewSSS: null,
      hdmfFile: null,
      previewHDMF: null,
      tinFile: null,
      previewTIN: null,
      bgcFile: null,
      previewBGC: null,
      pemeFile: null,
      previewPEME: null,
      form1902File: null,
      previewform1902: null,
      form2316File: null,
      previewform2316: null,
      nsoFile: null,
      previewNSO: null,
      dnsoFile: null,
      previewDNSO: null,
      vpFile: null,
      previewVP: null,
      hcFile: null,
      previewHC: null,
      twoFile: null,
      previewTWO: null,
      firstFile: null,
      previewFIRST: null,
      secondFile: null,
      previewSecond: null,
      mcFile: null,
      previewMC: null, */
    };
  },
  methods: {
    handleFileChange(event) {
      const file = event.target.files[0];
      console.log(file);
      this.file = file;
    },

    async submitBulkLeads() {
      if (!this.file) {
        this.errorMessage = "Please select a file to upload.";
        return;
      }

      this.loading = true;
      this.errorMessage = "";
      this.successMessage = "";

      const formData = new FormData();
      formData.append("file", this.file);
      formData.append("lead_added_by", this.$store.state.user_id);

      try {
        const token = this.$store.state.token;
        const response = await axios.post(
          "http://127.0.0.1:8000/api/upload-leads-bulk",
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
              Authorization: `Bearer ${token}`,
            },
          }
        );
        this.successMessage = response.data.success;
        this.file = null;
      } catch (error) {
        if (error.response && error.response.data) {
          this.errorMessage =
            error.response.data.error || "Error uploading file";
        } else {
          this.errorMessage = "Error uploading file";
        }
      } finally {
        this.loading = false;
      }
    },

    addLeads() {
      this.loading = true;
      const formData = {
        lead_date: this.lead_date,
        lead_source: this.lead_source,
        lead_type: this.lead_type,
        lead_application_date: this.lead_application_date,
        lead_released_date: this.lead_released_date,
        lead_srid: this.lead_srid,
        lead_prism_status: this.lead_prism_status,
        lead_site: this.lead_site,
        lead_last_name: this.lead_last_name,
        lead_first_name: this.lead_first_name,
        lead_middle_name: this.lead_middle_name,
        lead_contact_number: this.lead_contact_number,
        lead_email_address: this.lead_email_address,
        lead_home_address: this.lead_home_address,
        lead_gen_source: this.lead_gen_source,
        lead_spec_source: this.lead_spec_source,
        lead_position: this.lead_position,
        lead_added_by: this.$store.state.user_id,
      };
      const token = this.$store.state.token;
      const headers = {
        Authorization: `Bearer ${token}`,
      };

      axios
        .post("http://127.0.0.1:8000/api/upload_leads", formData, { headers })
        .then((response) => {
          console.log(response.data);
          this.lead_date = "";
          this.lead_source = "";
          this.lead_type = "";
          this.lead_application_date = "";
          this.lead_released_date = "";
          this.lead_srid = "";
          this.lead_prism_status = "";
          this.lead_site = "";
          this.lead_last_name = "";
          this.lead_first_name = "";
          this.lead_middle_name = "";
          this.lead_contact_number = "";
          this.lead_email_address = "";
          this.lead_home_address = "";
          this.lead_gen_source = "";
          this.lead_spec_source = "";
          this.lead_position = "";
        })
        .catch((error) => {
          console.log(error.response.data);
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
};
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  border: 0;
  outline: 0;
  font-size: 100%;
  vertical-align: baseline;
  background: transparent;
}
main {
  display: flex;
  flex-direction: column;
  height: 100%;
}
@media (min-width: 576px) {
  .tab-button {
    padding: 1rem 0.75rem;
  }
}
@media (min-width: 768px) {
  .tab-button {
    padding: 1rem 0.5rem;
  }
}
.link-button {
  text-decoration: none;
}
.svg-button {
  display: flex;
  align-items: center;
  gap: 5px;
}

.svg-button svg {
  width: 1em;
  height: 1em;
  border: 1px solid #000;
}
.svg-button2 {
  display: flex;
  align-items: center;
  gap: 5px;
  padding: 8px;
  height: 20px;
  width: auto;
}
.svg-button2 svg {
  width: 1em;
  height: 1em;
}
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-content {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  max-width: 400px;
}

.drop-container {
  position: relative;
  display: flex;
  gap: 10px;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 200px;
  padding: 20px;
  border-radius: 10px;
  border: 2px dashed #fb8303;
  color: #444;
  cursor: pointer;
  transition: background 0.2s ease-in-out, border 0.2s ease-in-out;
}

.drop-container:hover,
.drop-container.drag-active {
  background: #eee;
  border-color: #111;
}

.drop-container:hover .drop-title,
.drop-container.drag-active .drop-title {
  color: #222;
}

.drop-title {
  color: #444;
  font-size: 20px;
  font-weight: bold;
  text-align: center;
  transition: color 0.2s ease-in-out;
}
</style>
