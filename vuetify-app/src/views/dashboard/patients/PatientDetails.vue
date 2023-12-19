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
          <v-card-text>
            <v-row>
              <div class="d-flex flex-wrap ga-3">
                <v-card-text class="text-h6 mb-1">PATIENT ID:</v-card-text>
                <v-card-text class="text-h6 mb-1"> {{ patientId }}</v-card-text>
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
const patient_sources=ref([]);
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
  fetchPatientDetails ();
});

const fetchPatientDetails = async () => {
  try {
    const response = await axiosInstance.get(`/admin/patients/patients/${patientId.value}`); // get patient details
    console.log(response.data);
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};








</script>
