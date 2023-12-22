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
                  <v-col cols="auto">
                    <v-btn prepend-icon="mdi-pencil-plus-outline" color="primary">edit</v-btn>
                  </v-col>
                  <v-col cols="auto">
                    <v-btn prepend-icon="mdi-medical-bag" color="warning" @click="patientExamination">Examination</v-btn>
                  </v-col>
                </v-row>
              </v-container>
            </v-card>
          </v-col>
         <v-col cols="12">
               <v-row>
                 <v-col cols="8">
                   <v-card class="mx-auto">
                     <v-container>
                       <form @submit.prevent="submit">
                         <v-row>
                           <v-col cols="6">
                             <v-autocomplete
                               v-model="name.value.value"
                               :counter="10"
                               :items="special_doctor"
                               color="blue-grey-lighten-2"
                               item-value="id"
                               item-title="doctor_name"
                               label="Examination Doctor"
                             ></v-autocomplete>
                           </v-col>
                           <v-col cols="6">
                             <v-autocomplete
                               v-model="phone.value.value"
                               :counter="7"
                               :items="referral_doctor"
                               color="blue-grey-lighten-2"
                               item-value="id"
                               item-title="doctor_name"
                               label="Refer Doctor"
                             ></v-autocomplete>
                           </v-col>

                          <v-col cols="7">
                            <v-autocomplete
                              v-model="email.value.value"
                              :items="examination_list"
                              color="blue-grey-lighten-2"
                              item-value="id"
                              item-title="ex_name"
                              label="Examination"
                            ></v-autocomplete>
                          </v-col>
                           <v-col cols="4">
                             <v-text-field
                               v-model="select.value.value"
                               label="Unit Price"
                             ></v-text-field>
                           </v-col>
                           <v-col cols="1">
                             <v-btn icon="mdi-plus" size="small" color="primary"></v-btn>
                           </v-col>
                           <v-col cols="4">
                             <v-text-field
                               color="blue-grey-lighten-2"
                               label="Subtotal"
                             ></v-text-field>
                           </v-col>
                           <v-col cols="3">
                             <v-text-field
                               color="blue-grey-lighten-2"
                               label="Discount"
                             ></v-text-field>
                           </v-col>
                           <v-col cols="1">
                             <v-btn icon="mdi-plus" size="small" color="primary"></v-btn>
                           </v-col>
                           <v-col cols="4">
                             <v-text-field
                               color="blue-grey-lighten-2"
                               label="Total Price"
                             ></v-text-field>
                           </v-col>

                           <v-col cols="12">
                             <v-btn
                               class="me-4"
                               type="submit"
                             >
                               submit
                             </v-btn>
                             <v-btn @click="handleReset">
                               clear
                             </v-btn>
                           </v-col>
                         </v-row>
                       </form>
                     </v-container>
                   </v-card>
                 </v-col>
                 <v-col cols="4">
                   <v-card
                     class="mx-auto"
                     :title="patient_details.patient_id "
                     prepend-icon="mdi-36px mdi-light mdi-clipboard-text-outline"
                     rel="noopener"
                     color="info"
                   >
                       <v-card-text class="">
                         <v-row >
                           <div class="flex-1-1-100">
                             <span class="ma-2 pa-2  mb-1 ">Patient Name:</span>
                             <span class="ma-2 pa-2  mb-1"> {{ patient_details.patient_name }}</span>
                           </div>
                           <div class="flex-1-1-100">
                             <span class="ma-2 pa-2 mb-1 ">Patient Phone:</span>
                             <span class="ma-2 pa-2  mb-1"> {{ patient_details.patient_phone }}</span>
                           </div>
                         </v-row>
                       </v-card-text>
                     </v-card>
                 </v-col>
               </v-row>
         </v-col>
        </v-col>

      </v-row>
    </v-row>
  </v-container>
</template>
<script setup>
import {computed, onMounted, ref} from 'vue'
import axiosInstance from "@/services/axiosService";
import {useNotification} from "@/store/notification";
import {useRoute, useRouter} from "vue-router";
import { useField, useForm } from 'vee-validate'

const notify = useNotification();
const patient_details = ref([]);
const patientId = ref('');
const router = useRouter();
const special_doctor = ref([]);
const referral_doctor = ref([]);
const examination_list = ref([]);


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


onMounted(() => {
  patientId.value = useRoute().params.patientId;
  fetchPatientDetails();
  fetchDoctorData();
  fetchExaminationListData();
});

const fetchPatientDetails = async () => {
  try {
    const response = await axiosInstance.get(`/admin/patients/patients/${patientId.value}`); // get patient details
    patient_details.value = response.data.patient_details;
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
const fetchExaminationListData = async () => {
  try {
    const response = await axiosInstance.get(`/admin/invoice/examination-list`); // get doctor details
    examination_list.value = response.data.examination_list;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

const patientExamination = () => {
  router.push({ path: `/patient-examination-invoice/${patient_details.value.patient_id}` });
};

const { handleSubmit, handleReset } = useForm({
  validationSchema: {
    name (value) {
      if (value?.length >= 2) return true

      return 'Name needs to be at least 2 characters.'
    },
    phone (value) {
      if (value?.length > 9 && /[0-9-]+/.test(value)) return true

      return 'Phone number needs to be at least 9 digits.'
    },
    email (value) {
      if (/^[a-z.-]+@[a-z.-]+\.[a-z]+$/i.test(value)) return true

      return 'Must be a valid e-mail.'
    },
    select (value) {
      if (value) return true

      return 'Select an item.'
    },
    checkbox (value) {
      if (value === '1') return true

      return 'Must be checked.'
    },
  },
})
const name = useField('name')
const phone = useField('phone')
const email = useField('email')
const select = useField('select')
const checkbox = useField('checkbox')

const items = ref([
  'Item 1',
  'Item 2',
  'Item 3',
  'Item 4',
])

const submit = handleSubmit(values => {
  alert(JSON.stringify(values, null, 2))
})
</script>
