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
      <v-col>
        <v-card
          class="mx-auto my-2"
          title="Patient Data"
          prepend-icon="mdi-36px mdi-light mdi-clipboard-text-outline"
          rel="noopener"
          color="warning"

        ></v-card>
        <v-col cols="12">
          <v-card>
            <v-container>
              <v-row class="d-flex flex-row-reverse">
                <v-col cols="2">
                  <v-btn
                    append-icon="mdi-plus"
                    color="primary"
                    @click="patientCreate()"
                  >
                    Create
                  </v-btn>
                </v-col>
                <v-col cols="6">
                    <v-text-field
                      v-model="data_search"
                      :loading="loading"
                      density="compact"
                      variant="solo"
                      label="Search"
                      append-inner-icon="mdi-magnify"
                      single-line
                      hide-details
                    ></v-text-field>
                </v-col>
              </v-row>
            </v-container>
          </v-card>
        </v-col>
        <v-col cols="12">
          <v-data-table
            :headers="headers"
            :items="desserts"
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
              <v-icon
                title="Patient Examination"
                size="small"
                class="me-2"
                color="primary"
                icon=" mdi-calendar-clock"
                @click="patientAppointment(item)"
              >
              </v-icon>
              <v-icon
                title="Patient Examination"
                size="small"
                class="me-2"
                color="green-darken-2"
                icon="mdi-file-document-edit-outline"
                @click="patientExamination(item)"
              >
              </v-icon>
              <v-icon
                title="Patient Details"
                size="small"
                class="me-2"
                color="blue-darken-2"
                icon="mdi-eye-outline"
                @click="patientDetails(item)"
              >
              </v-icon>
            </template>
          </v-data-table>
        </v-col>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import {ref, computed, onMounted,watch} from 'vue';
import axiosInstance from "@/services/axiosService";
import { useRouter } from 'vue-router';


const search = ref('');
const data_search = ref('');
const desserts = ref([]);


const loading = ref(false);
const router = useRouter();
const date=ref(new Date().toISOString().substr(0, 10))

const breadcrumbs = computed(() => [
  {
    title: 'Home',
    disabled: false,
    href: '/',
  },
  {
    title: 'Patients',
    disabled: false,
    href: '#',
  },
]);
const headers = computed(() => [
  {id: 'id', title: 'PID', align: 'start', key: 'patient_id',},
  {id: 'id', title: 'Name', align: 'end', key: 'patient_name',},
  {id: 'id', title: 'Phone', align: 'end', key: 'patient_phone'},
  {id: 'id', title: 'DOB', align: 'end', key: 'patient_dob'},
  {id: 'id', title: 'Actions', key: 'actions', sortable: false},

]);

onMounted(() => {
  fetchData();
});

watch(data_search, () => {
  fetchData();
});

const fetchData = async () => {
  try {
    loading.value = true;
    const response = await axiosInstance(`/admin/patients/patients?date=${date.value}&search=${data_search.value}`);
    desserts.value = response.data.patient_info;
    console.log('data search',data_search.value)
  } catch (error) {
    console.error('Error fetching data:', error);
  } finally {
    loading.value = false;
  }
};


const patientCreate = () => {
  router.push({ path: '/patient-create' });
};
const patientDetails = (item) => {
  router.push({ path: `/patient-details/${item.selectable.patient_id}` });
};
const patientExamination = (item) => {
  router.push({ path: `/patient-examination-invoice/${item.selectable.patient_id}` });
};
const patientAppointment = (item) => {
  router.push({ path: `/patient-appointment/${item.selectable.patient_id}` });
};


</script>

