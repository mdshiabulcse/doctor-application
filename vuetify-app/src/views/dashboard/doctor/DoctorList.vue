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
          <v-row>
            <v-dialog
                v-model="dialog"
                persistent
                width="500"
            >
              <template v-slot:activator="{ props }">
                <v-btn color="primary" v-bind="props" class="ma-2">
                  Add Source
                </v-btn>
              </template>

              <v-card>
                <v-toolbar
                    color="primary"
                    title="Source Create"
                ></v-toolbar>
                <v-form @submit="handleSubmit">
                  <v-card-text>

                    <v-container>
                      <v-row>
                        <v-col
                            cols="12"
                        >
                          <v-text-field
                              label="Doctor Name*"
                              v-model="formData.doctor_name"
                              required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                          <v-text-field
                              label="Doctor Fees*"
                              v-model="formData.doctor_fees"
                              required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                          <v-text-field
                              label="Doctor Phone*"
                              v-model="formData.dr_phone"
                              required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                          <v-autocomplete
                              label="Doctor Type*"
                              v-model="formData.dr_type"
                              :items="['Special', 'Referral']"
                              required
                          ></v-autocomplete>
                        </v-col>

                        <v-col cols="12">
                          <v-autocomplete
                              label="Select"
                              v-model="formData.hospital_name"
                              :items="hospital_data"
                              color="blue-grey-lighten-2"
                              item-value="id"
                              item-title="source_name"
                          >
                          </v-autocomplete>
                        </v-col>
                        <v-col cols="12">
                          <QuillEditor v-model:content="formData.doctor_details"  />
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="blue-darken-1"
                        variant="text"
                        @click="dialog = false"
                    >
                      Close
                    </v-btn>
                    <v-btn
                        color="blue-darken-1"
                        variant="text"
                        @click="dialog = false"
                        type="submit"
                    >
                      Save
                    </v-btn>
                  </v-card-actions>
                </v-form>
              </v-card>

            </v-dialog>
          </v-row>
          <v-row>
            <v-dialog
                v-model="dialogEdit"
                width="500"
            >
              <v-card>
                <v-toolbar
                    color="primary"
                    title="Edit Source Info"
                ></v-toolbar>
                <v-form @submit="handleSubmit">
                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col
                            cols="12"
                        >
                          <v-text-field
                              label="Source Name*"
                              v-model="editItmeData.source_name"
                              required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                          <v-text-field
                              label="Source Location*"
                              v-model="editItmeData.source_location"
                              required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                          <v-text-field
                              label="Phone*"
                              v-model="editItmeData.source_phone"
                              required
                          ></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="blue-darken-1"
                        variant="text"
                        @click="dialogEdit = false"
                    >
                      Close
                    </v-btn>
                    <v-btn
                        color="blue-darken-1"
                        variant="text"
                        @click="dialogEdit = false"
                        type="submit"
                    >
                      Save
                    </v-btn>
                  </v-card-actions>
                </v-form>
              </v-card>

            </v-dialog>
          </v-row>
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
                @click="editItem(item)"
            >
              mdi-pencil
            </v-icon>
            <v-icon
                size="small"
                @click="showDeleteModal(item)"
            >
              mdi-delete
            </v-icon>
            <v-switch
                :color="item.columns.status === 1 ? 'success' : ''"
                :label="item.columns.status === 1 ? 'Active' : 'Inactive'"
                :model-value="item.columns.status === 1 ? true : false"
                hide-details
                @change="toggleStatus(item, item.columns.status)"
            ></v-switch>
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
import {QuillEditor} from "@vueup/vue-quill";


const search = ref('');
const desserts = ref([]);
const hospital_data = ref([]);
const notify = useNotification();
const userData = useAuth();
const user_id = userData.user.data.id;
const dialog = ref(false);
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
  {id: 'id', title: 'Status', align: 'end', key: 'status'},
  {id: 'id', title: 'Actions', key: 'actions', sortable: false},

]);

const formData = ref({
  doctor_name: '',
  doctor_fees: '',
  dr_phone: '',
  dr_type: '',
  hospital_name: '',
  doctor_details: '',
});

const editItmeData = ref({
  source_name: '',
  source_location: '',
  source_phone: '',
});

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


let editItemId = null;
const editItem = (item) => {
  editItemId = item.value;
  const itemToEdit = desserts.value.find((item) => item.id === editItemId);
  editItmeData.value = {...itemToEdit}; // Populate editItmeData with the existing data
  dialogEdit.value = true;
};
const handleSubmit = async (event) => {
  event.preventDefault();
  try {
    if (editItemId) {
      // If editItemId is present, update the user data
      const response = await axiosInstance.put(`/admin/administrative/patient-source/${editItemId}`, editItmeData.value);
      if (response.data.message) {
        notify.Success(response.data.message);
        // Find the edited item and update its data
        const editedItemIndex = desserts.value.findIndex((item) => item.id === editItemId);
        if (editedItemIndex !== -1) {
          desserts.value[editedItemIndex] = response.data.patient_source;
        }
        // Reset editItemId after successful update

        editItemId = null;
        dialogEdit.value = false;
        loading.value = true;
        fetchDoctorData();
        loading.value = false;
      } else {
        notify.Error(response.data.message);
      }
    } else {

      console.log('API submit:', formData.value)
      const response = await axiosInstance.post('/admin/administrative/save-doctor?user_id=' + user_id, formData.value);
      if (response.data.message) {
        notify.Success(response.data.message);
        desserts.value.push(response.data.patient_source);
        loading.value = true;
        fetchDoctorData();
        loading.value = false;
      } else {
        notify.Error(response.data.message);
      }
    }
  } catch (error) {
    notify.Error(error);
  }
};

let deleteItemId = null;
const showDeleteModal = (item) => {
  deleteItemId = item.value;
  deleteDialog.value = true;
};

const deleteItem = async () => {
  try {
    const response = await axiosInstance.delete(`/admin/user-data/${deleteItemId}`);
    if (response.data.message) {
      notify.Success(response.data.message);
    } else {
      notify.Error(response.data.message);
      loading.value = true;
      fetchDoctorData();
      loading.value = false;
    }
  } catch (error) {
    notify.Error(error);
  }

  const indexToDelete = desserts.value.findIndex((item) => item.id === deleteItemId);
  if (indexToDelete !== -1) {
    desserts.value.splice(indexToDelete, 1);
    loading.value = true;
    fetchDoctorData();
    loading.value = false;
  }

  deleteDialog.value = false; // Close the delete confirmation modal
};


const toggleStatus = async (item) => {
  try {
    const newStatus = item.selectable.status === 1 ? 0 : 1;
    const response = await axiosInstance.get(`/admin/administrative/change-status/${item.value}?status=${newStatus}`);
    if (response.data.message) {
      notify.Success(response.data.message);
    } else {
      notify.Error(response.data.message);
    }
  } catch (error) {
    notify.Error(error);
  } finally {
    // Refresh the table data after updating the status
    loading.value = true;
    fetchDoctorData();
    loading.value = false;
  }
};

</script>

