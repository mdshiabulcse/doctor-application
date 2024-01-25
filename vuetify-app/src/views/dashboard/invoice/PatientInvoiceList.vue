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
                <v-row>
                  <v-col cols="3">
                    <v-autocomplete
                      v-model="handleSubmit.user_id"
                      :items="user_info"
                      color="blue-grey-lighten-2"
                      item-value="id"
                      item-title="name"
                      label="User"
                      @change="onUserChange"
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
                    <v-btn prepend-icon="mdi-printer" color="primary" @click="PrintInvoiceList"> Print</v-btn>
                  </v-col>
                </v-row>
              </v-container>
            </v-card>
          </v-col>
          <v-col cols="12">
            <v-card
              class="mx-auto my-2"
              title="Consultation"
              rel="noopener"
              color="primary"
            ></v-card>
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
                v-for="item in consultation_invoice_data"
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
                  <v-btn class="me-2" icon="mdi-printer" title="Consultation Invoice" color="warning"
                         @click="PrintInvoice(item)">
                  </v-btn>
                </td>
              </tr>
              <tr v-if="consultation_sum_data">
                <td></td>
                <td></td>
                <td></td>
                <td class="font-weight-bold">Consultation Total:</td>
                <td class="font-weight-bold">{{ consultation_sum_data.total_amount }}</td>
                <td class="font-weight-bold">{{ consultation_sum_data.total_paid_amount }}</td>
                <td class="font-weight-bold">{{ consultation_sum_data.discount_amount_total }}</td>
                <td class="font-weight-bold">{{ consultation_sum_data.total_received_amount }}</td>
                <td class="font-weight-bold">{{ consultation_sum_data.total_due_amount }}</td>
              </tr>
              </tbody>
            </v-table>

          </v-col>
          <v-col cols="12">
            <v-card
              class="mx-auto my-2"
              title="Pathology"
              rel="noopener"
              color="primary"
            ></v-card>
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
                v-for="item in pathology_invoice_data"
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
                  <v-btn class="me-2" icon="mdi-printer" title="Consultation Invoice" color="warning"
                         @click="PrintInvoice(item)">
                  </v-btn>
                </td>
              </tr>
              <tr v-if="pathology_sum_data">
                <td></td>
                <td></td>
                <td></td>
                <td class="font-weight-bold">Pathology Total:</td>
                <td class="font-weight-bold">{{ pathology_sum_data.total_amount }}</td>
                <td class="font-weight-bold">{{ pathology_sum_data.total_paid_amount }}</td>
                <td class="font-weight-bold">{{ pathology_sum_data.discount_amount_total }}</td>
                <td class="font-weight-bold">{{ pathology_sum_data.total_received_amount }}</td>
                <td class="font-weight-bold">{{ pathology_sum_data.total_due_amount }}</td>
              </tr>
              </tbody>
            </v-table>

          </v-col>
          <v-col cols="6">
            <v-card
              class="mx-auto my-2"
              title="Invoice Summary"
              rel="noopener"
              color="primary"
            ></v-card>
            <v-table>
              <tbody v-if="summary_total">
              <tr>
                <td cols="6">Total Amount:</td>
                <td cols="6">{{ formatAmount(summary_total.total_amount) }}</td>
              </tr>
              <tr>
                <td>Total Discount Amount:</td>
                <td>{{ formatAmount(summary_total.discount_amount_total) }}</td>
              </tr>
              <tr>
                <td>Total Paid Amount:</td>
                <td>{{ formatAmount(summary_total.total_paid_amount) }}</td>
              </tr>
              <tr>
                <td>Total Due Amount:</td>
                <td>{{ formatAmount(summary_total.total_due_amount) }}</td>
              </tr>
              <tr>
                <td>Total Received Amount:</td>
                <td>{{ formatAmount(summary_total.total_received_amount) }}</td>
              </tr>
              <tr>
                <td class="text-warning">Total Balance:</td>
                <td>
                  <v-chip color="warning">{{ formatAmount(summary_total.total_received_amount) }}</v-chip>
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
import {onMounted, ref, watchEffect} from 'vue'
import axiosInstance from "@/services/axiosService";
import {useRouter} from "vue-router";
import {localUrl} from "@/services/globalUrlConfig";
import moment from 'moment-timezone';

const router = useRouter();
const user_info = ref([]);
const breadcrumbs = [
  {title: 'Home', disabled: false, href: '/'},
  {title: 'Invoice List', disabled: false, href: '#'},
];
const formatAmount = (amount) => parseFloat(amount).toFixed(2);
const consultation_invoice_data = ref([]);
const consultation_sum_data = ref();
const pathology_invoice_data = ref([]);
const pathology_sum_data = ref();
const summary_total = ref();
const loading = ref(true);
const handleSubmit = ref({
  user_id: '',
  // create_date: new Date().toISOString().substr(0, 10),
  create_date: moment().format('YYYY-MM-DD'),
});

onMounted(() => {
  PatientInvoiceData();
  fetchUserData();
});

const fetchUserData = async () => {
  try {
    const response = await axiosInstance.get(`/admin/invoice/user-data`);
    user_info.value = response.data.user_info;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

const onUserChange = async () => {
  try {
    await fetchUserData();
    if (handleSubmit.value.user_id && handleSubmit.value.create_date) {
      await PatientInvoiceData();
    }
  } catch (error) {
    console.error('Error in onUserChange:', error);
  }
};

const onDateChange = async () => {
  try {
    if (handleSubmit.value.user_id || handleSubmit.value.create_date) {
      await PatientInvoiceData();
    }
  } catch (error) {
    console.error('Error in onDateChange:', error);
  }
};

const PatientInvoiceData = async () => {
  try {
    loading.value = true
    let apiUrl = `/admin/invoice/invoice?create_date=${handleSubmit.value.create_date}`;

    if (handleSubmit.value.user_id) {
      loading.value = true
      apiUrl += `&user_id=${handleSubmit.value.user_id}`;

    }

    const response = await axiosInstance.get(apiUrl);
    consultation_invoice_data.value = response.data.consultation_invoice_data;
    consultation_sum_data.value = response.data.consultation_sum_data;
    pathology_invoice_data.value = response.data.pathology_invoice_data;
    pathology_sum_data.value = response.data.pathology_sum_data;
    summary_total.value = response.data.summary_total;
    console.log('pathology', summary_total.value)
    loading.value = false;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

const PrintInvoice = (item) => {
  window.open(localUrl.value + '/print/invoice-print/' + item.invoice_id, '_blank');
};
const PrintInvoiceList = () => {
  window.open(localUrl.value + `/print/invoice-print-list?create_date=${handleSubmit.value.create_date}&user_id=${handleSubmit.value.user_id}`, '_blank');
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
