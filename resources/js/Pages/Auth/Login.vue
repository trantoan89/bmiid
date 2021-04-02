<template>
  <div>
    <div class="flex flex-wrap content-center justify-center w-full h-screen">
      <div class="block w-11/12 md:w-4/5 justify-center md:flex">
        <div class="p-5 w-full md:w-1/2">
          <h1 class="text-7xl">BMIID</h1>
          <p class="text-base">Baguio Map-Integration of Infectious Disease(BMIID)</p>
        </div>
        <div class="p-5 w-full md:w-1/2 md:border-l-2 border-gray-300">
          <h1 class="text-2xl">Welcome Back</h1>
          <p class="text-sm">Login to your account</p>
          <form class="w-full" v-on:submit.prevent="submit">
            <div class="flex mt-5 flex-col mb-2">
              <!-- <label class="mb-2 text-base" for="username">Username</label> -->
              <input class="py-2 px-3 text-grey-darkest" v-model="form.username" :class="{'border-2 border-red-700' : validation.usernameErr}" placeholder="username" type="text" name="username" id="username" maxlength="50">
              <p v-if="validation.usernameErr" class="text-sm text-red-700">{{ validation.usernameMsg }}</p>
            </div>
            <div class="flex flex-col mb-4">
              <!-- <label class="mb-2 text-base" for="password">Password</label> -->
              <input class="py-2 px-3 text-grey-darkest"  v-model="form.password" :class="{'border-2 border-red-700' : validation.passwordErr}" placeholder="Password" type="password" name="password" id="password" maxlength="50">
              <p v-if="validation.passwordErr" class="text-sm text-red-700">{{ validation.passwordMsg }}</p>
            </div>
            <p v-if="validation.incorrect" class="text-sm my-1 flex justify-center text-red-700">{{ validation.messageError }}</p>
            <div class="flex flex-col">
              <button class="bg-white w-full border-2 py-2 border-blue-500 text-blue-500 hover:border-blue-700 hover:text-blue-700" :class="{'opacity-50': validation.submit}" :disabled="validation.submit">
                {{ validation.submit ? 'Loading..' : 'Login'}}
              </button>
            </div>
            <div class="flex justify-end mt-4">
              <inertia-link href="/forgot-password" class="text-sm text-blue-700 cursor-pointer hover:text-blue-400"><u>Forgot password</u></inertia-link>
            </div>
          </form>
          <p></p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  window.onunload = function(){};
    // for chrome
  if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
      location.reload();
  }

  export default {
    data(){
      return {
        validation: {
          usernameErr: false,
          usernameMsg: '',
          passwordErr: false,
          passwordMsg: '',
          submit: false,
          messageError: '',
          incorrect: false,
        },
        form: {
          username: '',
          password: '',
        }
      }
    },
    created(){
      document.title = "BMIID(Login)";

      this.index();
    },
    methods: {
      submit: function(){
        this.validation.submit = true;

        if(this.form.username === ""){
          this.validation.usernameErr = true;
          this.validation.usernameMsg = "Provide your username";
        }else{
          this.validation.usernameErr = false;
          this.validation.usernameMsg = "";
        }

        if(this.form.password === ""){
          this.validation.passwordErr = true;
          this.validation.passwordMsg = "Provide your password";
        }else{
          this.validation.passwordErr = false;
          this.validation.passwordMsg = "";
        }

        if(this.validation.usernameErr === false && this.validation.passwordErr === false){
          axios.get('/sanctum/csrf-cookie').then(response => {
            window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            window.axios.defaults.withCredentials = true;

            axios.post('/api/auth', this.form ).then((response) => {
              if(response.data.status_code === "2"){
                this.validation.submit = false;
                this.validation.messageError = response.data.message;
                this.validation.incorrect = true;
              }else{
                window.location.href = "/dashboard";
              }
            }).catch((error) => {
              console.log(error);
            })
          }).catch((error) => {
            console.log(error);
          });
        }else{
          this.validation.submit = false;
        }
      },
    }
  }
</script>
