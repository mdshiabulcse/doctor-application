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
              <v-form @submit="handleSubmit">
              <v-card>
                <v-toolbar
                  color="primary"
                  title="User Form"
                ></v-toolbar>
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
                          <v-select
                            v-model="value"
                            :items="items"
                            label="Select Item"
                            multiple
                          >
                            <template v-slot:selection="{ item, index }">
                              <v-chip v-if="index < 2">
                                <span>{{ item.title }}</span>
                              </v-chip>
                              <span
                                v-if="index === 2"
                                class="text-grey text-caption align-self-center"
                              >
                              (+{{ value.length - 2 }} others)
                            </span>
                            </template>
                          </v-select>
                        </v-col>
                      </v-row>
                    </v-container>


                  <small>*indicates required field</small>
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
              </v-card>
              </v-form>
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

<script>
import {ref, computed, onMounted} from 'vue';
import axiosInstance from "@/services/axiosService";


export default {
  setup() {
    const itemsPerPage = ref(5);
    const search = ref('');
    const desserts = ref([]);
    const breadcrumbs = computed(() => [
      {
        title: 'Dashboard',
        disabled: false,
        href: 'breadcrumbs_dashboard',
      },
      {
        title: 'Link 1',
        disabled: false,
        href: 'breadcrumbs_link_1',
      },
      {
        title: 'Link 2',
        disabled: true,
        href: 'breadcrumbs_link_2',
      },
    ]);
    const headers = computed(() => [
      {title: 'Name', align: 'start', sortable: false, key: 'doctor_name',},
      {title: 'Mail', align: 'end', key: 'doctor_id',},
      {title: 'Phone', align: 'end', key: 'hospital_name'},
      {title: 'Group', align: 'end', key: 'hospital_name'},

    ]);
    const dialog = ref(false);
    const items=ref( ['foo', 'bar', 'fizz', 'buzz', 'fizzbuzz', 'foobar']);
    const value= ref(['foo', 'bar', 'fizz']);

    const formData = ref({
      name: '',
      email: '',
      phone: '',
      password: '',

    });

    const handleSubmit = async (event) => {
      event.preventDefault();

      console.log('Form Data:', formData.value); // Log the form data

      try {
        const response = await axiosInstance.post('/admin/user-data', formData.value);

        console.log('Response Data:', response.data); // Log the response data

        if (response.status === 201) {
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

    const fetchPosts = async () => {
      try {
        const response = await axiosInstance('/admin/doctor-data'); // Replace with your API endpoint
        desserts.value = response.data.doctors;
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    };
    onMounted(() => {
      fetchPosts();
    });
    return {
      search,
      desserts,
      headers,
      breadcrumbs,
      itemsPerPage,
      dialog,
      items,
      value,
      formData,
      handleSubmit
    };
  },
};
</script>
