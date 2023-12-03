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
        <form @submit.prevent="submit">
          <v-text-field
            v-model="name.value.value"
            :counter="10"
            :error-messages="name.errorMessage.value"
            label="Name"
          ></v-text-field>

          <v-text-field
            v-model="phone.value.value"
            :counter="7"
            :error-messages="phone.errorMessage.value"
            label="Phone Number"
          ></v-text-field>

          <v-text-field
            v-model="email.value.value"
            label="E-mail"
          ></v-text-field>
          <v-text-field
            v-model="selectedDate"
            label="Select Date"
            prepend-icon="mdi-calendar"
            readonly
          ></v-text-field>

          <v-date-picker v-model="selectedDate" ></v-date-picker>

          <v-select
            v-model="select.value.value"
            :items="items"
            :error-messages="select.errorMessage.value"
            label="Reference"
          ></v-select>

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
        </form>
      </v-col>
      <v-col cols="5">

      </v-col>
    </v-row>
  </v-container>
</template>
<script setup>
import {computed, ref} from 'vue'
import {useField, useForm} from 'vee-validate'

const breadcrumbs = computed(() => [
  {
    title: 'Home',
    disabled: false,
    href: '/',
  },
  {
    title: 'Patients',
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
    select(value) {
      if (value) return true

      return 'Select an item.'
    },
    checkbox(value) {
      if (value === '1') return true

      return 'Must be checked.'
    },
  },
})
const name = useField('name')
const phone = useField('phone')
const email = useField('email')
const select = useField('select')


const items = ref([
  'Item 1',
  'Item 2',
  'Item 3',
  'Item 4',
])

const submit = handleSubmit(values => {
  alert(JSON.stringify(values, null, 2))
})
const selectedDate = ref(null);

const openDatePicker = () => {
  // Open the date picker when the text field is clicked
  // You can customize the behavior based on your requirements
  selectedDate.value = true;
};

const closeDatePicker = () => {
  // Close the date picker when a date is selected
  // You can customize the behavior based on your requirements
  selectedDate.value = false;
};
</script>
