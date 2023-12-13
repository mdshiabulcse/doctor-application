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
                    :error-messages="validationErrors.name"
                    label="Name"
                  ></v-text-field>
                </v-col>

                <v-col cols="12">
                  <v-text-field
                    v-model="submitForm.phone"
                    required
                    pattern="\d{11,}"
                    :error-messages="validationErrors.phone"
                    label="Phone Number"
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
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-radio-group
                    inline
                  >
                    <v-radio
                      label="Male"
                      value="Male"
                    ></v-radio>
                    <v-radio
                      label="Female"
                      value="Female"
                    ></v-radio>
                    <v-radio
                      label="Others"
                      value="Others"
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
});


const validationErrors = ref({
  name: '',
  phone: '',
  // Add other fields as needed
});

const submit = async () => {
  // Reset previous validation errors
  const form = document.forms[0];

  // Use reportValidity to display validation messages
  if (!form.reportValidity()) {
    console.log('Form validation failed. Please check the fields.');
    return;
  }

  // If validation passes, proceed with API submission
  try {
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
