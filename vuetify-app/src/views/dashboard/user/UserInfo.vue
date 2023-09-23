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
        </v-sheet>
      </v-col>
      <v-col cols="12">
        <v-data-table
          :headers="headers"
          :items="desserts"
          :search="search"
          class="elevation-1"
          item-value="name"
        >
          <template v-slot:top>
            <v-text-field
              v-model="search"
              label="Search"
              class="pa-4"
            ></v-text-field>
          </template>
        </v-data-table>

      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import {ref, computed, onMounted} from 'vue';
import axiosInstance from "@/services/axiosService";

    const itemsPerPage = ref(5);
    const search = ref('');
    const desserts = ref([]);
    const groupRoles = ref([]);
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
      {title: 'Name', align: 'start', sortable: false, key: 'name',},
      {title: 'Mail', align: 'end', key: 'email',},
      {title: 'Phone', align: 'end', key: 'phone'},
      {title: 'Group', align: 'end', key: 'isVerified'},
      {title: 'Status', align: 'end', key: 'status'},

    ]);
    const dialog = ref(false);
    const formData = ref({
      name: '',
      email: '',
      phone: '',
      password: '',
      selectedGroupRoles:[]


    });

    const handleSubmit = async (event) => {
      event.preventDefault();
      try {
        const response = await axiosInstance.post('/admin/user-data', formData.value);
        console.log('Response Data:', response.data); // Log the response data
        if (response.status === 200) {
          console.log('Item created successfully'); // Log a success message
          // Handle success, e.g., show a success message or redirect
        } else {
          console.error('Error:', response.data); // Log the error response
          // Handle errors, e.g., display validation errors
        }
      } catch (error) {
        console.error('Network Error:', error); // Log network errors
        // Handle network errors
      }
    };


    onMounted(async () => {
      try {
        const response = await axiosInstance('/admin/user-data'); // Replace with your API endpoint
        desserts.value = response.data.user_list;
        groupRoles.value = response.data["group_role"].map(role => ({
          id: role.id,
          description: role.description,
        }));
        console.log('data', groupRoles.value)
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    });

</script>
