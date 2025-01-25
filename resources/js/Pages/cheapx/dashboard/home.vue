<template>
    <div class="main-board-mealxpress">
      <NavbarComponent />
      <div class="mealxpress-content">
        <HeaderDashboard/>
        <div class="mealxpress-main">
           <div class="row">
            <div class="col-lg-12 ">
              <div class="col-lg-12 col-12 mb-5">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="d-flex flex-column">
                    <h1 class="fs-2">Dashboard</h1>
                     <h6>Here's your dashboard sales details overview</h6>
                   </div>
                  <div class="d-flex">
                    <Button  @click="showtransfermodel" class="bg-button-submit bg-b btn p-3 m-3 py-2 text-white ">
                      BlackList 
                    </Button>
                  </div> 
                </div>
              </div>
                <div class="row mb-3">
                    <div class="col-lg-6 col-md-12">
                        <div class="row">
                            <div class="col-lg-6 col-12 ">
                                <div class="card  gap-1 shadow-none card-outline mb-3">
                                   <div class="d-flex justify-content-between mb-2 px-4 p-2">
                                    <h1 class="fs-4">Total Sales</h1>
                                      <div class="bg-circle border-circle">
                                        <i class="fa-solid fa-ellipsis"></i>
                                      </div>
                                   </div>
                                   <div class="div-centered mb-3 px-4 p-2">
                                    <h1 class="fs-3 fw-bold mb-3"> {{ formatCurrency(totalsales) }} </h1>
                                    <div class="d-flex">
                                      <div class="bg-auto-rate me-2">
                                        <i class="fa-solid fa-arrow-up-long"></i>
                                        +5%
                                      </div>
                                      <span>Vs Last Month</span>
                                    </div>
                                   </div>
                                  <Link href="">
                                    <div class="card bg-lightgrey px-4 p-1 m-0">
                                      <div class="d-flex justify-content-between align-items-center p-3">
                                        <h6>View More</h6>
                                        <div class="bg-circle">
                                          <i class="fa-solid fa-chevron-right"></i>
                                        </div>
                                      </div>
                                     </div>
                                   </Link>
                                </div>
                            </div>
                            <div class="col-lg-6">
                              <div class="card  gap-1 shadow-none card-outline mb-3">
                                 <div class="d-flex justify-content-between mb-2 px-4 p-2">
                                  <h1 class="fs-4">Total Orders</h1>
                                    <div class="bg-circle border-circle">
                                      <i class="fa-solid fa-ellipsis"></i>
                                    </div>
                                 </div>
                                 <div class="div-centered mb-3 px-4 p-2">
                                  <h1 class="fs-3 fw-bold mb-3"> {{ formattedAmount(totalcount) }} </h1>
                                  <div class="d-flex">
                                    <div class="bg-auto-rate me-2">
                                      <i class="fa-solid fa-arrow-up-long"></i>
                                      +5%
                                    </div>
                                    <span>Vs Last Month</span>
                                  </div>
                                 </div>
                                <Link href="">
                                  <div class="card bg-lightgrey px-4 p-1 m-0 shadow-none">
                                    <div class="d-flex justify-content-between align-items-center p-3">
                                      <h6>View More</h6>
                                      <div class="bg-circle">
                                        <i class="fa-solid fa-chevron-right"></i>
                                      </div>
                                    </div>
                                   </div>
                                 </Link>
                              </div>
                          </div>
                            <div class="col-lg-12 mb-3">
                              <div class="card p-4">
                                <div class="d-flex justify-content-between">
                                  <h1 class="fs-5 fw-bold">Customer Activities</h1>
                                  <h6 class="fw-bold fs-4">+239</h6>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                    <!-- recent updated request -->
                    <div class="col-lg-6 col-md-12">
                      <div class="card">
                        <div class="card-body">
                            <BarChart/>
                          </div>
                    </div>
                    </div>
                </div>
                <div class="card py-3  px-4">
                  <div class="d-flex justify-content-between">
                    <h5 class="card-header">Recent PurChase</h5>
                    <div class="form-bod">
                      <input
                        type="text"
                        class="form-control py-2"
                        v-model="searchQuery"
                        placeholder="Search your orders"
                      />
                    </div>
                  </div>
        
                  <div class="card-datatable text-nowrap table-responsive">
                    <table class="dt-fixedcolumns table  ">
                      <thead>
                        <tr>
                          <th>Username</th>
                          <th>Amount</th>
                          <th>T_Purchase</th>
                          <th>S_Purchase</th>
                          <th>D_Type</th>
                          <th>Status</th>
                          <th>Ref_Num</th>
                          <th>Reference</th>
                          <th>Date</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-if="filteredData.length === 0">
                            <td colspan="8" class="text-center">No Recent Order made </td>
                        </tr>
                        <tr v-for="(item, index) in filteredData" :key="item.id" >
                          <td> {{item.username}}</td>
                            <td> ₦{{formattedAmount(item.amount)}}</td>
                            <td> {{item.type_of_purchase}}</td>
                            <td> {{item.sub_type_purchase}}</td>
                            <td> {{item.data_type}}</td>
                            <td><span class="badge bg-label-info me-1">{{item.status}}</span></td>
                            <td> {{item.ref_num_purchase}}</td>
                            <td><span class="badge bg-label-info me-1">{{item.reference}}</span></td>
                            <td>{{date_of_purchase}}</td>
                            <td> {{item.created_at}}</td>
                        </tr>
                      </tbody>
                    </table>
                    <div v-if="noResults" class="text-center py-4">
                      Cheapxpense couldn't find any query data of this such {{ searchQuery }}
                    </div>
                  </div>        
                  <!-- Pagination Controls -->
                  <div class="d-flex justify-content-between mb-2 py-2 px-4">
                    <span>Page {{ currentPage }} of {{ totalPages }}</span>
                      <div class="d-flex justify-content-between gap-4">
                      <select v-model="rowsPerPage" @change="changevaluestate" class="form-select">
                        <option v-for="option in dropOption" :key="option" :value="option">
                          {{ option }}
                        </option>
                      </select>
                      <div class="d-flex gap-4">
                        <button @click="prevPage" :disabled="currentPage === 1">Previous</button>
                        <button @click="nextPage" :disabled="currentPage === totalPages">Next</button>
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
      populatebanks,
      totalsales,
      totalcount,
      filteredData,
    }

    }
  };
  </script>
  