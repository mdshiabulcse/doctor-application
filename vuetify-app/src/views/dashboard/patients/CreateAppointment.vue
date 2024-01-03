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
            title="Appointment Booking"
            prepend-icon="mdi-36px mdi-light mdi-calendar-check"
            rel="noopener"
            color="warning"
          ></v-card>
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

                      <v-col cols="12">
                        <v-btn
                          size="large"
                          color="primary"
                          prepend-icon="mdi-file-document-edit-outline"
                          type="submit"

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
      </v-row>
    </v-row>
  </v-container>
</template>
<script setup>
import {computed, onMounted, ref} from 'vue'
import axiosInstance from "@/services/axiosService";
import {useNotification} from "@/store/notification";
import {useRoute, useRouter} from "vue-router";
import {useAuth} from "@/store/auth";


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
const userData = useAuth();
const user_id = userData.user.data.id;
const handleSubmit = ref({
  doctor_id: '',
});

const fetchPatientDetails = async () => {
  try {
    const response = await axiosInstance.get(`/admin/patients/patients/${patientId.value}`); // get patient details
    patient_details.value = response.data.patient_details;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

onMounted(() => {
  patientId.value = useRoute().params.patientId;
  fetchPatientDetails();
});
</script>
