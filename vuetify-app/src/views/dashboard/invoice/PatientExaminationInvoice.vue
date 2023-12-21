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
            title="Examination Invoice"
            prepend-icon="mdi-36px mdi-light mdi-clipboard-text-outline"
            rel="noopener"
            color="warning"
          ></v-card>
          <v-col cols="12">
            <v-card>
              <v-container>
                <v-row align="center" justify="center">
                  <v-col cols="auto">
                    <v-btn prepend-icon="mdi-pencil-plus-outline" color="primary">edit</v-btn>
                  </v-col>
                  <v-col cols="auto">
                    <v-btn prepend-icon="mdi-medical-bag" color="warning" @click="patientExamination">Examination</v-btn>
                  </v-col>
                </v-row>
              </v-container>
            </v-card>
          </v-col>
         <v-col cols="12">
               <v-row>
                 <v-col cols="8">
                   <v-card class="mx-auto">
                     <v-container>
                       <form @submit.prevent="submit">
                         <v-row>
                           <v-col cols="6">
                             <v-autocomplete
                               v-model="name.value.value"
                               :counter="10"
                               :error-messages="name.errorMessage.value"
                               label="Examination Doctor"
                             ></v-autocomplete>
                           </v-col>
                           <v-col cols="6">
                             <v-autocomplete
                               v-model="phone.value.value"
                               :counter="7"
                               :error-messages="phone.errorMessage.value"
                               label="Refer Doctor"
                             ></v-autocomplete>
                           </v-col>

                           <v-text-field
                             v-model="email.value.value"
                             :error-messages="email.errorMessage.value"
                             label="E-mail"
                           ></v-text-field>

                           <v-select
                             v-model="select.value.value"
                             :items="items"
                             :error-messages="select.errorMessage.value"
                             label="Select"
                           ></v-select>

                           <v-checkbox
                             v-model="checkbox.value.value"
                             :error-messages="checkbox.errorMessage.value"
                             value="1"
                             label="Option"
                             type="checkbox"
                           ></v-checkbox>

                           <v-col cols="12">
                             <v-btn
                               class="me-4"
                               type="submit"
                             >
                               submit
                             </v-btn>

                             <v-btn @click="handleReset">
                               clear
                             </v-btn>
                           </v-col>
                         </v-row>
                       </form>
                     </v-container>
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
import { useField, useForm } from 'vee-validate'

const notify = useNotification();
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
    title: 'Patients Details',
    disabled: false,
    href: '#',
  },
]);


onMounted(() => {
  patientId.value = useRoute().params.patientId;
  fetchPatientDetails();
});

const fetchPatientDetails = async () => {
  try {
    const response = await axiosInstance.get(`/admin/patients/patients/${patientId.value}`); // get patient details
    patient_details.value = response.data.patient_details;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};
const patientExamination = () => {
  router.push({ path: `/patient-examination-invoice/${patient_details.value.patient_id}` });
};

const { handleSubmit, handleReset } = useForm({
  validationSchema: {
    name (value) {
      if (value?.length >= 2) return true

      return 'Name needs to be at least 2 characters.'
    },
    phone (value) {
      if (value?.length > 9 && /[0-9-]+/.test(value)) return true

      return 'Phone number needs to be at least 9 digits.'
    },
    email (value) {
      if (/^[a-z.-]+@[a-z.-]+\.[a-z]+$/i.test(value)) return true

      return 'Must be a valid e-mail.'
    },
    select (value) {
      if (value) return true

      return 'Select an item.'
    },
    checkbox (value) {
      if (value === '1') return true

      return 'Must be checked.'
    },
  },
})
const name = useField('name')
const phone = useField('phone')
const email = useField('email')
const select = useField('select')
const checkbox = useField('checkbox')

const items = ref([
  'Item 1',
  'Item 2',
  'Item 3',
  'Item 4',
])

const submit = handleSubmit(values => {
  alert(JSON.stringify(values, null, 2))
})
</script>
