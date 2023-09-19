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
          <v-row >
            <v-col cols="auto">
              <v-dialog
                transition="dialog-top-transition"
                width="auto"
              >
                <template v-slot:activator="{ props }">
                  <v-btn
                    color="primary"
                    v-bind="props"
                  >Add User</v-btn>
                </template>
                <template v-slot:default="{ isActive }">
                  <v-card>
                    <v-toolbar
                      color="primary"
                      title="User Form"
                    ></v-toolbar>
                    <v-sheet width="500" class="mx-auto">
                    <form >
                      <v-text-field
                        label="Name"
                      ></v-text-field>

                      <v-text-field
                        label="Phone Number"
                      ></v-text-field>

                      <v-text-field
                        label="E-mail"
                      ></v-text-field>

                      <v-select
                        label="Select"
                      ></v-select>

                      <v-checkbox

                        value="1"
                        label="Option"
                        type="checkbox"
                      ></v-checkbox>

                      <v-btn
                        class="me-4"
                        type="submit"
                      >
                        submit
                      </v-btn>

                      <v-btn >
                        clear
                      </v-btn>
                    </form>
                    </v-sheet>
                    <v-card-actions class="justify-end">
                      <v-btn
                        variant="text"
                        @click="isActive.value = false"
                      >Close</v-btn>
                    </v-card-actions>
                  </v-card>
                </template>
              </v-dialog>
            </v-col>
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
import router from "@/router";

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
    };
  },
};
</script>
