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
                    v-model="name.value.value"
                    :counter="10"
                    :error-messages="name.errorMessage.value"
                    label="Name"
                  ></v-text-field>
                </v-col>

                <v-col cols="12">
                  <v-text-field
                    v-model="phone.value.value"
                    :counter="7"
                    :error-messages="phone.errorMessage.value"
                    label="Phone Number"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    v-model="email.value.value"
                    label="E-mail"
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="12" sm="7">
                  <v-text-field
                    label="Date Of Birth"
                    v-model="selectedDob"
                    clearable
                    type="date"
                    :max="new Date().toISOString().substr(0, 10)"
                    min="1930-01-01"
                    @input="calculateAgeFromDate"
                  >
                  </v-text-field>
                </v-col>
                <span>Or</span>
                <v-col cols="12" sm="4">
                  <v-text-field
                    label="Patient Age"
                    v-model="selectedAge"
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
                    required
                  ></v-autocomplete>
                </v-col>
                <v-col cols="12">
                  <v-btn prepend-icon="mdi-check-circle"
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
import {useField, useForm} from 'vee-validate'
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
const {handleSubmit, handleReset} = useForm({
  validationSchema: {
    name(value) {
      if (value?.length >= 2) return true

      return 'Name needs to be at least 2 characters.'
    },
    phone(value) {
      if (value?.length > 9 && /[0-9-]+/.test(value)) return true

      return 'Phone number needs to be at least 9 digits.'
    },
    email(value) {
      if (/^[a-z.-]+@[a-z.-]+\.[a-z]+$/i.test(value)) return true

      return 'Must be a valid e-mail.'
    },
  },
})
const name = useField('name')
const phone = useField('phone')
const email = useField('email')


const submit = handleSubmit(values => {
  alert(JSON.stringify(values, null, 2))
});
const selectedDob = ref('');
const selectedAge = ref('');

const calculateAgeFromDate = () => {
  const birthDate = new Date(selectedDob.value);
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
  selectedAge.value = age.toString();
};

const calculateDateFromAge = () => {
  const enteredAge = parseInt(selectedAge.value);
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

  selectedDob.value = birthDate.toISOString().substr(0, 10);
};


</script>
