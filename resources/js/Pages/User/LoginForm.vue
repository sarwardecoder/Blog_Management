<script setup>
import {Link, router, useForm, usePage} from "@inertiajs/vue3"
import {createToaster} from "@meforma/vue-toaster"
const toaster = createToaster({position:"top-right"});
const form = useForm({
email:"",
password:""
});
function submit(){
  if(form.email.length===0){
toaster.warning("email is required.");
  }else if(form.password.length===0){
toaster.warning("password is required.");

}else{
  form.post("/Login",{
    onSuccess(){
       if(page.props.flash.status===true ){
         router.get("/Dashboard");
        toaster.success("Login Successful");
       }else{
        router.get("/login");
        toaster.warning(page.props.flash.message);
       }
    }
  })
}
}
</script>

<template>
<div class="container py-4">
      <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="h2 mb-4">Login here</h1>
                <div v-if="successMessage" class="alert alert-success mb-4">
                    {{ successMessage }}
                </div>
                <form @submit.prevent="submit" class="mb-3">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="form-control"
                            required
                        />
                        <div v-if="errors.email" class="text-danger small mt-1">
                            {{ errors.email }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label"
                            >Password</label
                        >
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="form-control"
                            required
                            minlength="8"
                        />
                        <div
                            v-if="errors.password"
                            class="text-danger small mt-1"
                        >
                            {{ errors.password }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label"
                            >Confirm Password</label
                        >
                        <input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            class="form-control"
                            required
                            minlength="8"
                        />
                    </div>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="btn btn-primary w-100"
                    >
                        <span v-if="form.processing">Login in process...</span>
                        <span v-else>Login</span>
                    </button>
                </form>
            </div>
        </div>
    </div></template>

<style scoped>

</style>
