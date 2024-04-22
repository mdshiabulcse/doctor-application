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
             title="Patient Details"
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
           <v-card>
             <v-card-text class="">
               <v-row >
                 <div class="flex-1-1-100">
                   <span class="ma-2 pa-2  mb-1 ">Patient ID:</span>
                   <span class="ma-2 pa-2 mb-1"> {{ patient_details.patient_id }}</span>
                 </div>
                 <div class="flex-1-1-100">
                   <span class="ma-2 pa-2  mb-1 ">Patient Name:</span>
                   <span class="ma-2 pa-2  mb-1"> {{ patient_details.patient_name }}</span>
                 </div>
                 <div class="flex-1-1-100">
                   <span class="ma-2 pa-2 mb-1 ">Patient Phone:</span>
                   <span class="ma-2 pa-2  mb-1"> {{ patient_details.patient_phone }}</span>
                 </div>
                 <div class="flex-1-1-100">
                   <span class="ma-2 pa-2  mb-1 ">Patient E-mail:</span>
                   <span class="ma-2 pa-2  mb-1"> {{ patient_details.patient_email }}</span>
                 </div>
                 <div class="flex-1-1-100">
                   <span class="ma-2 pa-2  mb-1 ">Patient DOB:</span>
                   <span class="ma-2 pa-2  mb-1"> {{ patient_details.patient_dob }}</span>
                 </div>
                 <div class="flex-1-1-100">
                   <span class="ma-2 pa-2  mb-1 ">Patient Gender:</span>
                   <span class="ma-2 pa-2  mb-1"> {{ patient_details.gender }}</span>
                 </div>
                 <div class="flex-1-1-100">
                   <span class="ma-2 pa-2  mb-1 ">Patient Registration:</span>
                   <span class="ma-2 pa-2  mb-1"> {{ patient_details.created_at }}</span>
                 </div>
               </v-row>
             </v-card-text>
           </v-card>
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

const notify = useNotification();
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
    title: 'Patients Details',
    disabled: false,
    href: '#',
  },
]);


onMounted(() => {
  patientId.value = useRoute().params.patientId;
  fetchPatientDetails();
});

const fetchPatientDetails = async () => {
  try {
    const response = await axiosInstance.get(`/admin/patients/patients/${patientId.value}`); // get patient details
    patient_details.value = response.data.patient_details;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};
const patientExamination = () => {
  router.push({ path: `/patient-examination-invoice/${patient_details.value.patient_id}` });
};

</script>
