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
            <v-table>
              <thead>
              <tr>
                <th class="text-left">
                  INV
                </th>
                <th class="text-left">
                  PID
                </th>
                <th class="text-left">
                  Name
                </th>
                <th class="text-left">
                  Doctor
                </th>
                <th class="text-left">
                  Total
                </th>
                <th class="text-left">
                  Paid
                </th>
                <th class="text-left">
                  Discount
                </th>
                <th class="text-left">
                  Received
                </th>
                <th class="text-left">
                  Due
                </th>
                <th class="text-left">
                  Status
                </th>
                <th class="text-left">
                  Actions
                </th>
              </tr>
              </thead>
              <tbody>
              <tr
                v-for="item in invoice_data"
                :key="item.name"
              >
                <td>{{ item.invoice_id }}</td>
                <td>{{ item.patient_id }}</td>
                <td>{{ item.patient_info.patient_name }}</td>
                <td>{{ item.doctor_info.doctor_name }}</td>
                <td>{{ item.invoice_total_amount }}</td>
                <td>{{ item.paid_amount }}</td>
                <td>{{ item.total_discount_amount }}</td>
                <td>{{ item.received_amount }}</td>
                <td>{{ item.due_amount }}</td>
                <td>{{ item.status }}</td>
                <td>
                  <v-btn class="me-2" icon="mdi-printer" title="Consultation Invoice" color="warning"  @click="PrintInvoice(item)">
                  </v-btn>
                </td>
              </tr>
              </tbody>
            </v-table>

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
import {localUrl} from "@/services/globalUrlConfig";

const router = useRouter();
const special_doctor = ref([]);
const breadcrumbs = [
  { title: 'Home', disabled: false, href: '/' },
  { title: 'Invoice List', disabled: false, href: '#' },
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

const PrintInvoice = (item) => {
  window.open(localUrl.value + '/print/consultation-invoice-print/'+item.invoice_id, '_blank');
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
