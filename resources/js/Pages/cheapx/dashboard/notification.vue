<template>
    <Head>
      <title>Cheapxpense | Notification Information</title>
    </Head>
    <div class="main-board-mealxpress">
        <NavbarComponent />
        <div class="mealxpress-content">
          <HeaderDashboard/>
          <div class="mealxpress-mai">
            <div class="card-general-container card py-5">
                <div class="bx-space-bond">
                  <input 
                  type="text"
                   class="py-3 form-control"
                    placeholder="Title"
                    v-model="titlebody"
                    >
                  <!-- input longtext -->
                  <textarea name=""
                   ref="textref"
                   @input="autoResize" 
                   cols="5" rows="5" class="form-control mb-5"
                    style="resize:none;overflow:hidden"
                    v-model="message"
                    ></textarea>

                  <button @click="sendNotification()" :disabled="isAlreadyLoading" :class="[isAlreadyLoading ?'inactive-state-button-x' :'action-button']">
                    Send Notification
                  </button>
                </div>
            </div>
        </div>
        </div>
    </div>
  </template>
  
  <script>
  import '../../../assetsmain/vendor/css/rtl/core.css';
  import '../../../assetsmain/vendor/css/rtl/theme-default.css';
  import '../../../assetsmain/vendor/fonts/boxicons.css';
  import '../../../assetsmain/vendor/fonts/fontawesome.css';
  import '../../../assetsmain/vendor/fonts/flag-icons.css';
  import '../../../assetsmain/mealxpresscustom/app.css';
  import '../../../assetsmain/mealxpresscustom/media.css';
  import '../../../assetsmain/css/demo.css';
  import NavbarComponent from '../../../components/adminnav/aside.vue';
  import Navigation from './../../components/vendorsnav/navigation.vue';
  import HeaderDashboard from './../../components/vendorsnav/header.vue';
  import TransferInit from './../../components/modals/transferinit.vue';
  import BarChart from './../../components/charts/chartview.vue';
  import { Link, usePage,Head } from '@inertiajs/vue3';
  import { useToast } from "vue-toastification";
  import {ref, computed, onMounted, reactive} from 'vue';
  import { Button } from 'bootstrap';
  import axios from 'axios';
  
  export default {
    components: {
      NavbarComponent,
      Navigation,
      HeaderDashboard,
      BarChart,
      Link,
      TransferInit,
      Head,
    },
    methods: {
    formattedPrice(price) {
      if (price === null || price === undefined) return ''; // Handle null/undefined cases
      return parseFloat(price).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      });
    },
  },
    setup(){
    const { props } = usePage(); 
    const data = ref(props.data || []);
    const searchQuery = ref('');
    const rowsPerPage = ref(5); // Default rows per page
    const currentPage = ref(1);
    const dropOption = [5, 20, 50, 100];
    const isLoading = ref(false);
    const textref = ref(null);
    const titlebody = ref('');
    const message = ref('');

    const isAlreadyLoading = ref(false);



    const sendNotification = async ()  => {
      const toast = useToast();

    const payload = reactive({
      title:  titlebody.value,
      body: message.value,
    });

    try{
      isAlreadyLoading.value = true;
      const response = await axios.post('/sendnotification', payload);
      if(response.status == 200){
        toast.success('Payload successful',{
            hideProgressBar:true,});
      }else{
        toast.failed('Payload successful',
          {
            hideProgressBar:true,
            
          }
        );
      }
    }catch(e){
      console.log('Console information ', e);
    }
    finally{
      isAlreadyLoading.value = false;

    }
  
    }




    const autoResize = () => {
      const el = textref.value;
      if(el){
        el.style.height = 'auto';
        el.style.height = `${el.scrollHeight}px`;
      }
    }
  
  
    const filteredData = computed(() => {
      if (!data.value) return [];
      if (!searchQuery.value) return data.value;
      return data.value.filter((item) =>
        Object.values(item).some((val) =>
          String(val).toLowerCase().includes(searchQuery.value.toLowerCase())
        )
      );
    });
  
    const noResults = computed(() => filteredData.value.length === 0);
    const nextPage = () => {
      if (currentPage.value < totalPages.value) currentPage.value++;
    };
  
    const paginatedData = computed(() => {
      const start = (currentPage.value - 1) * rowsPerPage.value;
      const end = start + rowsPerPage.value;
      return filteredData.value.slice(start, end);
    });
  
    const prevPage = () => {
      if (currentPage.value > 1) currentPage.value--;
    };
  
    const changevaluestate = () => {
      currentPage.value = 1;
    };
  
    const totalPages = computed(() => {
      return Math.ceil(filteredData.value.length / rowsPerPage.value);
    });

    onMounted(() => {
      autoResize(); // In case there is pre-filled content
    });
  
  
  
    return{
      data,
      searchQuery,
      rowsPerPage,
      nextPage,
      prevPage,
      changevaluestate,
      totalPages,
      noResults,
      paginatedData,
      dropOption,
      currentPage,
      autoResize,
      textref,
      sendNotification,
      isAlreadyLoading,
      titlebody,
      message,
    }
    }
  }
    
    </script>
  
  