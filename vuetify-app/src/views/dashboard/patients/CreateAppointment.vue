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
                              <v-card :color="slot.isBooked ? 'error' : 'primary'" class="mb-4" height="150">
                                <v-card-title v-if="slot.isBooked">
                                  Booked Slot
                                </v-card-title>
                                <v-card-title v-else>
                                  Available Slot
                                </v-card-title>
                                <v-card-subtitle>
                                  {{ slot.isBooked ? 'Start Time: ' + slot.startTime + ' - End Time: ' + slot.endTime : 'Serial Number: ' + slot.serialNumber }}
                                </v-card-subtitle>
                                <v-card-text v-if="slot.isBooked">
                                  <div>Slot Number: {{ slot.slotNumber }}</div>
                                </v-card-text>
                                <v-card-actions>
                                  <v-btn v-if="!slot.isBooked" @click="bookSlot(slot)">Book Slot</v-btn>
                                  <span v-else>Booked</span>
                                </v-card-actions>
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

const timeSlots = ref([]);

onMounted(() => {
  generateTimeSlots();
});

const generateTimeSlots = () => {
  const timeSlotArrays = [
    { start: '09:00', end: '11:00', slotNumber: 6 },
    { start: '12:00', end: '14:00', slotNumber: 7 },
    { start: '15:00', end: '17:00', slotNumber: 9 },
    // Add more arrays as needed
  ];

  for (const timeSlot of timeSlotArrays) {
    const start = new Date(`2022-01-01 ${timeSlot.start}`);
    const end = new Date(`2022-01-01 ${timeSlot.end}`);
    let current = new Date(start);

    while (current <= end) {
      const formattedTime = current.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
      const nextTime = new Date(current.getTime() + 60 * 60000);
      const formattedNextTime = nextTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

      timeSlots.value.push({
        id: timeSlot.slotNumber,
        time: formattedTime,
        serialNumber: timeSlot.slotNumber,
        isBooked: false,
        slotNumber: timeSlot.slotNumber,
        startTime: formattedTime,
        endTime: formattedNextTime,
      });

      current = nextTime;
    }
  }
};

const bookSlot = (selectedSlot) => {
  // Simulate a booking process
  selectedSlot.isBooked = true;
  showAlert(selectedSlot);
};

const showAlert = (selectedSlot) => {
  alert(`Slot booked: Start Time - ${selectedSlot.startTime}, End Time - ${selectedSlot.endTime}, Serial Number - ${selectedSlot.serialNumber}`);
};
</script>
<style scoped>
.v-card {
  text-align: center;
}
</style>
