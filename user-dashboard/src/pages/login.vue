<script setup>
import AuthProvider from '@/views/pages/authentication/AuthProvider.vue'
import logo from '@images/logo.svg?raw'
import {reactive} from "vue";
import {useAuth} from "@/store/auth";
import { useRouter} from 'vue-router';
import { ElNotification } from 'element-plus';

// const form = ref({
//   phone: '',
//   password: '',
//   remember: false,
// })

const isPasswordVisible = ref(false)

const auth=useAuth();
const {errors}=storeToRefs(auth);
const router=useRouter();

const form= reactive({
  phone:"",
  password:"",
  remember: false,
})
const onSubmit= async ()=>{
  const res = await auth.login(form);
  if(res.data){
    router.push({name:'user.dashboard'})
    ElNotification({
      title: 'Success',
      message: "Login Success!",
      position: 'top-left',
      type:'success',
    })
  }
}
</script>

<template>
  <div class="auth-wrapper d-flex align-center justify-center pa-4">
    <VCard
      class="auth-card pa-4 pt-7"
      max-width="448"
    >
      <VCardItem class="justify-center">
        <template #prepend>
          <div class="d-flex">
            <div
              class="d-flex text-primary"
              v-html="logo"
            />
          </div>
        </template>

        <VCardTitle class="text-2xl font-weight-bold">
          sneat
        </VCardTitle>
      </VCardItem>

      <VCardText class="pt-2">
        <h5 class="text-h5 mb-1">
          Welcome to DocApp! 👋🏻
        </h5>
      </VCardText>
      <VCardText>
        <VForm @submit.prevent="onSubmit">
          <VRow>
            <!-- email -->
            <VCol cols="12">
              <VTextField
                v-model="form.phone"
                autofocus
                placeholder="Phone Number"
                label="Phone"
                type="text"
                :class="{'is-invalid':errors.phone}"
                name="phone"
              />
              <span class="text-danger" v-if="errors.phone">{{errors.phone[0]}}</span>
            </VCol>

            <!-- password -->
            <VCol cols="12">
              <VTextField
                name="password"
                v-model="form.password"
                label="Password"
                placeholder="············"
                :type="isPasswordVisible ? 'text' : 'password'"
                :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                @click:append-inner="isPasswordVisible = !isPasswordVisible"
                :class="{'is-invalid':errors.password}"
              />
              <span class="text-danger" v-if="errors.password">{{errors.password[0]}}</span>
              <!-- remember me checkbox -->
              <div class="d-flex align-center justify-space-between flex-wrap mt-1 mb-4">
              </div>

              <!-- login button -->
              <VBtn
                block
                type="submit"
              >
                Login
              </VBtn>
            </VCol>

            <!-- create account -->
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </div>
</template>

<style lang="scss">
@use "@core/scss/template/pages/page-auth.scss";
</style>
