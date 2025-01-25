<template>
    <div class="bg-drawer-center" v-if="showEditValue">
      <div class="bg-drawer-center-container">
        <div class="d-flex justify-content-between px-5 py-5">
          <h1 class="fs-5 fw-bold">Edit Drivers</h1>
          <div class="bg-circle" @click="$emit('close')">
            <i class="fas fa-x"></i>
          </div>
        </div>
        <div class="container-scrollable px-5">
          <!-- Product Name Input -->
       
          <div class="form-input mb-3">
            <label for="contactname">FirstName</label>
            <input
              type="text"
              v-model="dataHolder.firstname"
              id="contactname"
              class="form-control py-3"
              placeholder="Enter Contact"
            />
          </div>


          <div class="form-input mb-3">
            <label for="password">Last Name</label>
            <input
              type="text"
              v-model="dataHolder.lastname"
              id="password"
              class="form-control py-3"
              placeholder="Enter Last Name"
            />
          </div>


          <div class="form-input mb-3">
            <label for="emailname">Email Address</label>
            <input
              type="text"
              v-model="dataHolder.email"
              id="emailname"
              class="form-control py-3"
              placeholder="Enter Email Address"
            />
          </div>


          <div class="form-input mb-3">
            <label for="business">Contact</label>
            <input
              type="text"
              v-model="dataHolder.contact"
              id="business"
              class="form-control py-3"
              placeholder="Enter Contact Digits"
            />
          </div>

          <div class="form-input mb-3">
            <label for="business">Next Of Kin</label>
            <input
              type="text"
              v-model="dataHolder.nextofkin"
              id="city"
              class="form-control py-3"
              placeholder="Enter Next of Kin"
            />
          </div>

          <div class="form-input mb-3">
            <label for="business">Kin Contact</label>
            <input
              type="text"
              v-model="dataHolder.kincontact"
              id="state"
              class="form-control py-3"
              placeholder="Kin Contact"
            />
          </div>

          
  
     <div class="d-flex mb-2 bg-align-center">
        <div class="highlight"></div>
           <span>Tracker ID</span>
        <div class="highlight"></div>
    </div>


    <div class="form-input mb-3">
        <label for="store">Tracker ID</label>
        <input
          type="text"
          v-model="dataHolder.trackerid"
          id="store"
          class="form-control py-3"
          placeholder="Vendors Store"
          readonly
        />
      </div>
  
          <!-- File Drag-and-Drop Input -->
          <div class="form-input mb-3">
            <div
              class="drag-content-container"
              @dragover.prevent
              @drop="HandleFileDrop"
              @click="triggerFileInput"
            >
              <div class="content-write">
                <h1>{{image? '' : 'Drag or upload manually'}}</h1>
                <div v-if="image">
                    <img :src="image" alt="Product Image" class="uploaded-image" />
                  </div>
                <div class="d-none">
                  <input
                    type="file"
                    ref="fileInput"
                    style="display: none"
                    @change="HandleFileChange"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Submit Button -->
        <div class="py-4 px-5">
          <button class="bg-button-submit btn py-3" @click="submitProduct">
            {{ isOptionLoading ? 'Loading...' : 'Update' }}
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { useToast } from 'vue-toastification';
  import axios from 'axios';
  const toast = useToast();
  export default {
    
    props: {
        showEditValue: Boolean, // Modal visibility
      cat:Array,
      isOptionLoading:Boolean,
      dataHolder:{
        type:Object,
        required:true,
      },
    },
    data() {
      return {
        image: null,
        file:null,
      };
    },
    methods: {
        CloseEditModal(){
         this.$emit('close');
        },

      // Handle the file drop
      HandleFileDrop(event) {
        event.preventDefault();

        const file = event.dataTransfer.files[0];
        if(file){
            this.file  = file;
        }
        this.previewImage(file);
      },
  
                triggerFileInput() {
                if (!this.$refs || !this.$refs.fileInput) {
                    console.error("File input element not found.");
                    return;
                }
                this.$refs.fileInput.click();
            },

      // Handle file selection (manual file input)
      HandleFileChange(event) {
        const file = event.target.files[0];
        this.previewImage(file);
        if(file){
            this.file = file;
        }
      },
  
      // Preview the selected image
      previewImage(file) {
        const reader = new FileReader();
        reader.onload = () => {
          this.image = reader.result; // Store the image URL for preview

        };
        reader.readAsDataURL(file);
      },
  
      // Handle the form submission
      submitProduct() {
        if (!this.dataHolder.firstname || !this.dataHolder.lastname || !this.dataHolder.email
         || !this.dataHolder.contact || !this.dataHolder.nextofkin || !this.dataHolder.kincontact 
     
        ) {
          console.log('Please fill out all fields and upload an image.');

          toast.info('Required fields still empty',{
            timeout: 2000,
            hideProgressBar:true,
          });
          return;
        }
        // Prepare the form data to send to the backend
        const formData = new FormData();
        formData.append('vendorId', this.vendorId);
        formData.append('vendorContact', this.vendorContact);
        formData.append('vendorEmail', this.vendorEmail);
        formData.append('productImage', this.file); // Send the image data
  
        this.$emit('editsubmit', formData);

      },
    },
  };
  </script>
  
  <style scoped>
  .uploaded-image {
    max-width: 100%;
    max-height: 100px;
    margin-top: 10px;
  }
  </style>
  