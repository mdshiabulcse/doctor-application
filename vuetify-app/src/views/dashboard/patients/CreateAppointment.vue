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
            :loading="loading"
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
                          @change="onDoctorChange"
                        ></v-autocomplete>
                      </v-col>
                      <v-col
                        cols="6"
                      >
                        <v-text-field
                          v-model="handleSubmit.appointment_date"
                          type="date"
                          label="Appointment Date"
                          hint="MM/DD/YYYY format"
                          prepend-icon="mdi-calendar"
                          :min="moment().format('YYYY-MM-DD')"
                          required
                          @change="onDateChange"
                        ></v-text-field>
                      </v-col>
                      <v-container>
                        <h2 class="mb-4">Booking Slots</h2>
                        <v-row>
                          <v-col v-for="(slot, index) in timeSlots" :key="index" cols="12" sm="6" md="4" lg="3">
                            <v-card
                              class="mx-auto mb-4"
                              :color="getCardColor(slot)"
                              height="100"
                              :title="slot.serialNumber"
                            >
                              <template v-slot:prepend>
                                <v-icon icon="mdi-account" color="warning"></v-icon>
                              </template>
                              <template v-slot:append>
                                <v-btn @click="bookSlot(slot)" icon="mdi-calendar" color="warning" size="small"></v-btn>
                              </template>
                              <v-card-text>{{ slot.startTime + '-' + slot.endTime }}</v-card-text>
                            </v-card>
                          </v-col>
                        </v-row>
                      </v-container>
                      <v-col cols="12">
                        <v-btn
                          size="large"
                          color="primary"
                          prepend-icon="mdi-file-document-edit-outline"
                          type="submit"
                          @click="submitForm"

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
import {computed, onMounted, ref, watch} from 'vue'
import axiosInstance from "@/services/axiosService";
import {useNotification} from "@/store/notification";
import {useRoute, useRouter} from "vue-router";
import {useAuth} from "@/store/auth";
import {ElMessageBox} from "element-plus";
import moment from "moment-timezone";


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
const appointment_data = ref([]);
const timeSlotArrays = ref([]);
const loading = ref(true);
const userData = useAuth();
const user_id = userData.user.data.id;

const handleSubmit = ref({
  doctor_id: '',
  appointment_date: moment().format('YYYY-MM-DD'),
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
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};


onMounted(() => {
  patientId.value = useRoute().params.patientId;
  fetchPatientDetails();
  fetchDoctorData();
  appointmentSetting();
  generateTimeSlots();
  appointmentData();
});

const appointmentSetting = async () => {
  try {
    const response = await axiosInstance.get(`/admin/appointment/appointment-setting`);
    timeSlotArrays.value = response.data.appointment_settings;
    loading.value = false;
    generateTimeSlots();
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

const timeSlots = ref([]);
let serialNumberCounter = 1;
const selectedSlot = ref(null);

const generateTimeSlots = () => {
  for (const timeSlot of timeSlotArrays.value) {
    const currentDate = new Date();
    const start = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate(), timeSlot.start_time.split(':')[0], timeSlot.start_time.split(':')[1]);
    const end = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate(), timeSlot.end_time.split(':')[0], timeSlot.end_time.split(':')[1]);
    let current = new Date(start);

    while (current < end) {
      const formattedTime = current.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'});
      const nextTime = new Date(current.getTime() + timeSlot.minute * 60000);
      const formattedNextTime = nextTime.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'});

      timeSlots.value.push({
        id: serialNumberCounter,
        time: formattedTime,
        serialNumber: serialNumberCounter,
        isBooked: false,
        slotNumber: timeSlot.slotNumber,
        startTime: formattedTime,
        endTime: formattedNextTime,
        appointmentCount: 0, // Initialize appointmentCount to 0
      });

      current = nextTime;
      serialNumberCounter++;
    }
  }
};

const bookSlot = (clickedSlot) => {
  if (!clickedSlot.isBooked && clickedSlot !== selectedSlot.value) {
    if (selectedSlot.value) {
      selectedSlot.value.isBooked = false;
    }

    clickedSlot.appointmentCount += 1; // Increment appointmentCount for the clicked slot
    clickedSlot.isBooked = clickedSlot.appointmentCount > 0; // Update isBooked based on appointmentCount

    showAlert(clickedSlot);
    selectedSlot.value = clickedSlot;
  }
};

const getCardColor = (slot) => {
  return slot.isBooked ? 'error' : 'primary';
};

const showAlert = (selectedSlot) => {
  console.log(`Slot booked: Serial Number - ${selectedSlot.serialNumber}, Start Time - ${selectedSlot.startTime}, End Time - ${selectedSlot.endTime}`);
  alert(`Slot booked: Start Time - ${selectedSlot.startTime}, End Time - ${selectedSlot.endTime}, Serial Number - ${selectedSlot.serialNumber}`);
};


const submitForm = async () => {
  try {
    const formData = {
      doctor_id: handleSubmit.value.doctor_id,
      appointment_date: handleSubmit.value.appointment_date,
      slot: selectedSlot.value,
      user_id: user_id,
      patient_id: patientId.value,
      serial_number: selectedSlot.value.serialNumber,
      slot_time_start: selectedSlot.value.time,
    };
    const confirmResult = await ElMessageBox.confirm(
      'Do you want to submit the form?',
      'Confirmation',
      {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        type: 'warning',
        center: true,
      }
    );

    if (confirmResult === 'confirm') {
      const response = await axiosInstance.post('/admin/appointment/appointment-data', formData);
      notify.Success(response.data.message);
    }
  } catch (error) {
    notify.Error(error.response.data.errors);
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
    if (handleSubmit.value.doctor_id && handleSubmit.value.appointment_date) {
      await appointmentData();
    }
  } catch (error) {
    console.error('Error in onDateChange:', error);
  }
};

const appointmentData = async () => {
  try {
    if (!handleSubmit.value.doctor_id || !handleSubmit.value.appointment_date) {
      return;
    }

    const response = await axiosInstance.get(`/admin/appointment/appointment-booking-patient/${handleSubmit.value.doctor_id}?appointment_date=${handleSubmit.value.appointment_date}`);
    appointment_data.value = response.data.appointment_data;

    timeSlots.value.forEach(slot => {
      const isBooked = appointment_data.value.some(appointment => appointment.appointment_sl === slot.serialNumber);
      slot.isBooked = isBooked;
    });
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

watch(
  [() => handleSubmit.value.doctor_id, () => handleSubmit.value.appointment_date],
  async ([newDoctorId, newAppointmentDate]) => {
    try {
      if (!newDoctorId || !newAppointmentDate) {
        return;
      }

      const response = await axiosInstance.get(`/admin/appointment/appointment-booking-patient/${newDoctorId}?appointment_date=${newAppointmentDate}`);
      appointment_data.value = response.data.appointment_data;

      timeSlots.value.forEach(slot => {
        const isBooked = appointment_data.value.some(appointment => appointment.appointment_sl === slot.serialNumber);
        slot.isBooked = isBooked;
      });
    } catch (error) {
      console.error('Error fetching data:', error);
    }
  }
);
</script>
<style scoped>
.v-card {
  text-align: center;
}
</style>
