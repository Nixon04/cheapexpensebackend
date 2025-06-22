<template>
    <Head>
      <title>Cheapxpense | Data Information</title>
    </Head>
    <div class="main-board-mealxpress">
        <Addproducts :addVisible="addVisible" :dataModal="dataModal"  @close="CloseCenterModel"  @submitmodal="UploadProducts"/>
        <NavbarComponent />
        <div class="mealxpress-content">
          <HeaderDashboard/>
          <div class="mealxpress-mai p-1">
            <div class="card-general-container card p-2">
                <div class="card">
                    <h5 class="card-header">
                      <div class="d-flex justify-content-end">
                        <button  :class="[refstateloading ? 'inactive-state-button': 'btn-action' ]" @click="ReactiveUpdate()" :disabled="refstateloading" >
                         
                         <template v-if="refstateloading">...</template>
                          <template v-else>
                            <span  class="updatepg-text" >Update Package</span>
                          </template>
                          
                        </button>
                      </div>
                    </h5>
                    <div class="d-flex justify-content-between px-1">

                        <div class="d-flex">
                            <div class="form-input">
                                <input type="text" class="form-control py-2" v-model="searchQuery"  placeholder="Search...">
                            </div>
                        </div>
                        <div class="d-flex gap-4">
                            <div class="form-input">
                                <select v-model="rowsPerPage"  class="form-select py-2" @change="changevaluestate" >
                                    <option v-for="option in dropOption" :key="option" :value="option">{{ option }}</option>
                                </select>
                            </div>
                        
                        </div>
                    </div>
                    <div class="card-bod">
                    <div class="table-responsive text-nowrap">
                      <table class="table">
                       <thead>
                          <tr>
                          <th>Network Name</th>
                          <th>Network Code</th>
                          <th>Network Package</th>
                          <th>NetworkPlanList</th>
                          <th>Initial price</th>
                          <th>Current price</th>
                          </tr>
                      </thead>
                      <tbody>`
                        <tr v-if="paginatedData.length === 0">
                          <td colspan="8" class="text-center">
                            No Data Plan Found
                          </td>
                        </tr>
                          <tr v-for="(item, index) in paginatedData" :key="index.id">
                            <td v-if="noResults">No Registered User found  </td>
                            <td> {{item.network}}</td>
                            <td> {{item.plan_code}}</td>
                            <td> {{item.name}}</td>
                            <td> {{item.alias}}</td>
                            <td>{{item.amount}}</td>
                            <td>{{item.current_amount}}</td>
                            <!-- <td @click="updateprice(item.id)"><i class="fa-solid fa-ellipsis cursor-pointer"></i></td>                            <td>
                      
                    </td> -->
                    </tr>
       
        </tbody>
        </table>
    </div>
    <!-- pagination details -->
    <div class="card p-3">
        <div class="d-flex justify-content-between">
            <div class="d-flex">
                <span>Showing {{ currentPage }} to {{ totalPages }} entries</span>
              
            </div>
            <div class="d-flex gap-4">
                <button @click="prevPage" :disabled="currentPage === 1" class="outline-pointer">prev</button>
                <button @click="nextPage" :disabled="currentPage === totalPages" class="outline-pointer">Next</button>
            </div>
        </div>
    </div>
    <!-- end of pagination -->
    </div>
  </div>
  <!--/ Bordered Table -->
  
        
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
  import Addproducts from './../../components/modals/addproducts.vue';
  import {ref, computed, reactive} from 'vue';
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
      Addproducts,
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
    const addVisible = ref(false);
    const actionModal = ref('');
    const toast = useToast();
    const refstateloading = ref(false);

     const ReactiveUpdate = async () => {
      try{
        refstateloading.value = true;
        console.log('Loading',refstateloading.value);
      const payload = {
        'type': 'all',
      };
      const response = await axios.post('/updatedatapackages', payload, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
      });

      if(response.status == 200){
        toast.success('All Data Updated Successfully',{
          hideProgressBar:true,
          timeout: 3000,
        });
        console.log('successful');
      }else{
        console.log('Couldn\'t get the information needed');
      }s
    }catch(error){
      console.log('Error '.error);
    }
    finally{
      refstateloading.value = false;
    }
  }


    const dataModal = reactive({
        networkid: '',
        networkprice: '',
        networkplan: '',
        networkpackagespace: '',
    });
   

    const updateprice  = (item) => {
        dataModal.networkid = item.id;
        dataModal.networkprice = item.networkPrice;
        dataModal.networkplan =  item.networkPlansList;
        dataModal.networkpackagespace = item.networkPackageSpace;
      
        addVisible.value = true;    
        actionModal.value = item;
        console.log(actionModal);

    };

    const CloseCenterModel = () => {
        addVisible.value = false;
        
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
  
  
  
    return{
     updateprice,
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
      addVisible,   
      CloseCenterModel,
      dataModal,
      actionModal,
      ReactiveUpdate,
      refstateloading,
    }
    }
  }
    
    </script>
  
  