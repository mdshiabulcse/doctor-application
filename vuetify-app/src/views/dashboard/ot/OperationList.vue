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
      <v-col cols="12" class="">
        <v-card
          class="mx-auto my-2"
          title="Operation List"
          prepend-icon="mdi-36px mdi-light mdi-clipboard-text-outline"
          rel="noopener"
          color="warning"

        ></v-card>
        <v-col cols="12">
          <v-card>
            <v-container>
              <v-row>
                <v-col cols="3">
                  <v-autocomplete
                    v-model="handleSubmit.doctor_id"
                    :items="special_doctor"
                    color="blue-grey-lighten-2"
                    item-value="id"
                    item-title="doctor_name"
                    label="Select Doctor"
                    @change="onDoctorChange"
                  ></v-autocomplete>
                </v-col>
                <v-col cols="3">
                  <v-autocomplete
                    v-model="handleSubmit.doctor_id"
                    :items="special_doctor"
                    color="blue-grey-lighten-2"
                    item-value="id"
                    item-title="doctor_name"
                    label="Select Doctor"
                    @change="onDoctorChange"
                  ></v-autocomplete>
                </v-col>
                <v-col cols="3">
                  <v-text-field
                    v-model="handleSubmit.start_date"
                    type="date"
                    label="Start Date"
                    hint="MM/DD/YYYY format"
                    prepend-icon="mdi-calendar"
                    required
                    @change="onDateChange"
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="3">
                  <v-text-field
                    v-model="handleSubmit.end_date"
                    type="date"
                    label="End Date"
                    hint="MM/DD/YYYY format"
                    prepend-icon="mdi-calendar"
                    required
                    @change="onDateChange"
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="auto">
                  <v-btn prepend-icon="mdi-printer" color="primary"> Print</v-btn>

                </v-col>
                <v-col cols="auto">
                  <v-btn prepend-icon="mdi-plus" color="primary" @click="operationRegistration">Create</v-btn>
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
            :items="operation_list"
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
<!--            <template v-if="handleSubmit.appointment_date === moment().format('YYYY-MM-DD')"-->
<!--                      v-slot:item.actions="{ item }">-->
<!--              <v-btn class="me-2" icon="mdi-clippy" title="Consultation Invoice" color="warning"-->
<!--                     @click="appointmentInvoice(item)">-->
<!--              </v-btn>-->
<!--              <v-btn class="me-2" icon="mdi-file-document-edit-outline" title="New Prescription" color="primary"-->
<!--                     @click="appointmentPrescription(item)">-->
<!--              </v-btn>-->
<!--            </template>-->
          </v-data-table>
        </v-col>
      </v-col>
    </v-row>
  </v-container>
</template>
<script setup>
import {onMounted, ref, watchEffect} from 'vue'
import axiosInstance from "@/services/axiosService";
import {useRouter} from "vue-router";
import moment from "moment-timezone";

const router = useRouter();
const special_doctor = ref([]);
const breadcrumbs = [
  {title: 'Home', disabled: false, href: '/'},
  {title: 'Operation List', disabled: false, href: '#'},
];
const headers = [
  {id: 'id', title: 'OT Reg', align: 'end', key: 'registration_number'},
  {id: 'id', title: 'PID', align: 'start', key: 'patient_id'},
  {id: 'id', title: 'Patient Name', align: 'end', key: 'patient_info.patient_name'},
  {id: 'id', title: 'Ot Name', align: 'end', key: 'operation_info.operation_name'},
  {id: 'id', title: 'Doctor', align: 'end', key: 'doctor_info.doctor_name'},
  {id: 'id', title: 'Ot Amount', align: 'end', key: 'ot_amount'},
  {id: 'id', title: 'Paid Amount', align: 'end', key: 'ot_amount_paid'},
  {id: 'id', title: 'Status', align: 'end', key: 'status'},
  {id: 'id', title: 'Actions', key: 'actions', sortable: false},
];
const search = ref('');
const appointment_data = ref([]);
const operation_list = ref([]);
const loading = ref(true);
const handleSubmit = ref({
  doctor_id: '',
  start_date: moment().format('YYYY-MM-DD'),
  end_date: moment().format('YYYY-MM-DD'),
});

onMounted(() => {
  appointmentData();
  fetchDoctorData();
});

const fetchDoctorData = async () => {
  try {
    const response = await axiosInstance.get(`/admin/default/special-doctor-list`);
    special_doctor.value = response.data.special_doctor_list;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

const onDoctorChange = async () => {
  try {
    await fetchDoctorData();
    if (handleSubmit.value.doctor_id && handleSubmit.value.appointment_date) {
      await appointmentData();
    }
  } catch (error) {
    console.error('Error in onDoctorChange:', error);
  }
};

const onDateChange = async () => {
  try {
    if (handleSubmit.value.doctor_id || handleSubmit.value.start_date|| handleSubmit.value.end_date) {
      await appointmentData();
    }
  } catch (error) {
    console.error('Error in onDateChange:', error);
  }
};

const appointmentData = async () => {
  try {
    loading.value = true
    let apiUrl = `/admin/ot/operation?start_date=${handleSubmit.value.start_date}` + `&end_date=${handleSubmit.value.end_date}`;

    if (handleSubmit.value.doctor_id) {
      loading.value = true
      apiUrl += `&doctor_id=${handleSubmit.value.doctor_id}`;

    }

    const response = await axiosInstance.get(apiUrl);
    operation_list.value = response.data.operation_list;
    loading.value = false;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

const appointmentInvoice = (item) => {
  router.push({path: `/patient-consultation-invoice/${item.selectable.id}/${item.selectable.patient_id}`});
};
const appointmentPrescription = (item) => {
  router.push({path: `/patient-prescription/${item.selectable.id}/${item.selectable.patient_id}/${null}`});
};
const operationRegistration = () => {
  router.push({path: `/operation-registration`});
};
watchEffect(() => {
  appointmentData();
});
</script>
<style scoped>
.v-data-table td {
  font-size: 10px; /* Adjust the font size as needed */
}

</style>
