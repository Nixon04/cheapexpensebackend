
<template>
    <div>
      <!-- header -->
      <Head>
        <title>Mealxpress | Login Page For Admin</title>
      </Head>
      <div class="mealxpress-background-set">
          <div class="meal-header-state bg-pad-big">
            <h1 class="text-white bg-center-text fw-bold fs-2">CheapXpense<span class="bg-color-secondary">.com</span></h1>
          </div>
          
          <div class="login-container-set">
           <div class="login-spacing mb-5">
              <h1 class="bg-center-text fs-3 fw-bold bg-color-primary mb-2">Login to your account</h1>
              <h6 class="bg-center-text bg-color-primary">Securely login to your Mealxpress Admin Dashboard App</h6>
           </div>     
           <div class="form-data-set">
            <form @submit.prevent="LoginSubmit">
             <div class="form-inputs mb-3">
              <input type="text" class="py-3 form-control" v-model="userID" placeholder="Enter your Email Address" autofocus>
            </div>
            <div class="form-inputs mb-3 position-relative">
              <input :type="passwordfield" id="password" v-model="password" class="py-3 form-control" placeholder="Enter Password" autofocus>
              <span class="pass-top" @click.prevent="toggleVisibility">
                <i :class="showPassword? 'fas fa-eye' : 'fas fa-eye-slash'"></i>
              </span>
             <span></span>
            
            </div>
            <div class="form-inputs mb-3">
              <button type="submit" :class="loading ? 'bg-button-submit py-3 btn' : 'btn bg-button-submit py-3 text-white' ">
                {{ loading ? 'Loading...' : 'Login' }}
              </button>
            </div>
            </form> 
          </div>
        </div>
        <div class="forgot-position">
        <!-- <Link href="/vendorspath/auth/forgotpassword">
         <h1 class="text-white bg-center-text fw-bold fs-7">Forgot Password? 
          <span class="bg-color-secondary ms-2">Recover Now</span>
        </h1>
    </Link> -->
        </div> 
      </div>
    </div>
  </template>
  <script>
  
  import axios from 'axios';
  import '../../../assetsmain/vendor/css/rtl/core.css';
  import '../../../assetsmain/vendor/css/rtl/theme-default.css';
  import '../../../assetsmain/vendor/fonts/boxicons.css';
  import '../../../assetsmain/vendor/fonts/fontawesome.css';
  import '../../../assetsmain/vendor/fonts/flag-icons.css';
  import '../../../assetsmain/mealxpresscustom/app.css';
  import '../../../assetsmain/mealxpresscustom/media.css';
  import { Head } from '@inertiajs/inertia-vue3';
  import {Link} from '@inertiajs/inertia-vue3';
  import { useToast } from 'vue-toastification';
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  export default{
    data() {
      return {
        password: '',  
        userID: '',
        showPassword: false,   
        errorMessage: '',
        loading: false,
      };
    },
    components:{
      Link,
      Head,
    },
    computed:{
      passwordfield(){
        return this.showPassword ? 'text' : 'password';
      },
    },
  methods:{
    toggleVisibility(){
      this.showPassword =! this.showPassword;
    },
    // delay(ms) {
    //     return new Promise(resolve => setTimeout(resolve, ms));
    //   },
  
    async LoginSubmit(){
      if (this.loading) return;
      const toast = useToast();
      if(!this.userID || !this.password){
        this.errorMessage = "Please fill in both username and password";
        toast.info("Please fill in both username and password", {
          timeout: 2000,
          hideProgressBar:true,
          icon: false,
        });
        return;
      }
      const payload = {
        email: this.userID,
        password: this.password,
      }
      try{
        this.loading = true;
  
        const response = await axios.post('/cheapx/auth/loginpost', payload, {
          method: 'POST',
          headers: {'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
          }, 
        });
        if(response.data.status == "success"){
         toast.success('Login successfully',{
          timeout:3000,
          hideProgressBar: true,
          icon:false,
         });
         window.location.href = '../dashboard/home';
        }else{
          toast.error("Email or password not correct",{
          timeout:3000,
          hideProgressBar:true,
          icon:false,
         });
        }
      }catch(error){
        toast.error('Oops something went wrong try again later',{
          timeout:3000,
          hideProgressBar: true,
          icon:false,
         });
      }finally{
        this.loading = false;
      }
    }
  },
  };
  
  
  </script>
  
  