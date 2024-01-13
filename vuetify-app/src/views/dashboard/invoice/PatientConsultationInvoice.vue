
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
            title="Consultation Invoice"
            prepend-icon="mdi-36px mdi-light mdi-clipboard-text-outline"
            rel="noopener"
            color="warning"
          ></v-card>
          <v-col cols="12">
            <v-card>
              <v-container>
                <v-row align="center" justify="center">
                  <v-col cols="auto" >
                    <v-btn  prepend-icon="mdi-file-document-edit-outline" color="info" >  Edit Patinet</v-btn>
                  </v-col>
                  <v-col cols="auto" v-if="invoice_id">
                    <v-btn   prepend-icon="mdi-printer" color="warning" @click="exInvoicePrint">  Print Invoice</v-btn>
                  </v-col>
                </v-row>
              </v-container>
            </v-card>
          </v-col>
          <v-col cols="12">
            <v-row>
              <v-col cols="9">
                <v-card class="mx-auto">
                  <v-container>
                    <v-row>
                      <v-col cols="6">
                        <v-autocomplete
                          v-model="handleSubmit.doctor_id"
                          :items="special_doctor"
                          color="blue-grey-lighten-2"
                          item-value="id"
                          item-title="doctor_name"
                          label="Examination Doctor"
                          readonly
                        ></v-autocomplete>
                      </v-col>
                      <v-col cols="6">
                        <v-autocomplete
                          v-model="handleSubmit.ref_doctor_id"
                          :items="referral_doctor"
                          color="blue-grey-lighten-2"
                          item-value="id"
                          item-title="doctor_name"
                          label="Refer Doctor"
                        ></v-autocomplete>
                      </v-col>
                      <v-col cols="4">
                        <v-text-field
                          v-model="handleSubmit.subtotal"
                          color="blue-grey-lighten-2"
                          label="Subtotal"
                          readonly
                        ></v-text-field>
                      </v-col>
                      <v-col cols="3">
                        <v-text-field
                          v-model="totalDiscount"
                          color="blue-grey-lighten-2"
                          label="Discount"
                          readonly
                        ></v-text-field>
                      </v-col>
                      <v-col cols="2">
                        <v-btn @click="dialog = true" append-icon="mdi-sale" size="small" color="primary">{{discountRef}}
                        </v-btn>
                      </v-col>
                      <v-col cols="3">
                        <v-text-field
                          v-model="totalPaidAmount"
                          color="blue-grey-lighten-2"
                          label="Total Paid"
                          readonly
                        ></v-text-field>
                      </v-col>
                      <v-col cols="4">
                      </v-col>
                      <v-col cols="4">
                      </v-col>
                      <v-col cols="4">
                        <v-text-field
                          v-model="receivedAmount"
                          color="blue-grey-lighten-2"
                          label="Total Received"
                          readonly
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12">
                        <v-btn
                          size="large"
                          color="primary"
                          prepend-icon="mdi-file-document-edit-outline"
                          type="submit"
                          @click="submitForm"
                        >
                          Submit
                        </v-btn>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-card>
              </v-col>
              <v-col cols="3">
                <v-card
                  class="mx-auto"
                  :title="appointment_details.patient_id "
                  rel="noopener"
                  color="info"
                >
                  <v-card-text class="" v-if="appointment_details && appointment_details.patient_info">
                    <v-row>
                      <div class="flex-1-1-100">
                        <span class="ma-2 pa-2  mb-1">{{ appointment_details.patient_info.patient_name }}</span>
                      </div>
                      <div class="flex-1-1-100">
                        <span class="ma-2 pa-2  mb-1">{{ appointment_details.patient_info.patient_id }}</span>
                      </div>
                    </v-row>
                  </v-card-text>
                </v-card>
              </v-col>
            </v-row>
          </v-col>
        </v-col>
        <div>
          <v-row
            justify="center"
          >
            <v-dialog
              v-model="dialog"
              width="200"

            >
              <v-card>
                <v-card-title>
                  Discount %
                </v-card-title>
                <v-card-text>
                  <v-select
                    v-model="discountRef"
                    :items="discount_list"
                    item-title="discount"
                    item-value="discount"
                  >
                  </v-select>
                </v-card-text>
                <v-card-actions>
                  <v-btn color="primary" variant="text" @click="submitDiscount">
                    Submit
                  </v-btn>
                  <v-btn
                    color="warning"
                    variant="text"
                    @click="discountRefresh"
                  >
                    Refresh
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>

          </v-row>
        </div>
      </v-row>
    </v-row>
  </v-container>
</template>
<script setup>
import {computed, onMounted, ref, reactive, watch, watchEffect} from 'vue'
import axiosInstance from "@/services/axiosService";
import {useNotification} from "@/store/notification";
import {useRoute} from "vue-router";
import {ElMessageBox} from 'element-plus'
import {useAuth} from "@/store/auth";
import {localUrl} from "@/services/globalUrlConfig";

const breadcrumbs = computed(() => [
  {
    title: 'Home',
    disabled: false,
    href: '/',
  },
  {
    title: 'Patients Details',
    disabled: false,
    href: '#',
  },
]);

const notify = useNotification();
const appointment_details = ref('');
const patientId = ref('');
const AppointmentId = ref('');
const special_doctor = ref([]);
const referral_doctor = ref([]);
const discount_list = ref([]);
const dialog = ref(false);
const discountRef = ref('');
const invoice_id = ref('');
const userData = useAuth();
const user_id = userData.user.data.id;
const handleSubmit = ref({
  doctor_id: '',
  ref_doctor_id: '',
  subtotal: '',
  total_discount: '',
  total_paid_amount: '',
  received_amount: '',
  due_amount: '',

});



onMounted(async () => {
  try {
    patientId.value = useRoute().params.patientId;
    AppointmentId.value = useRoute().params.id;
    await fetchPatientAppointmentDetails();
    await  fetchDoctorData();
    await  fetchDiscountListData();
    handleSubmit.value.doctor_id = appointment_details.value.doctor_info.id;
    handleSubmit.value.subtotal = appointment_details.value.doctor_info.doctor_fees;

  } catch (error) {
    console.error('Error fetching patient appointment details:', error);
  }
});

const fetchPatientAppointmentDetails = async () => {
  try {
    const response = await axiosInstance.get(`/admin/appointment/appointment-data/${AppointmentId.value}`); // get patient appointment details
    appointment_details.value = response.data.appointment_details;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

const fetchDoctorData = async () => {
  try {
    const response = await axiosInstance.get(`/admin/invoice/doctor-data`); // get doctor details
    special_doctor.value = response.data.special_doctor;
    referral_doctor.value = response.data.referral_doctor;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

const fetchDiscountListData = async () => { //discount list
  try {
    const response = await axiosInstance.get(`/admin/invoice/discount-list`); // get doctor details
    discount_list.value = response.data.discount_list;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

const totalDiscount = ref(0);
const totalPaidAmount = ref(0);
const receivedAmount = ref(0);




const calculateDiscount = () => {
  const discountPercentage = parseFloat(discountRef.value) || 0;
  const discountedAmount = (discountPercentage / 100) * parseFloat(handleSubmit.value.subtotal || 0);

  totalDiscount.value = discountedAmount.toFixed(2);
  totalPaidAmount.value = (parseFloat(handleSubmit.value.subtotal || 0) - parseFloat(discountedAmount || 0)).toFixed(2);

  // Ensure received amount is not greater than paid amount
  receivedAmount.value = Math.min(parseFloat(totalPaidAmount.value));

};
watchEffect(() => {
  // Watch for changes in discountRef and recalculate values
  calculateDiscount();
});

const submitDiscount = () => {
  calculateDiscount();
  dialog.value = false;
};
const discountRefresh = () => {
  discountRef.value=0;
  dialog.value = false;
};
const exInvoicePrint = () => {
  window.open(localUrl.value + '/print/ex-invoice-print/'+invoice_id.value, '_blank');
};


const submitForm = async () => {
  try {
    // Prepare the data to be sent to the Laravel API
    const formData = {
      doctor_id: handleSubmit.value.doctor_id,
      ref_doctor_id: handleSubmit.value.ref_doctor_id,
      subtotal: handleSubmit.value.subtotal,
      total_discount: totalDiscount.value,
      total_paid_amount: totalPaidAmount.value,
      received_amount: receivedAmount.value,
      discount_selected: discountRef.value,
      user_id: user_id,
      patient_id: patientId.value,

      // Add other form fields as needed
    };

    console.log('form data log',formData)
    console.log('form data log',formData)
    // Make an HTTP POST request to the Laravel API endpoint

    const confirmResult = await ElMessageBox.confirm(
      'Do you want to submit the form?',
      'Confirmation',
      {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        type: 'warning',
        center: true,
      }
    );

    // If the user clicks "OK," proceed with form submission
    if (confirmResult === 'confirm') {
      const response = await axiosInstance.post('/admin/invoice/examination-invoice', formData);
      invoice_id.value = response.data.invoice_id;
      console.log('API Response:', response.data);
      notify.Success(response.data.message);
    }

  } catch (error) {
    notify.Error(error.response.data.errors);
  }
};
</script>
