<template>
  <v-layout class="rounded rounded-md">
    <v-navigation-drawer
      color="#E0F2F1"
      v-model="drawer"
      :rail="rail"
      permanent
      @click="rail = false">
      <v-list-item
        prepend-avatar="https://randomuser.me/api/portraits/men/85.jpg" :title="UserName" nav>
        <template v-slot:append>
          <v-btn
            variant="text"
            icon="mdi-chevron-left"
            @click.stop="rail = !rail"
          ></v-btn>
        </template>
      </v-list-item>
      <v-divider></v-divider>
      <v-list density="compact" nav>
        <Sidbar/>
        <a href="javascript::void(0)" @click="userLogout">
          <v-list-item prepend-icon="mdi-power" title="Logout"></v-list-item>
        </a>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      :order="order"
      color="#4DB6AC"
      flat
      title="DOCTOR APPLICATION"
    >
      <template v-slot:append>
        <v-switch
          color="white"
          v-model="order"
          hide-details
          inset
          label="Toggle order"
          true-value="-1"
          false-value="0"
        ></v-switch>
      </template>
    </v-app-bar>

    <v-main class="d-flex align-center justify-center" style="min-height: 300px;">
      <router-view/>
    </v-main>
  </v-layout>
</template>
<script setup>
import {ref} from "vue";
import {useAuth} from "@/store/auth.js";
import {storeToRefs} from "pinia";
import Sidbar from "@/views/dashboard/Sidebar.vue"


const drawer = ref(true);
const rail = ref(false);
const order = ref(0);
const userData = useAuth();
const UserName = userData.user.data.name;

const auth = useAuth();
const {user} = storeToRefs(auth);

const userLogout = async () => {
  await auth.logout();
}

</script>

