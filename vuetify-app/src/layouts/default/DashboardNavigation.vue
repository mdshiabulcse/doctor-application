<template>
  <v-card>
    <v-layout>
      <v-navigation-drawer
        v-model="drawer"
        :rail="rail"
        permanent
        @click="rail = false"
      >
        <v-list-item
          prepend-avatar="https://randomuser.me/api/portraits/men/85.jpg"
          :title="UserName"
          nav
        >
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
          <router-link :to="{name:'user.dashboard'}" > <v-list-item prepend-icon="mdi-home-city" title="Home" value="home"></v-list-item></router-link>
          <router-link :to="{name:'user.profile'}" ><v-list-item prepend-icon="mdi-account" title="My Account" value="profile"></v-list-item></router-link>
          <a href="javascript::void(0)"  @click="userLogout"><v-list-item prepend-icon="mdi-power" title="Logout" ></v-list-item></a>
        </v-list>
      </v-navigation-drawer>
      <v-main style="height: 700px">
        <router-view/>
      </v-main>
    </v-layout>
  </v-card>
</template>
<script setup>
import {ref} from "vue";
import {useAuth} from "@/store/auth.js";
import {storeToRefs} from "pinia";


const drawer= ref(true);
const rail= ref(true);
const userData=useAuth();
const UserName=userData.user.data.name;

const auth=useAuth();
const {user}=storeToRefs(auth);

const userLogout= async()=>{
  await auth.logout();
}

</script>
