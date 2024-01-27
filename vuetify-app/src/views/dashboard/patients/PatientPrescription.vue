<template>
     <v-row>
       <v-col>
         <v-col cols="12">
           <v-card>
             <v-container>
               <v-row >
                 <v-col cols="3">
                   <v-autocomplete

                     :items="special_doctor"
                     color="blue-grey-lighten-2"
                     item-value="id"
                     item-title="doctor_name"
                     label="Appointment Doctor"
                     @change="onDoctorChange"
                   ></v-autocomplete>
                 </v-col>
                 <v-col cols="3">
                   <v-text-field
                     type="date"
                     label="Appointment Date"
                     hint="MM/DD/YYYY format"
                     prepend-icon="mdi-calendar"
                     required
                     @change="onDateChange"
                   >
                   </v-text-field>
                 </v-col>
                 <v-col cols="2">
                   <v-btn color="success"> Save</v-btn>
                 </v-col>
                 <v-col cols="2">
                   <v-btn prepend-icon="mdi-printer" color="primary"> Print</v-btn>
                 </v-col>
               </v-row>
             </v-container>
           </v-card>
         </v-col>
           <v-row>
                  <v-col cols="3">
                    <v-card class="pa-2 ma-2">
                      <v-card-text>
                          <v-row>
                            <v-col>
                              <v-card
                                class="mx-auto"
                                max-width="100%"
                                color="primary"
                                variant="flat"
                              >
                                <v-card-item>
                                  <div>
                                    <div class="text-overline mb-1">
                                      <span>Name:</span>
                                    </div>
                                    <div class="text-overline mb-1">
                                      <span>ID:</span>
                                    </div>
                                    <div class="text-overline mb-1">
                                      <span>Doctor:</span>
                                    </div>
                                    <div class="text-overline mb-1">
                                      <span>Refer Doctor:</span>
                                    </div>
                                  </div>
                                </v-card-item>
                              </v-card>
                            </v-col>
                            <v-col cols="12">
                              <v-text-field
                                v-model="submitForm.name"
                                required
                                minlength="2"
                                label="Symptoms"
                              ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                              <v-autocomplete
                                v-model="submitForm.name"
                                required
                                minlength="2"
                                label="Examination(Pathology)"
                              ></v-autocomplete>
                            </v-col>
                            <v-col cols="12">
                              <v-textarea
                                v-model="submitForm.phone"
                                required
                                label="Advice Note"
                              ></v-textarea>
                            </v-col>
                            <v-col cols="12">
                              <v-text-field
                                v-model="submitForm.email"
                                label="Followup Date"
                                type="date"
                              >
                              </v-text-field>
                            </v-col>
                          </v-row>
                      </v-card-text>
                    </v-card>
                  </v-col>
                  <v-col>
                    <v-card class="pa-2 ma-2">
                      <v-card-text>

                          <v-row>
                            <v-col cols="1">
                              <v-autocomplete
                                v-model="submitForm.name"
                                required
                                label="Type"
                              ></v-autocomplete>
                            </v-col>
                            <v-col cols="3">
                              <v-autocomplete
                                v-model="submitForm.name"
                                required
                                label="Medicine"
                              ></v-autocomplete>
                            </v-col>
                            <v-col cols="2">
                              <v-autocomplete
                                v-model="submitForm.name"
                                required
                                label="Duration"
                              ></v-autocomplete>
                            </v-col>
                            <v-col cols="3">
                              <v-autocomplete
                                v-model="submitForm.name"
                                required
                                label="Instruction"
                              ></v-autocomplete>
                            </v-col>
                            <v-col cols="2">
                              <v-btn prepend-icon="mdi-plus" color="primary"></v-btn>
                            </v-col>
                          </v-row>

                      </v-card-text>
                    </v-card>
                  </v-col>
           </v-row>
       </v-col>
     </v-row>
</template>
<script setup>
import {computed, onMounted, ref} from 'vue'
import axiosInstance from "@/services/axiosService";
import {useNotification} from "@/store/notification";
import {useAuth} from "@/store/auth";

const notify = useNotification();
const userData = useAuth();
const user_id = userData.user.data.id;
const patient_sources=ref([]);

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
  (v) => (v && v.length >= 2) || 'Name must be at least 2 characters',
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



// Add rules for other form fields

const submit = async () => {

  // Validate form fields
  const isFormValid = await validateForm();

  if (isFormValid) {
    // Proceed with API submission
    try {
      const response = await axiosInstance.post('/admin/patients/patients?user_id=' + user_id, submitForm.value);
      if (response.data.message) {
        notify.Success(response.data.message);
      } else {
        notify.Error(response.data.errors);
      }
    } catch (error) {
      notify.Error(error.response.data.errors);
    }
  } else {
    console.error('Form validation failed. Please check the fields.');
  }
};

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
const calculateAgeFromDate = () => {
  console.log("log date of birth",submitForm.value.selectedDob)
  const birthDate = new Date(submitForm.value.selectedDob);
  if (isNaN(birthDate.getTime())) {
    console.error('Invalid date entered for Date of Birth');
    return;
  }

  if (birthDate > new Date()) {
    console.error('Date of Birth cannot be in the future');
    return;
  }

  const today = new Date();
  const age = today.getFullYear() - birthDate.getFullYear();
  submitForm.value.selectedAge = age.toString();
};

const calculateDateFromAge = () => {
  console.log("log date of age",submitForm.value.selectedAge)
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




const handleReset = () => {
  // Reset the form fields
  submitForm.value.source_name = {
    name: '',
    phone: '',
    email: '',
    selectedDob: '',
    selectedAge: '',
    source_name: '',
  };
};

</script>
