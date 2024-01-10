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
            title="Appointment Data"
            prepend-icon="mdi-36px mdi-light mdi-clipboard-text-outline"
            rel="noopener"
            color="warning"

          ></v-card>
          <v-col cols="12">
            <v-card>
              <v-container>
                <v-row align="center" justify="center">
                  <v-col cols="auto">
                    <v-btn prepend-icon="mdi-file-document-edit-outline" color="info"> Edit Patinet</v-btn>
                  </v-col>
                  <v-col cols="auto">
                    <v-btn prepend-icon="mdi-printer" color="warning"> Print Invoice</v-btn>
                  </v-col>
                </v-row>
              </v-container>
            </v-card>
          </v-col>
          <v-col cols="12">
            <v-sheet class="d-flex align-end flex-column ">

            </v-sheet>
            <v-data-table
              :headers="headers"
              :items="appointment_data"
              :search="search"
              class="elevation-1"
              item-value="id"
              :loading="loading"
            >
              <template v-slot:top>
                <v-text-field
                  v-model="search"
                  label="Search"
                  class="pa-0"
                ></v-text-field>
              </template>
              <template v-slot:item.actions="{ item }">
                <v-btn class="me-2" icon="mdi-file-document-edit-outline" title="More" color="warning"  @click="appointmentInvoice(item)">

                </v-btn>
              </template>
            </v-data-table>
          </v-col>
        </v-col>
      </v-row>
    </v-row>
  </v-container>
</template>
<script setup>
import {computed, onMounted, ref} from 'vue'
import axiosInstance from "@/services/axiosService";
import {useRoute, useRouter} from "vue-router";

const router = useRouter();
const breadcrumbs = computed(() => [
  {
    title: 'Home',
    disabled: false,
    href: '/',
  },
  {
    title: 'Patients Appointment',
    disabled: false,
    href: '#',
  },
]);
const headers = computed(() => [
  {id: 'id', title: 'PID', align: 'start', key: 'patient_id',},
  {id: 'id', title: 'Name', align: 'end', key: 'patient_info.patient_name',},
  {id: 'id', title: 'Serial', align: 'end', key: 'appointment_sl',},
  {id: 'id', title: 'Time', align: 'end', key: 'appointment_time',},
  {id: 'id', title: 'Doctor', align: 'end', key: 'doctor_info.doctor_name'},
  {id: 'id', title: 'Status', align: 'end', key: 'status'},
  {id: 'id', title: 'DOB', align: 'end', key: 'patient_info.patient_dob'},
  {id: 'id', title: 'Actions', key: 'actions', sortable: false},

]);
const search = ref('');
const appointment_data = ref([]);
const loading = ref(true);

onMounted(() => {
  appointmentData();
});
const appointmentData = async () => {
  try {
    const response = await axiosInstance.get(`/admin/appointment/appointment-data`); // get doctor details
    appointment_data.value = response.data.appointment_data;
    loading.value=false;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

const appointmentInvoice = (item) => {
  console.log('item',item.selectable)
  router.push({ path: `/patient-consultation-invoice/${item.selectable.id}/${item.selectable.patient_id}` });
};

</script>
<style scoped>
/* Add your custom styles for smaller font size or other styling here */
/* For example: */
.v-data-table td {
  font-size: 12px; /* Adjust the font size as needed */
}

/* Add more custom styles if required */
</style>
