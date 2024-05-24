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
       <v-col cols="12" md="7">
         <v-card
           class="mx-auto my-2"
           title="Operation Registration"
           prepend-icon="mdi-36px mdi-light mdi-clipboard-text-outline"
           rel="noopener"
           color="info"
         ></v-card>
         <v-card>
           <v-card-text>
             <form @submit.prevent="submit">
               <v-row>
                 <v-col cols="12">
                   <v-text-field
                     v-model="submitForm.patient_id"
                     minlength="2"
                     label="Patient ID"
                     :rules="submitPatientId"
                   ></v-text-field>
                 </v-col>

                 <v-col cols="6">
                   <v-autocomplete
                     v-model="submitForm.doctor_id"
                     :items="special_doctor"
                     color="blue-grey-lighten-2"
                     item-value="id"
                     item-title="doctor_name"
                     label="Doctor"
                     :rules="otDoctor"
                   ></v-autocomplete>
                 </v-col>
                 <v-col cols="6">
                   <v-autocomplete
                     v-model="submitForm.refer_doctor_id"
                     :items="all_doctor_list"
                     color="blue-grey-lighten-2"
                     item-value="id"
                     item-title="doctor_name"
                     label="Referral Doctor"
                   ></v-autocomplete>
                 </v-col>
                 <v-col cols="6">
                   <v-autocomplete
                     v-model="submitForm.operation_id"
                     :items="operation_list"
                     color="blue-grey-lighten-2"
                     item-value="id"
                     item-title="operation_name"
                     label="Operation Name"
                     :rules="otID"
                   ></v-autocomplete>
                 </v-col>
                 <v-col cols="6">
                   <v-text-field
                     v-model="submitForm.operation_subhead"
                     label="Operation Subhead"
                   ></v-text-field>
                 </v-col>
                 <v-col cols="12" sm="6">
                   <v-text-field
                     label="Operation Date"
                     v-model="submitForm.operation_date"
                     clearable
                     type="date"
                     :min="new Date().toISOString().substr(0, 10)"
                     :rules="otDate"
                   >
                   </v-text-field>
                 </v-col>
                 <v-col cols="12" sm="6">
                   <v-text-field
                     label="Operation Time"
                     v-model="submitForm.operation_time"
                     clearable
                     type="time"
                   >
                   </v-text-field>
                 </v-col>
                 <v-col cols="12">
                   <v-text-field
                     label="Operation Amount"
                     v-model="submitForm.ot_amount"
                     clearable
                     :rules="otAmount"
                   >
                   </v-text-field>
                 </v-col>
                 <v-col cols="12">
                   <v-textarea
                     label="Operation Description"
                     v-model="submitForm.ot_description"
                     color="blue-grey-lighten-2"
                   >
                   </v-textarea>
                 </v-col>
                 <v-col cols="12">
                   <v-btn
                     prepend-icon="mdi-check-circle"
                     class="me-4"
                     type="submit"
                     color="primary"
                   >
                     submit
                   </v-btn>
                   <v-btn  prepend-icon="mdi-trash-can-outline" @click="handleReset">
                     Clear
                   </v-btn>
                 </v-col>
               </v-row>
             </form>
           </v-card-text>
         </v-card>
       </v-col >
       <v-col :class="patient_details.patient_id ? '':'d-none'" cols="12" md="5">
         <v-card
           color="warning"
           class="mx-auto my-2"
         >
           <v-card-item>
             <div>

               <div class="text-h6 mb-1">
                 {{patient_details.patient_id}}
               </div>
               <div class="text-overline mb-1">
                 {{patient_details.patient_name}}
               </div>
               <div class="text-overline mb-1">
                 {{patient_details.patient_phone}}
               </div>
               <div class="text-overline mb-1">
                 {{patient_details.patient_dob}}
               </div>
               <div class="text-overline mb-1">
                 {{patient_details.gender}}
               </div>
             </div>
           </v-card-item>
           <v-card-item>
               <div class="text-h6 mb-1">
                 <v-btn
                   prepend-icon="mdi mdi-account-edit-outline"
                   color="#5865f2"
                   size="small"
                 >
                   Edit
                 </v-btn>
               </div>
           </v-card-item>

         </v-card>
       </v-col>
     </v-row>

    </v-row>
  </v-container>
</template>
<script setup>
import {computed, onMounted, ref,watch} from 'vue'
import axiosInstance from "@/services/axiosService";
import {useNotification} from "@/store/notification";
import {useAuth} from "@/store/auth";
import {useRoute, useRouter} from "vue-router";

const notify = useNotification();
const userData = useAuth();
const user_id = userData.user.data.id;
const special_doctor=ref([]);
const all_doctor_list=ref([]);
const operation_list=ref([]);
const patient_details = ref([]);
const patientId = ref('');
const router = useRouter();

const breadcrumbs = computed(() => [
  {
    title: 'Home',
    disabled: false,
    href: '/',
  },
  {
    title: 'OT Registration',
    disabled: false,
    href: '#',
  },
]);

const submitForm=ref({
  registration_number:'',
  patient_id:'',
  doctor_id:'',
  refer_doctor_id:'',
  operation_id:'',
  operation_subhead:'',
  operation_date:'',
  operation_time:'',
  ot_amount:'',
  ot_description:'',
  user_id:'',

});




onMounted(() => {
  patientId.value = useRoute().params.patientId;
  fetchDoctorData();
  allDoctorData();
  operationList();
});

const fetchDoctorData = async () => {
  try {
    const response = await axiosInstance.get(`/admin/default/special-doctor-list`);
    special_doctor.value = response.data.special_doctor_list;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};
const allDoctorData = async () => {
  try {
    const response = await axiosInstance.get(`/admin/default/all-doctor-list`);
    all_doctor_list.value = response.data.all_doctor_list;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

const operationList = async () => {
  try {
    const response = await axiosInstance.get(`/admin/default/operation-list`);
    operation_list.value = response.data.operation_list;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};



const submitPatientId = [
  (v) => !!v || 'Patient ID is required',
  (v) => (v && v.length >= 3) || 'Patient ID must be at least 3 characters',
];

const otDoctor = [
  (v) => !!v || 'Operation Doctor is required',
];
const otID = [
  (v) => !!v || 'Operation Name is required',
];
const otDate = [
  (v) => !!v || 'Operation Date is required',
];
const otAmount = [
  (v) => !!v || 'Operation Amount is required',  // Check if the value is present
  (v) => (v > 0) || 'Amount must be greater than 0',  // Check if the value is positive
  (v) => (v >= 10) || 'Minimum amount is 10',
];




watch(() => submitForm.value.patient_id, async (newPatientId) => {
  if (newPatientId) {
    try {
      const response = await axiosInstance.get(`/admin/patients/patients/${newPatientId}`);
      if (response.data && response.data.patient_details) {
        patient_details.value = response.data.patient_details;
        submitForm.value.patient_id = response.data.patient_details.patient_id;
      } else {
        notify.Error('No patient details found for the given ID.');
      }
    } catch (error) {
      console.error('Error fetching patient details:', error);
    }
  }
});



const submit = async () => {
  const isFormValid = await validateForm();

  if (isFormValid) {
    try {
      const response = await axiosInstance.post(`/admin/ot/operation?user_id=${user_id}`, submitForm.value);
      if (response.data.message) {
        notify.Success(response.data.message);
        console.log('Form submitted successfully',response.data.registration_number);
        // router.push({ path: `/patient-details/${response.data.patient_id}` });
      } else {
        notify.Error(response.data.errors);
        console.error('Submission error:', response.data.errors);
      }
    } catch (error) {
      notify.Error(error.response.data.errors);
      console.error('HTTP request error:', error.response.data.errors);
    }
  } else {
    console.error('Form validation failed. Please check the fields.');
  }
};

// ===============Form Validation Code Start Here===============  //

const validateForm = async () => {
  const results = await Promise.all([
    ...submitPatientId.map((rule) => rule(submitForm.value.patient_id)),
    ...otDoctor.map((rule) => rule(submitForm.value.doctor_id)),
    ...otID.map((rule) => rule(submitForm.value.operation_id)),
    ...otDate.map((rule) => rule(submitForm.value.operation_date)),
    ...otAmount.map((rule) => rule(submitForm.value.ot_amount)),
  ]);

  // Log the results for debugging
  console.log('Validation results:', results);

  // Check if all validation results are truthy (indicating valid)
  return results.every((result) => result === true);
};


// ===============Form Validation Code End Here===============  //

const updateSubmitForm = () => {
  watch(patient_details, (newValue) => {
    const patientDetails = newValue;

    if (patientDetails) {

      submitForm.value = {
        patient_id: patientDetails.patient_id,
      };
    }
  });
};

updateSubmitForm();

const handleReset = () => {
  // Reset the form fields
  submitForm.value = {
    patient_id: '',
    doctor_id: '',
    refer_doctor_id: '',
    operation_id: '',
    operation_subhead: '',
    operation_date: '',
    operation_time: '',
    ot_amount: '',
    ot_description: '',
  };
};
</script>
