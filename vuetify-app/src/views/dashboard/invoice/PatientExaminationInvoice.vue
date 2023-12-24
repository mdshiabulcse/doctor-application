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
                       <form >
                         <v-row>
                           <v-col cols="6">
                             <v-autocomplete
                               v-model="handleSubmit.doctor_id"
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
                               v-model="handleSubmit.ref_doctor_id"
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
                              v-model="handleSubmit.examination_id"
                              :items="examination_list"
                              color="blue-grey-lighten-2"
                              item-value="id"
                              item-title="ex_name"
                              label="Examination"
                            ></v-autocomplete>
                          </v-col>
                           <v-col cols="4">
                             <v-text-field
                               v-model="handleSubmit.ex_unit_price"
                               label="Unit Price"
                             ></v-text-field>
                           </v-col>
                           <v-col cols="1">
                             <v-btn @click="addExamination" icon="mdi-plus" size="small" color="primary"></v-btn>
                           </v-col>
                           <v-col cols="4">
                             <v-text-field
                               v-model="handleSubmit.subtotal"
                               color="blue-grey-lighten-2"
                               label="Subtotal"
                             ></v-text-field>
                           </v-col>
                           <v-col cols="3">
                             <v-text-field
                               v-model="handleSubmit.total_discount"
                               color="blue-grey-lighten-2"
                               label="Discount"
                             ></v-text-field>
                           </v-col>
                           <v-col cols="1">

                             <v-btn  @click="dialog = true"  append-icon="mdi-sale" size="small" color="primary">{{handleSubmit.discount}}</v-btn>
                           </v-col>
                           <v-col cols="4">
                             <v-text-field
                               v-model="handleSubmit.total_paid_amount"
                               color="blue-grey-lighten-2"
                               label="Total Paid"
                             ></v-text-field>
                           </v-col>
                           <v-col cols="4">
                           </v-col>
                           <v-col cols="4">
                             <v-text-field
                               v-model="handleSubmit.due_amount"
                               color="blue-grey-lighten-2"
                               label="Due Amount"
                             ></v-text-field>
                           </v-col>
                           <v-col cols="4">
                             <v-text-field
                               type="number"
                               v-model="handleSubmit.received_amount"
                               color="blue-grey-lighten-2"
                               label="Total Received"
                             ></v-text-field>
                           </v-col>

                           <v-col cols="12">
                             <v-btn
                               class="me-4"
                               type="submit"
                             >
                               submit
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
                    v-model="handleSubmit.discount"
                  :items="discount_list"
                  item-title="discount"
                  >

                  </v-select>
                </v-card-text>
                <v-card-actions>
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
const discount_list = ref([]);
const dialog=ref(false);
const handleSubmit=ref({
  doctor_id:'',
  ref_doctor_id:'',
  examination_id:'',
  ex_unit_price:'',
  subtotal:'',
  total_discount:'',
  discount:'',
  total_paid_amount:'',
  received_amount:'',
  due_amount:'',
  });

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
  fetchDiscountListData();
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

const patientExamination = () => {
  router.push({ path: `/patient-examination-invoice/${patient_details.value.patient_id}` });
};
const addExamination = () => {

};

</script>
