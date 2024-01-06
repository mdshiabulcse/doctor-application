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
                      <v-container>
                        <h2 class="mb-4">Booking System</h2>
                        <v-row>
                          <v-col v-for="(slot, index) in timeSlots" :key="index" cols="12" sm="6" md="4" lg="3">
                              <v-card
                                class="mx-auto mb-4"
                                :color="getCardColor(slot)"  height="100"
                                :title="slot.serialNumber"
                              >
                                <template v-slot:prepend>
                                  <v-icon icon="mdi-account" color="warning"></v-icon>
                                </template>
                                <template v-slot:append>
                                  <v-btn @click="bookSlot(slot)" icon="mdi-calendar" color="warning" size="small"></v-btn>
                                </template>
                                <v-card-text>{{ slot.startTime + '-' + slot.endTime  }}</v-card-text>
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
const timeSlotArrays = ref([]);
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
const appointmentSetting = async () => {
  try {
    const response = await axiosInstance.get(`/admin/appointment/appointment-setting`); // get patient details
    timeSlotArrays.value = response.data.appointment_settings;
    generateTimeSlots();
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

onMounted(() => {
  patientId.value = useRoute().params.patientId;
  fetchPatientDetails();
  appointmentSetting();
});

const timeSlots = ref([]);
let serialNumberCounter = 1;
const selectedSlot = ref(null);

onMounted(() => {
  generateTimeSlots();
});

const generateTimeSlots = () => {
  for (const timeSlot of timeSlotArrays.value) {
    const start = new Date(`2022-01-01 ${timeSlot.start_time}`);
    const end = new Date(`2022-01-01 ${timeSlot.end_time}`);
    let current = new Date(start);

    while (current < end) {
      const formattedTime = current.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
      const nextTime = new Date(current.getTime() + timeSlot.minute * 60000);
      const formattedNextTime = nextTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

      timeSlots.value.push({
        id: serialNumberCounter,
        time: formattedTime,
        serialNumber: serialNumberCounter,
        isBooked: false,
        slotNumber: timeSlot.slotNumber, // Replace with the correct property if needed
        startTime: formattedTime,
        endTime: formattedNextTime,
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

    clickedSlot.isBooked = true;
    showAlert(clickedSlot);
    selectedSlot.value = clickedSlot;
  }
};

const showAlert = (selectedSlot) => {
  console.log(`Slot booked: Serial Number - ${selectedSlot.serialNumber}, Start Time - ${selectedSlot.startTime}, End Time - ${selectedSlot.endTime}`);
  alert(`Slot booked: Start Time - ${selectedSlot.startTime}, End Time - ${selectedSlot.endTime}, Serial Number - ${selectedSlot.serialNumber}`);
};

const getCardColor = (slot) => {
  return slot.isBooked ? 'error' : 'primary';
};
</script>
<style scoped>
.v-card {
  text-align: center;
}
</style>
