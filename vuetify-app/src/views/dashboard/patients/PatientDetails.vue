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
      <v-col cols="7">
        <v-card
          class="mx-auto my-2"
          title="Patient Details"
          prepend-icon="mdi-36px mdi-light mdi-clipboard-text-outline"
          rel="noopener"
          color="warning"
        ></v-card>
        <v-card>
          <v-expansion-panels variant="popout" class="pa-4">
              <v-expansion-panel-title>
                <v-row
                  align="center"
                  class="spacer"
                  no-gutters
                >
                  <v-col
                    cols="4"
                    sm="2"
                    md="1"
                  >
                    <v-avatar
                      size="36px"
                    >
                      <v-img
                        alt="Avatar"
                        src="https://avatars0.githubusercontent.com/u/9064066?v=4&s=460"
                      ></v-img>
                      <v-icon
                      ></v-icon>
                    </v-avatar>
                  </v-col>

                  <v-col
                    class=" text-left ms-2"
                    sm="5"
                    md="3"
                  >
                  </v-col>

                  <v-col
                    class="text-no-wrap text-left"
                    cols="5"
                    sm="3"
                  >
                    <v-chip
                      :color="`green-lighten-1`"
                      class="ms-0 me-2"
                      label
                      small
                    >
                      {{ patient_details.patient_name }}
                    </v-chip>
                    <strong>2222</strong>
                  </v-col>


                </v-row>
              </v-expansion-panel-title>

              <v-expansion-panel-text>
                <v-card-text>dgsdgsfd</v-card-text>
              </v-expansion-panel-text>
          </v-expansion-panels>
          <v-card-text>
            <v-row class="d-flex flex-wrap">
              <div class="flex-1-1-100">
                <span class="ma-2 pa-2 text-h6 mb-1 ">Patient ID:</span>
                <span class="ma-2 pa-2 text-h6 mb-1"> {{ patient_details.patient_id }}</span>
              </div>
              <div class="flex-1-1-100">
                <span class="ma-2 pa-2 text-h6 mb-1 ">Patient Name:</span>
                <span class="ma-2 pa-2 text-h6 mb-1"> {{ patient_details.patient_name }}</span>
              </div>
              <div class="flex-1-1-100">
                <span class="ma-2 pa-2 text-h6 mb-1 ">Patient Phone:</span>
                <span class="ma-2 pa-2 text-h6 mb-1"> {{ patient_details.patient_phone }}</span>
              </div>
              <div class="flex-1-1-100">
                <span class="ma-2 pa-2 text-h6 mb-1 ">Patient E-mail:</span>
                <span class="ma-2 pa-2 text-h6 mb-1"> {{ patient_details.patient_email }}</span>
              </div>
              <div class="flex-1-1-100">
                <span class="ma-2 pa-2 text-h6 mb-1 ">Patient DOB:</span>
                <span class="ma-2 pa-2 text-h6 mb-1"> {{ patient_details.patient_dob }}</span>
              </div>
              <div class="flex-1-1-100">
                <span class="ma-2 pa-2 text-h6 mb-1 ">Patient Gender:</span>
                <span class="ma-2 pa-2 text-h6 mb-1"> {{ patient_details.gender }}</span>
              </div>
              <div class="flex-1-1-100">
                <span class="ma-2 pa-2 text-h6 mb-1 ">Patient Registration:</span>
                <span class="ma-2 pa-2 text-h6 mb-1"> {{ patient_details.created_at }}</span>
              </div>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>

    </v-row>
  </v-container>
</template>
<script setup>
import {computed, onMounted, ref} from 'vue'
import axiosInstance from "@/services/axiosService";
import {useNotification} from "@/store/notification";
import {useAuth} from "@/store/auth";
import {useRoute} from "vue-router";

const notify = useNotification();
const userData = useAuth();
const user_id = userData.user.data.id;
const patient_details = ref([]);
const patientId = ref('');


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


</script>
