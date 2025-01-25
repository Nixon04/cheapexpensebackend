<template>
    <div class="bg-drawer-center" v-if="addVisible">
      <div class="bg-drawer-center-container">
        <div class="d-flex justify-content-between px-5 py-5">
          <h1 class="fs-5 fw-bold">Register Vendors</h1>
          <div class="bg-circle" @click="$emit('close')">
            <i class="fas fa-x"></i>
          </div>
        </div>
        <div class="container-scrollable px-5">

          <div class="form-input mb-3">
            <label for="contactname">Contact(Digits)</label>
              <input
                type="number"
                v-model="vendorContact"
                id="contactname"
                class="form-control py-3"
                placeholder="Enter Contact"
              />
          </div>


          <div class="form-input mb-3">
            <label for="password">Password</label>
            <input
              type="password"
              v-model="vendorsPassword"
              id="password"
              class="form-control py-3"
              placeholder="Enter Unique Password"
            />
          </div>


          <div class="form-input mb-3">
            <label for="emailname">Email Address</label>
            <input
              type="text"
              v-model="vendorEmail"
              id="emailname"
              class="form-control py-3"
              placeholder="Enter Email Address"
            />
          </div>

          <div class="form-input mb-3">
            <label for="senderEmail">SenderEmail</label>
            <input
              type="text"
              v-model="vendorSenderMail"
              id="senderEmail"
              class="form-control py-3"
              placeholder="Enter Sender Mail"
            />
          </div>

          <div class="form-input mb-3">
            <label for="business">LegalBusiness</label>
            <input
              type="text"
              v-model="vendorBusiness"
              id="business"
              class="form-control py-3"
              placeholder="Enter Legal Business Name"
            />
          </div>

          <div class="form-input mb-3">
            <label for="business">City</label>
            <input
              type="text"
              v-model="vendorCity"
              id="city"
              class="form-control py-3"
              placeholder="Enter city"
            />
          </div>

          <div class="form-input mb-3">
            <label for="business">State</label>
            <input
              type="text"
              v-model="vendorState"
              id="state"
              class="form-control py-3"
              placeholder="State In Nigeria"
            />
          </div>

          
  
     <div class="d-flex mb-2 bg-align-center">
        <div class="highlight"></div>
           <span>Stores</span>
        <div class="highlight"></div>
    </div>


    <div class="form-input mb-3">
        <label for="store">Store Name</label>
        <input
          type="text"
          v-model="vendorstorename"
          id="store"
          class="form-control py-3"
          placeholder="Vendors Store"
        />
      </div>

      <div class="form-input mb-3">
        <label for="vendorbio">MarketBio(Description of your Business)</label>
         <textarea v-mode="vendorbio" rows="3" class="form-control"></textarea>
      </div>
       
        <div class="form-input mb-3">
            <label for="storecollection">Choose Market</label>
            <select v-model="vendorbio" id="storecollection" class="form-select py-3">
                <option selected disabled>Choose Market</option>
                <option value="localmarket">LocalMarket</option>
                <option value="supermarket">SuperMarket</option>
            </select>
       </div> 


      <div class="form-input mb-3">
        <label for="vendoraddress">Address (Description of your Business Area)</label>
         <textarea v-model="vendoraddress" id="vendoraddress" rows="3" class="form-control"></textarea>
      </div>
       


      <div class="form-input mb-3">
        <label for="hours">Working Time</label>
        <select class="form-select py-3" v-model="vendorhours">
            <option selected disabled>Working Time Duration</option>
                <option value="8AM - 12AM">8AM - 12AM</option>
                <option value="8AM - 12AM">12PM - 4PM</option>
                <option value="8AM - 12AM">4PM - 9AM</option>
        </select>
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
            {{ isOptionLoading ? 'Loading...' : 'Add Vendors' }}
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
      addVisible: Boolean, // Modal visibility
      cat:Array,
      isOptionLoading:Boolean,
    },
    data() {
      return {
        vendorId: '',
        vendorContact: '',
        vendorEmail: '',
        vendorSenderMail: '',
        vendorBusiness: '',
        vendorCity: '',
        vendorState: '',
        vendorstorename: '',
        vendorbio: '',
        vendoraddress: '',
        vendorsPassword: '',
        vendorhours: '',
        image: null,
      };
    },
    methods: {

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
        if (!this.vendorContact || !this.vendorEmail || !this.vendorSenderMail
         || !this.vendorBusiness || !this.vendorCity || !this.vendorState ||
          !this.vendorstorename || !this.vendorbio || !this.vendoraddress || 
          !this.vendorhours || !this.vendorsPassword || !this.file 
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
        formData.append('vendorId', this.vendorId)
        formData.append('vendorContact', this.vendorContact);
        formData.append('vendorEmail', this.vendorEmail);
        formData.append('vendorSenderMail', this.vendorSenderMail);
        formData.append('vendorBusiness', this.vendorBusiness);
        formData.append('vendorCity', this.vendorCity);
        formData.append('vendorState', this.vendorState);
        formData.append('vendorStoreName', this.vendorstorename);
        formData.append('vendorBio', this.vendorbio);
        formData.append('vendorAddress', this.vendoraddress);
        formData.append('vendorhours', this.vendorhours);
        formData.append('vendorsPassword', this.vendorsPassword);
        formData.append('productImage', this.file); // Send the image data
        this.$emit('submitmodal', formData);
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
  