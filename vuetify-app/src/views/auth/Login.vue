
<script setup>

import {reactive, ref} from "vue";
import {useAuth} from "@/store/auth";
import {storeToRefs} from "pinia";

const auth=useAuth();
const {errors}=storeToRefs(auth);
const visible= ref(false);
const form= reactive({
  phone:"",
  password:"",

})
const onSubmit= async ()=>{
  await auth.login(form);
}
</script>

<template>
  <div>
    <v-img
      class="mx-auto my-6"
      max-width="228"
      src="https://cdn.vuetifyjs.com/docs/images/logos/vuetify-logo-v3-slim-text-light.svg"
    ></v-img>

    <v-card
      class="mx-auto pa-12 pb-8"
      elevation="8"
      max-width="448"
      rounded="lg"
    >
      <div class="text-subtitle-1 text-medium-emphasis">Account</div>
      <form @submit.prevent="onSubmit">
        <v-text-field
          density="compact"
          placeholder="Phone"
          prepend-inner-icon="mdi-phone-outline"
          variant="outlined"
          v-model="form.phone"
          :error-messages="errors.phone"
        ></v-text-field>

        <v-text-field
          :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
          :type="visible ? 'text' : 'password'"
          density="compact"
          placeholder="Enter your password"
          prepend-inner-icon="mdi-lock-outline"
          variant="outlined"
          @click:append-inner="visible = !visible"
          v-model="form.password"
          :error-messages="errors.password"
        ></v-text-field>

        <v-btn
          block
          class="mb-8"
          color="blue"
          size="large"
          variant="tonal"
          type="submit"
        >
          Log In
        </v-btn>
      </form>

    </v-card>
  </div>
</template>
