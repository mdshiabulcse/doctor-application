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
            title="Examination Invoice"
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

                      <v-col cols="11">
                        <v-row v-for="(examination, index) in examinations" :key="index">
                          <v-col cols="7">
                            <v-autocomplete
                              v-model="examination.examination_id"
                              :items="getAvailableExaminations(index)"
                              color="blue-grey-lighten-2"
                              item-value="id"
                              item-title="ex_name"
                              label="Examination"
                              @change="updateExUnitPrice(index)"
                              :rules="[validateUniqueExaminationId(index)]"
                            ></v-autocomplete>
                          </v-col>
                          <v-col cols="4">
                            <v-text-field
                              v-model="examination.ex_unit_price"
                              label="Unit Price"
                              :readonly="!isFlexiblePrice(index)"
                              :rules="[validateNumber, validateNonNegative]"
                            ></v-text-field>
                          </v-col>
                          <v-col v-if="index !== 0" cols="1">
                            <v-btn @click="removeExamination(index)" size="x-small" icon="mdi-minus"
                                   color="error"></v-btn>
                          </v-col>
                        </v-row>
                      </v-col>
                      <v-col cols="1">
                        <v-btn @click="addExamination" icon="mdi-plus" size="x-small" color="primary"></v-btn>
                      </v-col>
                      <v-col cols="4">
                        <v-text-field
                          v-model="getTotalUnitPrice"
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
                        <v-btn @click="dialog = true" append-icon="mdi-sale" size="small" color="primary">{{
                            discountRef
                          }}
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
                        <v-text-field
                          v-model="dueAmount"
                          color="blue-grey-lighten-2"
                          label="Due Amount"
                          readonly
                        ></v-text-field>
                      </v-col>
                      <v-col cols="4">
                        <v-text-field
                          type="number"
                          v-model="receivedAmount"
                          :max="totalPaidAmount"
                          color="blue-grey-lighten-2"
                          label="Total Received"
                          :rules="[validateReceivedAmount, validateNonNegative]"
                          hint="Received amount cannot be greater than total paid amount"
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
                  :title="patient_details.patient_id "
                  rel="noopener"
                  color="info"
                >
                  <v-card-text class="">
                    <v-row>
                      <div class="flex-1-1-100">
                        <span class="ma-2 pa-2  mb-1"> {{ patient_details.patient_name }}</span>
                      </div>
                      <div class="flex-1-1-100">
                        <span class="ma-2 pa-2  mb-1"> {{ patient_details.patient_phone }}</span>
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
                    color="primary"
                    variant="text"
                    @click="dialog = false"
                  >
                    Close
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
import {useRoute, useRouter} from "vue-router";
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
const patient_details = ref([]);
const patientId = ref('');
const router = useRouter();
const special_doctor = ref([]);
const referral_doctor = ref([]);
const examination_list = ref([]);
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
const examinations = reactive([]);
const selectedExaminationIds = ref(new Set());


onMounted(() => {
  patientId.value = useRoute().params.patientId;
  fetchPatientDetails();
  fetchDoctorData();
  fetchExaminationListData();
  fetchDiscountListData();
  addExamination();
});

const fetchPatientDetails = async () => {
  try {
    const response = await axiosInstance.get(`/admin/patients/patients/${patientId.value}`); // get patient details
    patient_details.value = response.data.patient_details;
    fetchExaminationListData();
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
const fetchExaminationListData = async () => { //examination data list
  try {
    const response = await axiosInstance.get(`/admin/invoice/examination-list`); // get doctor details
    examination_list.value = response.data.examination_list;
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

const exInvoicePrint = () => {
  window.open(localUrl.value + '/print/invoice-print/'+invoice_id.value, '_blank');
};

const addExamination = () => {
  examinations.push({
    examination_id: '',
    ex_unit_price: '',
  });
};

const removeExamination = (index) => {
  const removedId = examinations[index].examination_id;
  examinations.splice(index, 1);
  selectedExaminationIds.value.delete(removedId);
};

const isFlexiblePrice = (index) => {
  const selectedExamination = examination_list.value.find((item) => item.id === examinations[index].examination_id);
  return selectedExamination && selectedExamination.price_type === 'flexible';
};

const validateNumber = (value) => /^[+]?\d+([.]\d+)?$/.test(value) || 'Enter a valid number';

const validateNonNegative = (value) => (parseFloat(value) >= 0) || 'Value must be non-negative';

const getAvailableExaminations = (currentIndex) => {
  return examination_list.value.filter((item) => {
    return !selectedExaminationIds.value.has(item.id) || item.id === examinations[currentIndex].examination_id;
  });
};

const validateUniqueExaminationId = (currentIndex) => (value) => {
  const isDuplicateInOtherFields = examinations
    .filter((_, index) => index !== currentIndex) // Exclude the current field
    .some((exam) => exam.examination_id === value);

  return !isDuplicateInOtherFields || 'Examination already selected in another field';
};

const updateExUnitPrice = (index) => {
  const selectedExamination = examination_list.value.find((item) => item.id === examinations[index].examination_id);
  if (selectedExamination) {
    examinations[index].ex_unit_price = isFlexiblePrice(index) ? '' : selectedExamination.price;
  } else {
    examinations[index].ex_unit_price = '';
  }

  // Update selected examination ids
  selectedExaminationIds.value.clear();
  examinations.forEach((exam) => {
    selectedExaminationIds.value.add(exam.examination_id);
  });
};

watch(() => examinations.map((exam) => exam.examination_id), (newValues, oldValues) => {
  newValues.forEach((newValue, index) => {
    if (newValue !== oldValues[index]) {
      updateExUnitPrice(index);
    }
  });
});

const getTotalUnitPrice = computed(() => {
  const sum = examinations.reduce((sum, exam) => {
    return sum + parseFloat(exam.ex_unit_price || 0);
  }, 0);
  handleSubmit.subtotal = sum.toFixed(2); // Update subtotal in handleSubmit
  return sum.toFixed(2);
});


const totalDiscount = ref(0);
const totalPaidAmount = ref(0);
const receivedAmount = ref(0);
const dueAmount = ref(0);

const updateValues = () => {
  const discountPercentage = parseFloat(discountRef.value) || 0;
  const discountedAmount = (discountPercentage / 100) * parseFloat(getTotalUnitPrice.value || 0);

  totalDiscount.value = discountedAmount.toFixed(2);
  totalPaidAmount.value = (parseFloat(getTotalUnitPrice.value || 0) - parseFloat(discountedAmount || 0)).toFixed(2);

  // Ensure received amount is not greater than paid amount
  receivedAmount.value = Math.min(parseFloat(totalPaidAmount.value), parseFloat(receivedAmount.value));

  // Calculate due amount based on the difference between total amount and received amount
  dueAmount.value = (parseFloat(totalPaidAmount.value || 0) - parseFloat(receivedAmount.value || 0)).toFixed(2);

};


watchEffect(
  updateValues
);
const validateReceivedAmount = (value) => {
  return parseFloat(value) <= parseFloat(totalPaidAmount.value) || "Received amount cannot be greater than total paid amount";
};
const submitDiscount = () => {
  dialog.value = false;
};


const submitForm = async () => {
  try {
    // Prepare the data to be sent to the Laravel API
    const formData = {
      doctor_id: handleSubmit.value.doctor_id,
      ref_doctor_id: handleSubmit.value.ref_doctor_id,
      invoice_total_amount: getTotalUnitPrice.value,
      subtotal: handleSubmit.subtotal,
      total_discount: totalDiscount.value,
      total_paid_amount: totalPaidAmount.value,
      received_amount: receivedAmount.value,
      due_amount: dueAmount.value,
      discount_selected: discountRef.value,
      user_id: user_id,
      patient_id: patientId.value,
      examinations: examinations.map(exam => ({
        examination_id: exam.examination_id,
        ex_unit_price: exam.ex_unit_price,
      })),
      // Add other form fields as needed
    };

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
