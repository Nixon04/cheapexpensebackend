<template>
    <div class="bg-drawer-center" v-if="addVisible">
      <div class="bg-drawer-center-container">
        <div class="d-flex justify-content-between px-5 py-3">
          <h1 class="fs-5">Update Data Package </h1>
          <div class="bg-circle" @click="$emit('close')">
            <i class="fas fa-x"></i>
          </div>
        </div>
        <div class="container-scrollable px-5">
          <!-- Product Name Input -->

          <div class="form-input mb-3 d-none">
            <label for="InputProduct">Product Price</label>
            <input
              type="text"
              v-model="dataModal.networkid"
              id="InputProduct"
              class="form-control py-3"
              placeholder="Enter Product Name"
            />
          </div>
          
          <div class="form-input mb-3">
            <label for="InputProduct">Product Price</label>
            <input
              type="text"
              v-model="dataModal.networkprice"
              id="InputProduct"
              class="form-control py-3"
              placeholder="Enter Product Name"
            />
          </div>
  
          <!-- Product Price Input -->
          <div class="form-input mb-3">
            <label for="InputPrice">Product Description</label>
            <input
              type="text"
              v-model="dataModal.networkplan"
              id="InputPrice"
              class="form-control py-3"
              placeholder="Enter Product Price"
            />
          </div>


          <div class="form-input mb-3">
            <label for="inputspace">Product Package (GB,MB,KB) </label>
            <input
              type="text"
              v-model="dataModal.networkpackagespace"
              id="inputspace"
              class="form-control py-3"
              placeholder="Enter Product Price"
            />
          </div>


          <div class="bg-label-success p-4">
            <h6>Please Do not add any foreign Object else we will reject the parameters ( . , $ ).. just place your plan Amount you Intend to Update..Thanks </h6>
          </div>

         
  
       
       
        </div>
  
        <!-- Submit Button -->
        <div class="py-1   px-5">
          <button class="bg-button-submit btn py-3" @click="submitProduct">
            {{ isLoadingProduct ? '....' : 'Update Product' }}
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
import { useToast } from 'vue-toastification';
import axios from 'axios';
import { Inertia } from '@inertiajs/inertia';

  export default {
    
    props: {
      addVisible: Boolean, // Modal visibility
      cat:Array,
      isLoadingProduct: Boolean,
      dataModal:Object,
    },
    data() {
      return {
        inputname: '',
        inputprice: '',
        inputweight: '',
        isLoadingProduct: false,
        kgcall: '',
        category: '',
        image: null,
        fileName: null, 
      };
    },
    methods: {
      // Handle the form submission
     async submitProduct()  {
        const toast = useToast();
          if (!this.dataModal.networkprice || !this.dataModal.networkid || !this.dataModal.networkplan || !this.dataModal.networkpackagespace) {
            console.log('Please fill out all fields.');
            toast.success('Please fill out all fields', {
              hideProgressBar: true,
              timeout:3000,
            });
            return;
          }
        try{
          this.isLoadingProduct = true;
        const response =  await axios.post('/cheapx/auth/updatedata',this.dataModal,{
          'Content-Type': 'application/json',
        });
        const toast = useToast();

        console.log(response.data);
        
          toast.success(response.data.message, {
            timeout: 3000,
            hideProgressBar:true,
          });
          console.log(`Not successful  ${response.status}`);

          Inertia.reload();
       

      }catch(error){
        console.log('Error Stack Log', error);
      }
      finally{
        this.isLoadingProduct = false;
      }
     
  
        // this.$emit('submitmodal', formData);
        // this.$emit('close');
      },
    },
  };
  </script>
 