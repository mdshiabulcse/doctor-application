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
          title="Patient Create"
          prepend-icon="mdi-36px mdi-light mdi-clipboard-text-outline"
          rel="noopener"
          color="warning"
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
                    label="Source"
                    placeholder="Select..."
                  ></v-autocomplete>
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
                  <v-btn prepend-icon="mdi-trash-can-outline" @click="handleReset">
                    clear
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
import {computed, ref} from 'vue'
// import {useField, useForm} from 'vee-validate'
// import {VDatePicker} from "vuetify/labs/VDatePicker";


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
});



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
      console.log('this data log',submitForm.value.gender)
      const response = await fetch('your-api-endpoint', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(submitForm.value),
      });

      const data = await response.json();
      console.log('API Response:', data);

      // Optionally reset the form after successful submission
      handleReset();
    } catch (error) {
      console.error('Error submitting data to API:', error);
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
  submitForm.value = {
    name: '',
    phone: '',
    email: '',
    selectedDob: '',
    selectedAge: '',
  };
};

</script>
