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
          :title="patient_details.patient_id ? patient_details.patient_id : 'Patient Create'"
          prepend-icon="mdi-36px mdi-light mdi-clipboard-text-outline"
          rel="noopener"
          color="info"
        ></v-card>
        <v-card>
          <v-card-text>
            <form @submit.prevent="submit">
              <v-row>
                <v-col cols="12">
                  <v-text-field
                    v-model="submitForm.name"
                    required
                    minlength="2"
                    label="Name"
                    :rules="nameRules"
                  ></v-text-field>
                </v-col>

                <v-col cols="12">
                  <v-text-field
                    v-model="submitForm.phone"
                    required
                    pattern="\d{11,}"
                    label="Phone Number"
                    :rules="phoneRules"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    v-model="submitForm.email"
                    label="E-mail"
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="12" sm="7">
                  <v-text-field
                    label="Date Of Birth"
                    v-model="submitForm.selectedDob"
                    clearable
                    type="date"
                    :max="new Date().toISOString().substr(0, 10)"
                    min="1920-01-01"
                    @input="calculateAgeFromDate"
                    :rules="dobRules"
                    required
                  >
                  </v-text-field>
                </v-col>
                <span>Or</span>
                <v-col cols="12" sm="4">
                  <v-text-field
                    label="Patient Age"
                    v-model="submitForm.selectedAge"
                    clearable
                    @input="calculateDateFromAge"
                    :rules="ageRules"
                    required
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-radio-group
                    inline
                    v-model="submitForm.gender"
                    required
                    :rules="genderRules"
                  >
                    <template v-slot:label>
                      <div>Select <strong>Gender</strong></div>
                    </template>
                    <v-radio
                      label="Male"
                      value="Male"
                    ></v-radio>
                    <v-radio
                      label="Female"
                      value="Female"
                    ></v-radio>
                    <v-radio
                      label="Other"
                      value="Other"
                    ></v-radio>
                  </v-radio-group>
                </v-col>
                <v-col cols="12">
                  <v-autocomplete
                    label="Patient Source"
                    v-model="submitForm.source_name"
                    :items="patient_sources"
                    color="blue-grey-lighten-2"
                    item-value="id"
                    item-title="source_name"
                  >
                  </v-autocomplete>
                </v-col>
                <v-col cols="12">
                  <v-btn
                    prepend-icon="mdi-check-circle"
                    class="me-4"
                    type="submit"
                    color="primary"
                  >
                    submit
                  </v-btn>
                  <v-btn :class="patient_details.patient_id ? 'd-none':''" prepend-icon="mdi-trash-can-outline" @click="handleReset">
                    Clear
                  </v-btn>
                </v-col>
              </v-row>
            </form>
          </v-card-text>
        </v-card>
      </v-col>

    </v-row>
  </v-container>
</template>
<script setup>
import {computed, onMounted, ref,watch} from 'vue'
import axiosInstance from "@/services/axiosService";
import {useNotification} from "@/store/notification";
import {useAuth} from "@/store/auth";
import {useRoute, useRouter} from "vue-router";

const notify = useNotification();
const userData = useAuth();
const user_id = userData.user.data.id;
const patient_sources=ref([]);
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
    title: 'Patients Create',
    disabled: false,
    href: '#',
  },
]);

const submitForm=ref({
  name:'',
  phone:'',
  email:'',
  selectedDob:'',
  selectedAge:'',
  gender:'',
  source_name:'',

});




onMounted(() => {
  patientId.value = useRoute().params.patientId;
  fetchPatientSources (); // Fetch data when the component is mounted
});


const fetchPatientSources = async () => {
  try {
    const response = await axiosInstance('/admin/patients/patient-sources'); // Replace with your API endpointa
    patient_sources.value = response.data.patient_sources;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};


const nameRules = [
  (v) => !!v || 'Name is required',
  (v) => (v && v.length >= 3) || 'Name must be at least 3 characters',
];

const phoneRules = [
  (v) => !!v || 'Phone Number is required',
  (v) => (v && /^\d{11,}$/.test(v)) || 'Invalid Phone Number',
];
const dobRules = [
  (v) => !!v || 'Date of Birth is required',
];
const ageRules = [
  (v) => !!v || 'Age is required',
  (v) => !isNaN(parseInt(v)) || 'Age must be a valid number',
];
const genderRules = [
  (v) => !!v || 'Gender is required',
];

const initializeForm = () => {
  if (patientId.value) {
    fetchPatientDetails();
  } else {
    handleReset();
  }
};

onMounted(initializeForm);

const fetchPatientDetails = async () => {
  try {
    const response = await axiosInstance.get(`/admin/patients/patients/${patientId.value}`);
    patient_details.value = response.data.patient_details;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};



const submit = async () => {
  const isFormValid = await validateForm();

  if (isFormValid) {
    try {
      // Check if it's an edit or create operation
      if (patientId.value) {
        // It's an edit operation
        const response = await axiosInstance.post(`/admin/patients/patients-update/${patientId.value}?user_id=${user_id}`, submitForm.value);
        if (response.data.message) {
          notify.Success(response.data.message);
          router.push({ path: `/patient-details/${response.data.patient_id}` });
        } else {
          notify.Error(response.data.errors);
        }
      } else {
        // It's a create operation
        const response = await axiosInstance.post(`/admin/patients/patients?user_id=${user_id}`, submitForm.value);
        if (response.data.message) {
          notify.Success(response.data.message);
          router.push({ path: `/patient-details/${response.data.patient_id}` });
        } else {
          notify.Error(response.data.errors);
        }
      }
    } catch (error) {
      notify.Error(error.response.data.errors);
    }
  } else {
    console.error('Form validation failed. Please check the fields.');
  }
};

// ===============Form Validation Code Start Here===============  //

const validateForm = async () => {
  const results = await Promise.all([
    ...nameRules.map((rule) => rule(submitForm.value.name)),
    ...phoneRules.map((rule) => rule(submitForm.value.phone)),
    ...dobRules.map((rule) => rule(submitForm.value.selectedDob)),
    ...ageRules.map((rule) => rule(submitForm.value.selectedAge)),
    ...genderRules.map((rule) => rule(submitForm.value.gender)),

  ]);

  // Check if all validation results are truthy (indicating valid)
  return results.every((result) => result === true);
};
// Invalid date entered for Date of Birth
const calculateAgeFromDate = () => {
  const birthDate = new Date(submitForm.value.selectedDob);
  if (isNaN(birthDate.getTime())) {
    return;
  }

  // Date of Birth cannot be in the future
  if (birthDate > new Date()) {
    return;
  }

  const today = new Date();
  const age = today.getFullYear() - birthDate.getFullYear();
  submitForm.value.selectedAge = age.toString();
};

const calculateDateFromAge = () => {
  const enteredAge = parseInt(submitForm.value.selectedAge);
  if (isNaN(enteredAge) || enteredAge < 0) {
    return;
  }

  const today = new Date();
  const birthYear = today.getFullYear() - enteredAge;
  const birthDate = new Date(birthYear, today.getMonth(), today.getDate());

  if (birthDate > today) {
    console.error('Date of Birth calculated from age cannot be in the future');
    return;
  }

  submitForm.value.selectedDob = birthDate.toISOString().substr(0, 10);
};

// ===============Form Validation Code End Here===============  //

const updateSubmitForm = () => {
  watch(patient_details, (newValue) => {
    const patientDetails = newValue;

    if (patientDetails) {

      let sourceName = '';
      if (patientDetails.hospital_name !== null) {
        const selectedSources = patient_sources.value.filter(source => patientDetails.hospital_name.includes(source.id));
        sourceName = selectedSources.map(source => source.source_name).join(', ');
      }

      submitForm.value = {
        name: patientDetails.patient_name,
        phone: patientDetails.patient_phone,
        email: patientDetails.patient_email,
        selectedDob: patientDetails.patient_dob,
        selectedAge: patientDetails.patient_age,
        gender: patientDetails.gender,
        source_name: sourceName,
      };
    }
  });
};

updateSubmitForm();

const handleReset = () => {
  // Reset the form fields
  submitForm.value = {
    name: '',
    phone: '',
    email: '',
    selectedDob: '',
    selectedAge: '',
    source_name: '',
  };
};
</script>
