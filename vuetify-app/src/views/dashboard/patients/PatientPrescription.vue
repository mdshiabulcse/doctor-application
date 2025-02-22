<template>
  <v-row no-gutters>
    <v-col>
      <v-col cols="12">
        <v-card
          :loading="loading"
        >
          <v-container>
            <v-row>
              <v-col cols="4">
                <v-autocomplete
                  density="compact"
                  v-model="submitForm.previous_prescription_id"
                  :items="previous_prescription"
                  label="Previous Prescription"
                  :item-title="formatPreviousPrescriptionTitle"
                  item-value="id"
                  @change="updateFormPreviousPrescription"
                  variant="outlined"
                ></v-autocomplete>
              </v-col>
              <v-col v-if="showActionButtons" cols="6">
                <v-btn size="small" class="ma-2" color="success" @click="submit">{{ buttonText }}</v-btn>
                <v-btn size="small" class="ma-2" prepend-icon="mdi-printer" color="primary" @click="PrintPrescription">
                  Print
                </v-btn>
              </v-col>
              <v-col v-else cols="6">
                <v-btn size="small" class="ma-2" color="primary" @click="createNewPrescription">Create as New</v-btn>
                <v-btn size="small" class="ma-2" prepend-icon="mdi-printer" color="info" @click="PrintPrescription">
                  Print
                </v-btn>
              </v-col>
            </v-row>
          </v-container>
        </v-card>
      </v-col>
      <v-row class="mx-auto">
        <v-col cols="3">
          <v-card>
            <v-card-text>
              <v-row>
                <v-col>
                  <v-card
                    class="mx-auto"
                    max-width="100%"
                    color="warning"
                  >
                    <v-card-item>
                      <div v-if="appointment_info">
                        <div class="text-overline">
                          <span>{{ appointment_info.patient_info.patient_name }}</span>
                        </div>
                        <div class="text-overline">
                          <span>{{ appointment_info.patient_id }}</span>
                        </div>
                        <div class="text-overline">
                          <span>Doctor: {{ appointment_info.doctor_info.doctor_name }}</span>
                        </div>
                      </div>
                    </v-card-item>
                  </v-card>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    density="compact"
                    v-model="submitForm.symptoms"
                    required
                    minlength="2"
                    label="Symptoms"
                    variant="outlined"
                    @input="fetchSymptomSuggestions"
                    @keydown.enter.prevent="saveSymptom"
                    @blur="hideSuggestions"
                  ></v-text-field>

                  <!-- Suggestions List (Hidden When Not Typing) -->
                  <v-list v-if="symptomSuggestions.length > 0">
                    <v-list-item
                      v-for="(suggestion, index) in symptomSuggestions"
                      :key="index"
                      @click="selectSymptom(suggestion)"
                    >
                      {{ suggestion }}
                    </v-list-item>
                  </v-list>
                </v-col>
                <v-col cols="12">
                  <v-autocomplete
                    density="compact"
                    v-model="submitForm.examination_data"
                    required
                    minlength="2"
                    label="Examination(Pathology)"
                    variant="outlined"
                  ></v-autocomplete>
                </v-col>
                <v-col cols="12">
                  <v-textarea
                    density="compact"
                    v-model="submitForm.advice_note"
                    required
                    label="Advice Note"
                    variant="outlined"
                  ></v-textarea>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    density="compact"
                    v-model="submitForm.followup_date"
                    label="Followup Date"
                    type="date"
                    variant="outlined"
                  >
                  </v-text-field>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col>
          <v-card>
            <v-card-text>
              <v-row>
                <v-col cols="2"></v-col>
                <v-col cols="3">
                  <v-btn @click="dialog = true" size="x-small" prepend-icon="mdi-plus" color="primary">Add</v-btn>
                </v-col>
                <v-col cols="2"></v-col>
                <v-col cols="3"></v-col>
                <v-col cols="2"></v-col>
              </v-row>
              <v-row v-for="(medicine, index) in submitForm.medicines" :key="index">
                <v-col cols="2">
                  <v-autocomplete
                    density="compact"
                    v-model="medicine.type"
                    required
                    label="Type"
                    :items="medicine_type"
                    size="small"
                    variant="outlined"
                  ></v-autocomplete>
                </v-col>
                <v-col cols="3">
                  <v-autocomplete
                    density="compact"
                    v-model="medicine.medicine_id"
                    :items="medicine_data"
                    required
                    label="Medicine"
                    item-value="id"
                    item-title="medicine_name"
                    variant="outlined"
                  ></v-autocomplete>
                </v-col>
                <v-col cols="2">
                  <v-text-field
                    density="compact"
                    v-model="medicine.duration"
                    required
                    label="Duration"
                    variant="outlined"
                  ></v-text-field>
                </v-col>
                <v-col cols="3">
                  <v-text-field
                    density="compact"
                    v-model="medicine.medicine_instruction"
                    required
                    label="Instruction"
                    variant="outlined"
                    @input="fetchInstructionSuggestions(medicine.medicine_instruction, index)"
                  ></v-text-field>

                  <!-- Suggestions List Below the Text Field -->
                  <v-list v-if="instructionSuggestions[index] && instructionSuggestions[index].length > 0" class="suggestion-list">
                    <v-list-item
                      v-for="(suggestion, idx) in instructionSuggestions[index]"
                      :key="idx"
                      @click="selectSuggestion(suggestion, index)"
                    >
                      <v-list-item-title>{{ suggestion }}</v-list-item-title>
                    </v-list-item>
                  </v-list>
                </v-col>


                <v-col v-if="submitForm.medicines.length > 1" cols="1">
                  <v-btn size="small" icon="mdi-minus" color="error" density="compact"
                         @click="removeMedicine(index)"></v-btn>
                </v-col>
                <v-col cols="1">
                  <v-btn size="small" icon="mdi-plus" color="warning" density="compact" @click="addMedicine"></v-btn>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-col>
    <div>
      <v-row
        justify="center"
      >
        <v-dialog
          v-model="dialog"
          width="250"

        >
          <v-card>
            <v-card-title>
              Add Medicine
            </v-card-title>
            <v-card-text>
              <v-select
                variant="outlined"
                density="compact"
                label="Type"
                :items="medicine_type"
                v-model="submitMedicine.medicine_type"
              >
              </v-select>
              <v-text-field
                variant="outlined"
                density="compact"
                label="Name"
                v-model="submitMedicine.medicine_name"
              >
              </v-text-field>
            </v-card-text>
            <v-card-actions>
              <v-btn @click="addMedicineData" color="primary" variant="text">
                Submit
              </v-btn>
              <v-btn
                color="warning"
                variant="text"
                @click="dialog = false"
              >
                Close
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>

      </v-row>
    </div>
  </v-row>
</template>
<script setup>
import {onMounted, ref, computed, watch,reactive} from 'vue';
import axiosInstance from "@/services/axiosService";
import {useNotification} from "@/store/notification";
import {useAuth} from "@/store/auth";
import {useRoute, useRouter} from "vue-router";
import {localUrl} from "@/services/globalUrlConfig";


const router = useRouter();
const loading = ref(true);
const dialog = ref(false);
const notify = useNotification();
const appointment_info = ref();
const prescription_data = ref([]);
const prescription_medicine_data = ref([]);
const previous_prescription = ref([]);
const suggest_prescription_data = ref([]);
const medicine_data = ref([]);
const user_id = useAuth().user.data.id;
const routeParams = useRoute().params;
const app_id = routeParams.app_id;
const patient_id = routeParams.pid;
const prescription_id = routeParams.prescriptionId !== 'null' ? parseInt(routeParams.prescriptionId) : 0;
const medicine_type = ref(['Tab', 'Cap', 'Drop', 'Syrup', 'Inj']);

const submitForm = ref({
  prescription_id: prescription_id,
  previous_prescription_id: '',
  app_id: app_id,
  user_id: user_id,
  doctor_id: '',
  symptoms: '',
  examination_data: '',
  advice_note: '',
  followup_date: '',
  medicines: [{
    type: '',
    medicine_id: '',
    duration: '',
    medicine_instruction: '',
  }],
});

const submitMedicine = ref({
  medicine_type: '',
  medicine_name: '',
});
onMounted(async () => {
  await fetchAppointmentInfo(); // Wait for the appointment info to be fetched
  await fetchMedicineData();
  await fetchPrescriptionData();
  await fetchPreviousPrescription();
  await prescriptionSuggestionData();
});

const fetchAppointmentInfo = async () => {
  try {
    const response = await axiosInstance(`/admin/appointment/patient-appointment-info/${app_id}`);
    appointment_info.value = response.data.appointment_info;
    submitForm.value.doctor_id = appointment_info.value.doctor_id;
    loading.value = false;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

const fetchPreviousPrescription = async () => {
  try {
    const response = await axiosInstance(`/admin/prescription/previous-prescription/${patient_id}`);
    previous_prescription.value = response.data.previous_prescription;
  } catch (error) {
    console.error('error ', error)
  }
}

const formatPreviousPrescriptionTitle = (item) => {
  // Concatenate create_date and id
  return `${item.create_date} - ${item.id}`;
};

watch(() => submitForm.value.previous_prescription_id, async (newValue, oldValue) => {
  if (newValue !== oldValue) {
    await updateFormPreviousPrescription(newValue);
  }
});

const updateFormPreviousPrescription = async (newValue) => {
  try {
    await router.push({path: `/patient-prescription/${app_id}/${appointment_info.value.patient_id}/${newValue}`});
    submitForm.value.prescription_id = newValue;
    await fetchPrescriptionData();
  } catch (error) {
    console.error('Error updating form from previous prescription:', error);
    notify.Error('Failed to update form from previous prescription.');
  }
};

// create ,update and Create as New function call here and return
const buttonText = computed(() => {
  if (prescription_id === 0) {
    return "Save"; // New prescription, show "Save" button
  } else {
    // Check if it's the same day as the prescription creation date
    const selectedPrescription = previous_prescription.value.find(prescription => prescription.id === submitForm.value.previous_prescription_id);
    const isTodayPrescription = selectedPrescription && new Date(selectedPrescription.create_date).toDateString() === new Date().toDateString();
    return isTodayPrescription ? "Update" : "Create as New"; // Show "Update" or "Create as New" button based on condition
  }
});

const showActionButtons = computed(() => {
  if (prescription_id === 0) {
    return true; // New prescription, show action buttons
  } else {
    const selectedPrescription = previous_prescription.value.find(prescription => prescription.id === submitForm.value.previous_prescription_id);
    const isTodayPrescription = selectedPrescription && new Date(selectedPrescription.create_date).toDateString() === new Date().toDateString();
    return isTodayPrescription;
  }
});

// this is prescription get function data

const fetchPrescriptionData = async () => {
  try {
    if (prescription_id !== 0) {
      const response = await axiosInstance.get(`/admin/prescription/prescription/${submitForm.value.prescription_id}`);
      prescription_data.value = response.data.prescription_data;
      prescription_medicine_data.value = response.data.prescription_medicine_data;

      submitForm.value.medicines = prescription_medicine_data.value;
      submitForm.value.symptoms = prescription_data.value.symptoms;
      submitForm.value.advice_note = prescription_data.value.advice_note;
      submitForm.value.followup_date = prescription_data.value.followup_date;
      submitForm.value.previous_prescription_id = prescription_data.value.id;

    } else {
      notify.Success('Prescription ID is null. Not fetching data.');
    }
  } catch (error) {
    notify.Error('Error fetching data:' + error);
  }
};

// this is medicine filed add , remove and medicine get function
const fetchMedicineData = async () => {
  try {
    const response = await axiosInstance(`/admin/prescription/medicine-data`);
    medicine_data.value = response.data.medicine_data;
  } catch (error) {
    notify.Error('Error fetching data:' + error);
  }
};

const addMedicine = () => {
  submitForm.value.medicines.push({
    type: '',
    medicine_id: '',
    duration: '',
    medicine_instruction: '',
  });
};

const removeMedicine = (index) => {
  submitForm.value.medicines.splice(index, 1);
};

// when you create and update new prescription today then work this

const submit = async () => {
  try {
    let response;
    const selectedPrescription = previous_prescription.value.find(prescription => prescription.id === submitForm.value.previous_prescription_id);
    const isTodayPrescription = selectedPrescription && new Date(selectedPrescription.create_date).toDateString() === new Date().toDateString();

    if (isTodayPrescription) {
      // Update logic
      response = await axiosInstance.post('/admin/prescription/prescription-update?patient_id=' + appointment_info.value.patient_id, submitForm.value);
    } else {
      // Save logic
      response = await axiosInstance.post('/admin/prescription/prescription?patient_id=' + appointment_info.value.patient_id, submitForm.value);
    }

    if (response.data.message) {
      const prescriptionId = response.data.prescription_id;
      // Navigate to the same route with different parameters
      await router.push({path: `/patient-prescription/${app_id}/${appointment_info.value.patient_id}/${prescriptionId}`});
      notify.Success(response.data.message);
      // Fetch updated data after the route change
      await fetchPrescriptionData();

      // Reload the page
      window.location.reload();
    } else {
      notify.Error(response.data.errors);
    }
  } catch (error) {
    notify.Error(error.response.data.errors);
  }
};

// when prescription not create today if you import old prescription and create old wise then this is work
const createNewPrescription = async () => {
  try {
    let response;

    // Save and create new prescription as old wise
    response = await axiosInstance.post('/admin/prescription/prescription?patient_id=' + appointment_info.value.patient_id, submitForm.value);

    if (response.data.message) {
      const prescriptionId = response.data.prescription_id;
      // Navigate to the same route with different parameters
      await router.push({path: `/patient-prescription/${app_id}/${appointment_info.value.patient_id}/${prescriptionId}`});
      notify.Success(response.data.message);
      // Fetch updated data after the route change
      await fetchPrescriptionData();

      // Reload the page
      window.location.reload();
    } else {
      notify.Error(response.data.errors);
    }
  } catch (error) {
    notify.Error(error.response.data.errors);
  }
};

// Doctor wise prescription suggests here

const prescriptionSuggestionData = async () => {
  try {
    const response = await axiosInstance(`/admin/prescription/prescription-suggestion-data/${submitForm.value.doctor_id}`);
    suggest_prescription_data.value = response.data.suggest_prescription_data;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
}

// add new medicine data

const addMedicineData = async () => {
  try {
    loading.value = true;
    const response = await axiosInstance.post('/admin/prescription/medicine-data-store', submitMedicine.value);
    if (response.data.message) {
      notify.Success(response.data.message);
      // Fetch updated data after the route change
      await fetchMedicineData();
      await fetchPrescriptionData();
      dialog.value = false;
      submitMedicine.value.medicine_type = '';
      submitMedicine.value.medicine_name = '';
      loading.value = false;
    } else {
      notify.Error(response.data.errors);
    }
  } catch (error) {
    notify.Error(error.response.data.errors);
  }
}
const PrintPrescription = () => {
  window.open(localUrl.value + `/print/prescription-print/${patient_id}/${prescription_id}`, '_blank');
};


const symptomSuggestions = ref([]); // Store API suggestions
let typingTimer = null; // Timer for managing API requests

// Fetch symptom suggestions while typing (without lodash)
const fetchSymptomSuggestions = () => {
  clearTimeout(typingTimer); // Clear previous timer
  typingTimer = setTimeout(async () => {
    if (submitForm.value.symptoms.length < 2) {
      symptomSuggestions.value = [];
      return;
    }

    try {

      const response = await axiosInstance(`/admin/prescription/prescription-suggestion-data/${submitForm.value.doctor_id}`,
        {params: {query: submitForm.value.symptoms}}
      );
      symptomSuggestions.value = response.data.suggest_prescription_data;
    } catch (error) {
      console.error("Error fetching suggestions:", error);
    }
  }, 300);
};

// Hide suggestions when user clicks outside
const hideSuggestions = () => {
  setTimeout(() => {
    symptomSuggestions.value = [];
  }, 200);
};

// Select symptom from suggestions
const selectSymptom = (suggestion) => {
  submitForm.value.symptoms = suggestion;
  symptomSuggestions.value = []; // Hide suggestions
};

// Track suggestions for each medicine instruction
const instructionSuggestions = reactive({});

// Fetch suggestions based on the input query
const fetchInstructionSuggestions = async (query, index) => {
  // Check if both text fields have values before fetching suggestions
  const medicineInstruction = submitForm.value.medicines[index].medicine_instruction;


  // If either field is empty, do not show suggestions
  if (!medicineInstruction) {
    instructionSuggestions[index] = []; // Clear suggestions if conditions are not met
    return; // Don't make the API call if one or both fields are empty
  }

  try {
    const response = await axiosInstance.get('/admin/prescription/medicine-suggestions', {
      params: { query }
    });

    // Assuming the API response is structured like: { suggestions: ["Take after meal"] }
    if (response.data && response.data.suggestions) {
      // Filter suggestions that match the query input
      instructionSuggestions[index] = response.data.suggestions.filter(suggestion =>
        suggestion.toLowerCase().includes(query.toLowerCase())
      ) || [];
    } else {
      instructionSuggestions[index] = [];
    }
  } catch (error) {
    console.error('Error fetching suggestions:', error);
    instructionSuggestions[index] = []; // Fallback to empty array in case of error
  }
};

// Select suggestion and assign it to the medicine instruction field
const selectSuggestion = (suggestion, index) => {
  submitForm.value.medicines[index].medicine_instruction = suggestion;
  instructionSuggestions[index] = []; // Clear suggestions after selection
};
</script>


