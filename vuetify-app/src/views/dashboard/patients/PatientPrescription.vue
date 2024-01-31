<template>
     <v-row>
       <v-col>
         <v-col cols="12">
           <v-card>
             <v-container>
               <v-row >
                 <v-col cols="4">
                   <v-autocomplete
                     label="Previous Prescription"
                   >
                   </v-autocomplete>
                 </v-col>
                 <v-col cols="4">
                   <v-btn class="ma-2" color="success" @click="submit"> Save</v-btn>
                   <v-btn class="ma-2" prepend-icon="mdi-printer" color="primary"> Print</v-btn>
                 </v-col>
               </v-row>
             </v-container>
           </v-card>
         </v-col>
           <v-row>
                  <v-col cols="3">
                    <v-card class="pa-2 ma-2">
                      <v-card-text>
                          <v-row>
                            <v-col>
                              <v-card
                                class="mx-auto"
                                max-width="100%"
                                color="primary"
                                variant="flat"
                              >
                                <v-card-item>
                                  <div v-if="appointment_info.patient_info">
                                    <div class="text-overline mb-1">
                                      <span >Name: {{appointment_info.patient_info.patient_name}}</span>
                                    </div>
                                    <div class="text-overline mb-1">
                                      <span>ID: {{appointment_info.patient_id}}</span>
                                    </div>
                                    <div class="text-overline mb-1">
                                      <span>Doctor: {{appointment_info.doctor_info.doctor_name}}</span>
                                    </div>
                                  </div>
                                </v-card-item>
                              </v-card>
                            </v-col>
                            <v-col cols="12">
                              <v-text-field
                                v-model="submitForm.symptoms"
                                required
                                minlength="2"
                                label="Symptoms"
                              ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                              <v-autocomplete
                                v-model="submitForm.examination_data"
                                required
                                minlength="2"
                                label="Examination(Pathology)"
                              ></v-autocomplete>
                            </v-col>
                            <v-col cols="12">
                              <v-textarea
                                v-model="submitForm.advice_note"
                                required
                                label="Advice Note"
                              ></v-textarea>
                            </v-col>
                            <v-col cols="12">
                              <v-text-field
                                v-model="submitForm.followup_date"
                                label="Followup Date"
                                type="date"
                              >
                              </v-text-field>
                            </v-col>
                          </v-row>
                      </v-card-text>
                    </v-card>
                  </v-col>
                  <v-col>
                    <v-card class="pa-2 ma-2">
                      <v-card-text>

                          <v-row>
                            <v-col cols="2">
                              <v-autocomplete
                                v-model="submitForm.medicine_type"
                                required
                                label="Type"
                              ></v-autocomplete>
                            </v-col>
                            <v-col cols="3">
                              <v-autocomplete
                                v-model="submitForm.medicine_name"
                                :items="medicine_data"
                                required
                                label="Medicine"
                                item-value="id"
                                item-title="medicine_name"
                              ></v-autocomplete>
                            </v-col>
                            <v-col cols="2">
                              <v-text-field
                                v-model="submitForm.duration"
                                required
                                label="Duration"
                              ></v-text-field>
                            </v-col>
                            <v-col cols="3">
                              <v-autocomplete
                                v-model="submitForm.medicine_instruction"
                                required
                                label="Instruction"
                              ></v-autocomplete>
                            </v-col>
                            <v-col cols="2">
                              <v-btn icon="mdi-plus" color="warning"></v-btn>
                            </v-col>
                          </v-row>

                      </v-card-text>
                    </v-card>
                  </v-col>
           </v-row>
       </v-col>
     </v-row>
</template>
<script setup>
import { onMounted, ref} from 'vue'
import axiosInstance from "@/services/axiosService";
import {useNotification} from "@/store/notification";
import {useAuth} from "@/store/auth";
import {useRoute} from "vue-router";

const notify = useNotification();
const userData = useAuth();
const appointment_info = ref([]);
const medicine_data = ref([]);
const user_id = userData.user.data.id;
const app_id = useRoute().params.app_id;
const prescription_id = useRoute().params.prescriptionId;


const submitForm=ref({
  symptoms:'',
  examination_data:'',
  advice_note:'',
  followup_date:'',
  medicine_type:'',
  medicine_name:'',
  duration:'',
  medicine_instruction:'',
  user_id: user_id,

});


onMounted(() => {
  fetchAppointmentInfo (); // Fetch data when the component is mounted
  fetchMedicineData ();
});

const fetchAppointmentInfo = async () => {
  try {
    const response = await axiosInstance(`/admin/appointment/patient-appointment-info/${app_id}`); // Replace with your API endpointa
    appointment_info.value = response.data.appointment_info;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};
const fetchMedicineData = async () => {
  try {
    const response = await axiosInstance(`/admin/prescription/medicine-data`); // Replace with your API endpointa
    medicine_data.value = response.data.medicine_data;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};





const submit = async () => {

  console.log('submit data',submitForm.value)
  // Validate form fields
  // const isFormValid = await validateForm();


  // if (isFormValid) {
  //   // Proceed with API submission
  //   try {
  //     const response = await axiosInstance.post('/admin/patients/patients?user_id=' + user_id, submitForm.value);
  //     if (response.data.message) {
  //       notify.Success(response.data.message);
  //     } else {
  //       notify.Error(response.data.errors);
  //     }
  //   } catch (error) {
  //     notify.Error(error.response.data.errors);
  //   }
  // } else {
  //   console.error('Form validation failed. Please check the fields.');
  // }
};





</script>
