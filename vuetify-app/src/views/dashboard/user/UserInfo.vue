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
                  Add User
                </v-btn>
              </template>

              <v-card>
                <v-toolbar
                  color="primary"
                  title="User Form"
                ></v-toolbar>
                <v-form @submit="handleSubmit">
                  <v-card-text>

                    <v-container>
                      <v-row>
                        <v-col
                          cols="12"
                        >
                          <v-text-field
                            label="Name*"
                            v-model="formData.name"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                          <v-text-field
                            label="Email*"
                            v-model="formData.email"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                          <v-text-field
                            label="Phone*"
                            v-model="formData.phone"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                          <v-text-field
                            v-model="formData.password"
                            label="Password*"
                            type="password"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col
                          cols="12"
                        >
                          <v-checkbox
                            v-for="role in groupRoles"
                            :key="role.id"
                            v-model="formData.selectedGroupRoles"
                            :label="role.description"
                            :value="role.id"
                          ></v-checkbox>
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
                  title="User Form"
                ></v-toolbar>
                <v-form @submit="handleSubmit">
                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col
                          cols="12"
                        >
                          <v-text-field
                            label="Name*"
                            v-model="editItmeData.name"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                          <v-text-field
                            label="Email*"
                            v-model="editItmeData.email"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                          <v-text-field
                            label="Phone*"
                            v-model="editItmeData.phone"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                          <v-text-field
                            v-model="editItmeData.password"
                            label="Password*"
                            type="password"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col
                          cols="12"
                        >
                          <v-checkbox
                            v-for="role in groupRoles"
                            :key="role.id"
                            v-model="editItmeData.selectedGroupRoles"
                            :label="role.description"
                            :value="role.id"
                          ></v-checkbox>
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

const itemsPerPage = ref(5);
const search = ref('');
const desserts = ref([]);
const groupRoles = ref([]);
const notify = useNotification();
const userData = useAuth();
const user_id = userData.user.data.id;
const breadcrumbs = computed(() => [
  {
    title: 'Home',
    disabled: false,
    href: '/',
  },
  {
    title: 'Administrative',
    disabled: false,
    href: '#',
  },
  {
    title: 'User List',
    disabled: true,
    href: 'user-list',
  },
]);
const headers = computed(() => [
  {id:'id', title: 'Name', align: 'start',key: 'name',},
  {id:'id', title: 'Mail', align: 'end', key: 'email',},
  {id:'id', title: 'Phone', align: 'end', key: 'phone'},
  {id:'id', title: 'Group', align: 'end', key: 'isVerified'},
  { id:'id', title: 'Actions', key: 'actions', sortable: false },

]);

const dialog = ref(false);
const dialogEdit = ref(false);
const deleteDialog = ref(false);
const formData = ref({
  name: '',
  email: '',
  phone: '',
  password: '',
  selectedGroupRoles: []
});
const editItmeData = ref({
  name: '',
  email: '',
  phone: '',
  password: '',
  selectedGroupRoles: []
});
onMounted(() => {
  fetchData(); // Fetch data when the component is mounted
});
const handleSubmit = async (event) => {
  event.preventDefault();
  try {
    const response = await axiosInstance.post('/admin/user-data', formData.value);
    if (response.data.message) {
      notify.Success(response.data.message);
      desserts.value.push(response.data.user_list);
      fetchData(); // Refresh the table data
    } else {
      notify.Error(response.data.message);
    }
  } catch (error) {
    notify.Error(error);
  }
};


const fetchData =async () => {
  try {
    const response = await axiosInstance('/admin/user-data?user_login_id='+user_id); // Replace with your API endpointa
    desserts.value = response.data.user_list;
    groupRoles.value = response.data["group_role"].map(role => ({
      id: role.id,
      description: role.description,
    }));
    console.log('data', groupRoles.value)
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};
let editItemId = null;
const editItem=(item)=>{
  editItemId= item.value;
  const itemToEdit = desserts.value.find((item) => item.id === editItemId);
  editItmeData.value = { ...itemToEdit }; // Populate editItmeData with the existing data
  dialogEdit.value = true;
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
    }
  } catch (error) {
    notify.Error(error);
  }

  const indexToDelete = desserts.value.findIndex((item) => item.id === deleteItemId);
  if (indexToDelete !== -1) {
    desserts.value.splice(indexToDelete, 1);
  }

  deleteDialog.value = false; // Close the delete confirmation modal
};

</script>
