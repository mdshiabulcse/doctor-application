<template>
  <v-container>
    <v-row no-gutters>
      <v-col cols="12">
        <v-sheet>
          <v-breadcrumbs :items="breadcrumbs">
            <template v-slot:prepend>
              <v-icon size="small" icon="$vuetify"></v-icon>
            </template>
          </v-breadcrumbs>
        </v-sheet>
      </v-col>
      <v-row>
        <v-col cols="12" class="">
          <v-card
            class="mx-auto my-2"
            title="Invoice Data"
            prepend-icon="mdi-36px mdi-light mdi-clipboard-text-outline"
            rel="noopener"
            color="warning"

          ></v-card>
          <v-col cols="12">
            <v-card>
              <v-container>
                <v-row >
                  <v-col cols="3">
                    <v-autocomplete
                      v-model="handleSubmit.doctor_id"
                      :items="special_doctor"
                      color="blue-grey-lighten-2"
                      item-value="id"
                      item-title="doctor_name"
                      label="Doctor"
                      @change="onDoctorChange"
                    ></v-autocomplete>
                  </v-col>
                  <v-col cols="3">
                    <v-text-field
                      v-model="handleSubmit.create_date"
                      type="date"
                      label="Date"
                      hint="MM/DD/YYYY format"
                      prepend-icon="mdi-calendar"
                      required
                      @change="onDateChange"
                    >
                    </v-text-field>
                  </v-col>
                  <v-col cols="2">
                    <v-btn prepend-icon="mdi-printer" color="primary"> Print</v-btn>
                  </v-col>
                </v-row>
              </v-container>
            </v-card>
          </v-col>
          <v-col cols="12">
            <v-sheet class="d-flex align-end flex-column ">

            </v-sheet>
            <v-data-table
              :headers="headers"
              :items="invoice_data"
              :search="search"
              class="elevation-1"
              item-value="id"
              :loading="loading"
            >
              <template v-slot:top>
                <v-text-field
                  v-model="search"
                  label="Search"
                  class="pa-0"
                ></v-text-field>
              </template>
              <template v-if="handleSubmit.appointment_date === new Date().toISOString().substr(0, 10)" v-slot:item.actions="{ item }">
                <v-btn class="me-2" icon="mdi-clippy" title="Consultation Invoice" color="warning"  @click="appointmentInvoice(item)">
                </v-btn>
                <v-btn class="me-2" icon="mdi-file-document-edit-outline" title="New Prescription" color="primary"  @click="appointmentInvoice(item)">
                </v-btn>
              </template>
            </v-data-table>
          </v-col>
        </v-col>
      </v-row>
    </v-row>
  </v-container>
</template>
<script setup>
import { onMounted, ref, watchEffect } from 'vue'
import axiosInstance from "@/services/axiosService";
import { useRouter } from "vue-router";

const router = useRouter();
const special_doctor = ref([]);
const breadcrumbs = [
  { title: 'Home', disabled: false, href: '/' },
  { title: 'Invoice List', disabled: false, href: '#' },
];
const headers = [
  { id: 'id', title: 'INV ID', align: 'end', key: 'invoice_id' },
  { id: 'id', title: 'PID', align: 'start', key: 'patient_id' },
  { id: 'id', title: 'Name', align: 'end', key: 'patient_info.patient_name' },
  { id: 'id', title: 'Doctor', align: 'end', key: 'doctor_info.doctor_name' },
  { id: 'id', title: 'Total', align: 'end', key: 'invoice_total_amount' },
  { id: 'id', title: 'Paid', align: 'end', key: 'paid_amount' },
  { id: 'id', title: 'Discount', align: 'end', key: 'total_discount_amount' },
  { id: 'id', title: 'Received', align: 'end', key: 'received_amount' },
  { id: 'id', title: 'Due', align: 'end', key: 'due_amount' },
  { id: 'id', title: 'Status', align: 'end', key: 'status' },
  { id: 'id', title: 'Actions', key: 'actions', sortable: false },
];
const search = ref('');
const invoice_data = ref([]);
const loading = ref(true);
const handleSubmit = ref({
  doctor_id: '',
  create_date: new Date().toISOString().substr(0, 10),
});

onMounted(() => {
  PatientInvoiceData();
  fetchDoctorData();
});

const fetchDoctorData = async () => {
  try {
    const response = await axiosInstance.get(`/admin/invoice/doctor-data`);
    special_doctor.value = response.data.special_doctor;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

const onDoctorChange = async () => {
  try {
    await fetchDoctorData();
    if (handleSubmit.value.doctor_id && handleSubmit.value.create_date) {
      await PatientInvoiceData();
    }
  } catch (error) {
    console.error('Error in onDoctorChange:', error);
  }
};

const onDateChange = async () => {
  try {
    if (handleSubmit.value.doctor_id || handleSubmit.value.create_date) {
      await PatientInvoiceData();
    }
  } catch (error) {
    console.error('Error in onDateChange:', error);
  }
};

const PatientInvoiceData = async () => {
  try {
    loading.value=true
    let apiUrl = `/admin/invoice/invoice?create_date=${handleSubmit.value.create_date}`;

    if (handleSubmit.value.doctor_id) {
      loading.value=true
      apiUrl += `&doctor_id=${handleSubmit.value.doctor_id}`;

    }

    const response = await axiosInstance.get(apiUrl);
    invoice_data.value = response.data.invoice_data;
    loading.value = false;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

const appointmentInvoice = (item) => {
  console.log('item', item.selectable);
  router.push({ path: `/patient-consultation-invoice/${item.selectable.id}/${item.selectable.patient_id}` });
};

watchEffect(() => {
  PatientInvoiceData();
});
</script>
<style scoped>
/* Add your custom styles for smaller font size or other styling here */
/* For example: */
.v-data-table td {
  font-size: 12px; /* Adjust the font size as needed */
}

/* Add more custom styles if required */
</style>
