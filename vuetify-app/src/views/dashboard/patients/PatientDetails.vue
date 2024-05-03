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
                    <v-btn prepend-icon="mdi-medical-bag" color="warning" @click="patientExamination">Examination
                    </v-btn>
                  </v-col>
                </v-row>
              </v-container>
            </v-card>
          </v-col>
          <v-col cols="8">
            <v-card
              class="mx-auto"
            >
              <v-img
                height="200"
                src="https://picsum.photos/700?image=996"
                cover
              ></v-img>
              <v-card-item class="bg-cyan-darken-1">
                <v-card-title>
                  <span class="text-h5">{{ patient_details.patient_name }}</span>
                </v-card-title>

                <template v-slot:append>
                  <v-defaults-provider :defaults="{
                    VBtn: {
                      variant: 'text',
                      density: 'comfortable',
                      }}">
                    <v-btn icon="mdi-pencil"></v-btn>
                  </v-defaults-provider>
                </template>
              </v-card-item>

              <v-list>
                <v-list-item
                  prepend-icon="mdi-card-account-details"
                  :title="patient_details.patient_id"
                ></v-list-item>
                <v-divider></v-divider>

                <v-list-item
                  prepend-icon="mdi-phone"
                  :title="patient_details.patient_phone"
                ></v-list-item>

                <v-divider></v-divider>

                <v-list-item
                  prepend-icon="mdi-email"
                  :title="patient_details.patient_email"
                ></v-list-item>
                <v-divider></v-divider>
                <v-list-item
                  prepend-icon="mdi-cake"
                  :title="patient_details.patient_dob"
                ></v-list-item>
                <v-divider></v-divider>
                <v-list-item
                  prepend-icon="mdi-gender-male-female-variant"
                  :title="patient_details.gender"
                ></v-list-item>
                <v-divider></v-divider>
                <v-list-item
                  prepend-icon="mdi-calendar-edit"
                  :title="patient_details.created_at"
                ></v-list-item>
                <v-divider></v-divider>
              </v-list>
            </v-card>
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
  router.push({path: `/patient-examination-invoice/${patient_details.value.patient_id}`});
};

</script>
