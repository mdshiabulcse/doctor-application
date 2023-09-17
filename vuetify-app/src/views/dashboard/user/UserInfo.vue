<template>
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
</template>

<script>
import {ref,computed,onMounted} from 'vue';
import axiosInstance from "@/services/axiosService";
import router from "@/router";
export default {
  setup() {
    const itemsPerPage=ref(5);
    const search = ref('');
    const desserts = ref([]);

    const headers = computed(() => [
      {title: 'Doctor Name', align: 'start', sortable: false, key: 'doctor_name',},
      {title: 'Doctor ID', align: 'end', key: 'doctor_id',},
      { title: 'Hospital Name', align: 'end', key: 'hospital_name' },

    ]);
    const fetchPosts = async () => {
      try {
        const response = await axiosInstance('/admin/doctor-data'); // Replace with your API endpoint
        desserts.value =  response.data.doctors;
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
    };
  },
};
</script>
