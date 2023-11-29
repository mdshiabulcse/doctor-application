<template>
  <v-container>
    <v-row no-gutters>
      <v-col cols="12">
        <v-sheet class="pa-2 ma-2">
          <v-breadcrumbs :items="breadcrumbs">
            <template v-slot:prepend>
              <v-icon size="small" icon="$vuetify"></v-icon>
            </template>
          </v-breadcrumbs>
        </v-sheet>
      </v-col>
      <v-col cols="12">
        <v-data-table
          :headers="headers"
          :items="desserts"
          :search="search"
          class="elevation-1"
          item-value="id"
          :loading="loading"
        >
          <template v-slot:top>
            <v-text-field
              v-model="search"
              label="Search"
              class="pa-4"
            ></v-text-field>
          </template>
          <template v-slot:item.actions="{ item }">
            <v-icon
              size="small"
              class="me-2"
              color="green-darken-2"
              icon="mdi-file-document-edit-outline"
              @click="pathologyInvoice(item)"
            >

            </v-icon>
          </template>
        </v-data-table>
        <v-dialog v-model="deleteDialog" max-width="400">
          <v-card>
            <v-card-title class="headline">
              Delete Confirmation
            </v-card-title>
            <v-card-text>
              Are you sure you want to delete this item?
            </v-card-text>
            <v-card-actions>
              <v-btn color="primary" @click="deleteItem">Yes</v-btn>
              <v-btn color="red" @click="deleteDialog = false">No</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>

      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import {ref, computed, onMounted} from 'vue';
import axiosInstance from "@/services/axiosService";
import {useNotification} from "@/store/notification";
import {useAuth} from "@/store/auth";
// import {QuillEditor} from "@vueup/vue-quill";


const search = ref('');
const desserts = ref([]);
const hospital_data = ref([]);
const notify = useNotification();
const userData = useAuth();
const user_id = userData.user.data.id;
const dialogEdit = ref(false);
const deleteDialog = ref(false);
const loading = ref(false);


const breadcrumbs = computed(() => [
  {
    title: 'Home',
    disabled: false,
    href: '/',
  },
  {
    title: 'Doctors',
    disabled: false,
    href: '#',
  },
  {
    title: 'Doctor List',
    disabled: true,
    href: 'doctor-list',
  },
]);
const headers = computed(() => [
  {id: 'id', title: 'DID', align: 'start', key: 'doctor_id',},
  {id: 'id', title: 'Name', align: 'end', key: 'doctor_name',},
  {id: 'id', title: 'Doctor Fees', align: 'end', key: 'doctor_fees'},
  {id: 'id', title: 'Doctor Phone', align: 'end', key: 'dr_phone'},
  {id: 'id', title: 'Type', align: 'end', key: 'dr_type'},
  {id: 'id', title: 'Actions', key: 'actions', sortable: false},

]);


onMounted(() => {
  fetchDoctorData (); // Fetch data when the component is mounted
});

const fetchDoctorData = async () => {
  try {
    loading.value = true;
    const response = await axiosInstance('/admin/administrative/doctor-data'); // Replace with your API endpointa
    console.log('API Response:', response.data);
    desserts.value = response.data.doctor_data;
    hospital_data.value = response.data.hospital_data;
    loading.value = true;
  } catch (error) {
    console.error('Error fetching data:', error);
  } finally {
    loading.value = false; // Set loading to false after the request is complete
  }
};


const pathologyInvoice = (item) => {
  console.log(item)
};





</script>

