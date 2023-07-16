<script setup>

import {useAuth} from "@/store/auth";
import {ref} from "vue";
import {Field, Form} from 'vee-validate';
import * as yup from 'yup';
import {useRouter} from 'vue-router';
import {ElNotification} from 'element-plus';

const auth = useAuth();
const router = useRouter();
const showPassword = ref(false);
const toggleShow = () => {
  showPassword.value = !showPassword.value
};
// const form= reactive({
//   phone:"",
//   password:"",
//
// })
const onSubmit = async (values, {setErrors}) => {
  const res = await auth.login(values);
  if (res.data) {
    console.log(res.data)
    router.push({name: 'user.dashboard'});
    ElNotification({
      title: 'Success',
      message: "Login Success!",
      position: 'top-left',
      type: 'success',
    })
  } else {
    setErrors(res);
  }

};

const schema = yup.object({
  phone: yup.string().required(),
  password: yup.string().required('Password must be at least 8 characters').min(8),
});
</script>


<template>
  <div>
    <main>
      <div class="container">

        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                <div class="d-flex justify-content-center py-4">
                  <a href="index.html" class="logo d-flex align-items-center w-auto">
                    <!--                    <img src="assets/img/logo.png" alt="">-->
                    <span class="d-none d-lg-block">Doc App</span>
                  </a>
                </div><!-- End Logo -->

                <div class="card mb-3">

                  <div class="card-body">

                    <div class="pt-4 pb-2">
                      <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                    </div>

                    <Form class="row g-3 needs-validation" @submit="onSubmit" :validation-schema="schema"
                          v-slot="{errors, isSubmitting}">
                      <div class="col-12">
                        <label for="yourUsername" class="form-label">Phone</label>
                        <div class="input-group has-validation">
                          <Field
                            name="phone"
                            type="text"
                            class="form-control "
                            placeholder="phone no"
                            :class="{'is-invalid':errors.phone}"
                          />
                          <span class="text-danger">{{ errors.phone }}</span>
                        </div>
                      </div>

                      <div class="col-12">
                        <label for="yourPassword" class="form-label">Password</label>
                        <Field
                          name="password"
                          :type="showPassword ? 'text':'password'"
                          class="form-control"
                          placeholder="password"
                          :class="{'is-invalid':errors.password}"
                        />
                        <span class="view-password" @click="toggleShow"><i  :class="{'bi bi-eye-slash':showPassword,'bi bi-eye':!showPassword,}"></i></span>
                        <span class="text-danger">{{ errors.password }}</span>
                      </div>

                      <div class="col-12">
                        <button class="btn btn-primary w-100" type="submit" :disabled="isSubmitting">login<span
                          v-show="isSubmitting" class="spinner-border spinner-border-sm mr-1 "></span></button>
                      </div>
                    </Form>

                  </div>
                </div>
                <div class="credits">
                </div>

              </div>
            </div>
          </div>

        </section>

      </div>
    </main>
  </div>
</template>
