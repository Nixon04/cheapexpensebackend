<template>
  <Head>
    <title>Cheapxpense | Users Information and Registration</title>
  </Head>
  <div class="main-board-mealxpress">
      <NavbarComponent />
      <div class="mealxpress-content">
        <HeaderDashboard/>
        <div class="mealxpress-main">
          <div class="p-1">
              <div class="car">
                  <h5 class="card-header mb-4">
                    Registered Users
                  </h5>
                  <div class="d-flex justify-content-between">
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
                          <!-- <div class="meal-form-button">
                              <button @click="ShowCenterModel(item)" class="togglebutton btn bg-button-submit py-3 text-sm">Add Users</button>
                          </div> -->
                      </div>
                  </div>
                  <div class="cx">
                  <div class="table-responsive text-nowrap">
                    <table class="table">
                     <thead>
                        <tr>
                        <th>Fullname</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>contact</th>
                        <th>profileimage</th>
                        <th>DOB</th>
                        <th>Reg Date</th>

                        </tr>
                    </thead>
                    <tbody>`
                      <tr v-if="paginatedData.length === 0">
                        <td colspan="8" class="text-center">
                          No Registered User found
                        </td>
                      </tr>
                        <tr v-for="(item, index) in paginatedData" :key="index.id" @click="modelsetup(item)">
                          <td v-if="noResults">No Registered User found  </td>
                          <td> {{item.fullname}}</td>
                          <td> {{item.username}}</td>
                          <td> {{item.email}}</td>
                          <td> {{item.contact}}</td>
                          <td><img :src="item.profileimage" style="height: 30px; height:100px"></td>
                          <td><span class="badge bg-label-info me-1">{{item.dob}}</span></td>
                          <td>{{  formatDate(item.created_at)}}</td>                         
                          <td>
                    
                  </td>
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
import {ref, computed} from 'vue';
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

  formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
}
},
  setup(){
  const { props } = usePage(); 
  const data = ref(props.data || []);
  const searchQuery = ref('');
  const rowsPerPage = ref(5); // Default rows per page
  const currentPage = ref(1);
  const dropOption = [5, 20, 50, 100];
  const isLoading = ref(false);


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
    currentPage
  }
  }
}
  
  </script>

