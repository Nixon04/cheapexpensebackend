<template>
    <div class="bg-drawer-center" v-if="addVisible">
      <div class="bg-drawer-center-container">
        <div class="d-flex justify-content-between px-5 py-5">
          <h1 class="fs-5 fw-bold">Register Drivers</h1>
          <div class="bg-circle" @click="$emit('close')">
            <i class="fas fa-x"></i>
          </div>
        </div>
        <div class="container-scrollable px-5">
          <!-- Product Name Input -->
          <div class="form-input mb-3">
            <label for="first">FirstName</label>
            <input
              type="text"
              v-model="firstname"
              id="first"
              class="form-control py-3"
              placeholder="Enter Product Name"
            />
          </div>

          
  
          <!-- Product Price Input -->
          <div class="form-input mb-3">
            <label for="last">Last Name</label>
            <input
              type="text"
              v-model="lastname"
              id="last"
              class="form-control py-3"
              placeholder="Enter Drivers Last Name"
            />
          </div>

          <div class="form-input mb-3">
            <label for="email">Email Address</label>
            <input
              type="email"
              v-model="email"
              id="last"
              class="form-control py-3"
              placeholder="Enter Email Address"
            />
          </div>

          <div class="form-input mb-3">
            <label for="contact">Contact</label>
            <input
              type="number"
              v-model="contact"
              id="last"
              class="form-control py-3"
              placeholder="Enter Contact"
            />
          </div>


          <div class="form-input mb-3">
            <label for="password">Password</label>
            <input
              type="password"
              v-model="Dpassword"
              id="password"
              class="form-control py-3"
              placeholder="Enter Password"
            />
          </div>

  
          <!-- Product Weight Input -->
          <div class="form-input mb-3">
            <label for="kin">Next of Kin</label>
            <input
              type="text"
              v-model="kin"
              id="kin"
              class="form-control py-3"
              placeholder="Enter Next of Kin Name"
            />
          </div>

          <div class="form-input mb-3">
            <label for="contact">Kin Contact</label>
            <input
              type="number"
              maxlength="11"
              v-model="kincontact"
              id="last"
              class="form-control py-3"
              placeholder="Enter Kin Contact"
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
            {{ isLoadingProduct ? '....' : 'Add Drivers' }}
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import {useToast} from 'vue-toastification';
  export default {
  
    props: {
      addVisible: Boolean, // Modal visibility
      cat:Array,
      isLoadingProduct: Boolean,
    },
    data() {
      return {
        firstname: '',
        lastname: '',
        email: '',
        contact: '',
        kin: '',
        kincontact: '',
        trackerid: '',
        Dpassword: '',
        image: null,

      };
    },
    methods: {

      validateNumber(){
        if (this.kincontact.length > 11) {
        this.kincontact = this.kincontact.slice(1, 11);
      }
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
  
      // Trigger the hidden file input when clicked
      triggerFileInput() {
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
      
        if (!this.firstname || !this.lastname || !this.email || !this.image || !this.Dpassword
        || !this.kin || !this.kincontact
        ) {
          const toast = useToast();
          toast.info('Please fill out the required fields',{
            hideProgressBar:true,
            timeout:3000,
          });
          console.log('Please fill out all fields and upload an image.');
          return;
        }
        // Prepare the form data to send to the backend
        const formData = new FormData();
        formData.append('firstname', this.firstname);
        formData.append('lastname', this.lastname);
        formData.append('email', this.email);
        formData.append('contact', this.contact);
        formData.append('password', this.Dpassword);
        formData.append('kin', this.kin);
        formData.append('kincontact', this.kincontact);
        formData.append('imageprofile', this.file);
      
        this.$emit('submitmodal', formData);
        // this.$emit('close');
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
  