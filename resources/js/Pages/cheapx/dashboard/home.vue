<template>
  <div>
    <div class="main-board-mealxpress">
      <NavbarComponent />
      <div class="mealxpress-content">
        <HeaderDashboard/>
        <div class="mealxpress-main">
           <div class="row">
            <div class="col-lg-12 ">
              <div class="col-lg-12 col-12 mb-1">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="d-flex flex-column">
                    <h1 class="fs-2">Dashboard</h1>
                     <h6 class="text-dark">Here's your dashboard sales details overview</h6>
                   </div>
                  <div class="d-flex">
                    <Button  @click="showtransfermodel" class="bg-button-submit bg-b btn p-3 m-3 py-2 text-white ">
                      BlackList 
                    </Button>
                  </div> 
                </div>
              </div>
                <div class="row mb-3">
                    <div class="col-lg-12 col-md-12">
                        <div class="row gx-1 gy-1">
                            <div class="col-lg-6 col-12 ">
                                <div class="card format-card gap-1 shadow-none card-outline mb-3">
                                   <div class="d-flex justify-content-between mb-1 px-4 p-2">
                                    <h1 class="fs-3">Total Sales</h1>
                                      <div class="bg-circle border-circle">
                                        <i class="fa-solid fa-ellipsis"></i>
                                      </div>
                                   </div>
                                   <div class="div-centered mb-3 px-4 p-2">
                                    <h1 class="fs-0 fw-bold mb-3"> {{ formatCurrency(totalsales) }} </h1>
                                   </div>
                                  <Link href="/cheapx/dashboard/transactions">
                                    <div class="card bg-lightgrey px-4 p-1 m-0">
                                      <div class="d-flex justify-content-between align-items-center ">
                                        <h6 class="text-dark">View More</h6>
                                        <div class="bg-circle">
                                          <i class="fa-solid fa-chevron-right"></i>
                                        </div>
                                      </div>
                                     </div>
                                   </Link>
                                </div>
                            </div>
                            <div class="col-lg-6">
                              <div class="card format-card gap-1 shadow-none card-outline mb-3">
                                 <div class="d-flex justify-content-between mb-1 px-4 p-2">
                                  <h1 class="fs-1">Today's Orders</h1>
                                    <div class="bg-circle border-circle">
                                      <i class="fa-solid fa-ellipsis"></i>
                                    </div>
                                 </div>
                                 <div class="div-centered mb-1 px-4 p-2">
                                  <h1 class="fs-0 fw-bold mb-2"> {{ formattedAmount(todaysCount ? todaysCount : '0') }} </h1>
                                 </div>
                                <Link href="/cheapx/dashboard/transactions">
                                  <div class="card bg-lightgrey px-4 p-1 m-0 shadow-none">
                                    <div class="d-flex justify-content-between align-items-center">
                                      <h6 class="text-dark">View More</h6>
                                      <div class="bg-circle">
                                        <i class="fa-solid fa-chevron-right"></i>
                                      </div>
                                    </div>
                                   </div>
                                 </Link>
                              </div>
                          </div>
                        </div>
                    </div>
                    <!-- recent updated request -->
                    <div class="col-lg-12 col-md-12">
                      <div class="card">
                        <div class="card-body">
                            <BarChart/>
                          </div>
                    </div>
                    </div>
                </div>
            
               </div>
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
  import NavbarComponent from '../../../components/adminnav/aside.vue';
  import Navigation from './../../components/vendorsnav/navigation.vue';
  import HeaderDashboard from '../../components/vendorsnav/header.vue';
  import TransferInit from './../../components/modals/transferinit.vue';
  import BarChart from '../../components/charts/chartview.vue';
  import { Link, usePage } from '@inertiajs/vue3';
  import { useToast } from "vue-toastification";
  import {ref, computed} from 'vue';
  import axios from 'axios';
  
  export default {
    components: {
      NavbarComponent,
      Navigation,
      HeaderDashboard,
      BarChart,
      Link,
      TransferInit,
    },
    methods: {
      formatCurrency(value) {
        return new Intl.NumberFormat('en-NG', { 
          style: 'currency', 
          currency: 'NGN',
          minimumFractionDigits: 2, 
          maximumFractionDigits: 2
        }).format(value);
      },
      formattedAmount(price) {
      if (price === null || price === undefined) return ''; // Handle null/undefined cases
      return parseFloat(price).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      });
    },
    },
    setup(){
    const {props} = usePage();
    const searchQuery = ref('');
    const rowsPerPage = ref(3); // Default rows per page
    const currentPage = ref(1);
    const totalsales  = ref(props.total || []);
    const dropOption = [3, 20, 50, 100];
    const todaysTransactions = ref(props.data.todaysTransactions || '');
    const todaysCount = ref(props.todaysCount);
    const data  = ref(props.data || []);
    const totalsum = ref(props.totalsum || []);
    const salescount = ref(props.salescount || []);
    const totalcount = ref(props.count || []);
    const populatebanks  =  ref(null);
    const stablevisible = ref(false);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
// algorithms for table contents

   const  fetchallbanks = async () => {
    const response = await axios.get('/vendorspath/populatebanks', {
      headers: {
        'X-CSRF-TOKEN': csrfToken,
      }
    });
    try{
    if(response.status){
      populatebanks.value = response.data.data;
       console.log(populatebanks);
    }else{
      console.log(response.status);
    }
    }catch(error){
      console.log(error);
    }

   };

     const showtransfermodel = () => {
        stablevisible.value = true;
        fetchallbanks();
        console.log(stablevisible.value);
     };
    const  dismissmodalclear = () => {
       stablevisible.value = false;
     };

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
    const totalPages = computed(() => {
      return Math.ceil(filteredData.value.length / rowsPerPage.value);
    });
    const paginatedData = computed(() => {
      const start = (currentPage.value - 1) * rowsPerPage.value;
      const end = start + rowsPerPage.value;
      return filteredData.value.slice(start, end);
    });
    const nextPage = () => {
      if (currentPage.value < totalPages.value) currentPage.value++;
    };
    const prevPage = () => {
      if (currentPage.value > 1) currentPage.value--;
    };
    const changevaluestate = () => {
      currentPage.value = 1;
    };
    // end of table algorithms
    // return call function
    return {
      searchQuery,
      rowsPerPage,
      currentPage,
      changevaluestate,
      data,
      noResults,
      totalPages,
      paginatedData,
      nextPage,
      dropOption,
      prevPage,
      totalsum,
      salescount,
      stablevisible,
      showtransfermodel,
      dismissmodalclear,
      todaysCount,
      populatebanks,
      totalsales,
      totalcount,
      filteredData,
    }

    }
  };
  </script>
  