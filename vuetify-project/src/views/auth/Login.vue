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
          placeholder="Phone address"
          prepend-inner-icon="mdi-phone-outline"
          variant="outlined"
          type="text"
          v-model="form.phone"
        ></v-text-field>
        <v-text-field
          density="compact"
          placeholder="Enter your password"
          prepend-inner-icon="mdi-lock-outline"
          variant="outlined"
          type="password"
          v-model="form.password"
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
<script setup>
import {reactive} from "vue";
import {useAuth} from "@/store/auth";

const auth=useAuth();
const form= reactive({
  phone:"",
  password:"",
})
const onSubmit= async ()=>{
  await auth.login(form);
}
</script>
