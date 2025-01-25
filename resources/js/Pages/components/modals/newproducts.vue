<template>
    <div class="bg-drawer" v-if="isVisible">
       <div class="bg-drawers-container">
        <div class="bg-drawer-header">
            <div class="d-flex justify-content-between">
                <h1 class="fs-5">Edit Products</h1>
                <div class="bg-circle" @click="$emit('close')" >
                    <i class="fas fa-x"></i>
                </div>
            </div>
        </div>
        <div class="bg-scrollable-drawer">
           <div class="bg-content-stepper">
            <div class="form-input mb-4 d-none">
                <label for="ProductName">Product Name</label>
                <input type="text" id="ProductName" v-model="ModalData.productID"  class="form-control py-3" placeholder="">
             </div>

             <div class="form-input mb-4">
                <label for="ProductName">Product Name</label>
                <input type="text" id="ProductName" v-model="ModalData.productName"  class="form-control py-3" placeholder="">
             </div>
             <div class="form-input mb-4">
                <label for="ProductPrice">Product Price</label>
                <input type="text" id="ProductPrice" v-model="ModalData.productPrice" class="form-control py-3" placeholder="">
             </div>
             <div class="form-input mb-4">
                <label for="ProductWeight">Product Weight</label>
                <input type="text" id="ProductWeight" v-model="ModalData.productWeight" class="form-control py-3" placeholder="">
             </div>
             <div class="form-input mb-2">
                <label for="ImageReplace">Image</label>
                <input type="file" id="ImageReplace" @change="onFileChange" class="form-control py-3" placeholder="">
             </div>
           </div>
        </div>

        <div class="bg-drawer-footer px-3">
           
            <button class="bg-button-submit btn" @click="submitData">

                {{  isLoadingUpdate ? 'Loading... ': 'Update Product' }}
            </button>
        </div>

       </div>
    </div>
</template>
<script>

   export default{
    props:{
      isVisible:Boolean,
      isLoadingUpdate:Boolean,
      ModalData:{
        type:Object,
        default: () => ({
          productID: '',
          productName: '',
          productPrice: '',
          productWeight: '',
        }),
      }
    },
    methods:{
        disclosemodel(){
         this.$emit('close');
        },
        submitData(){
            // Emit data back to parent Body
            this.$emit('submit', this.ModalData);
        },
        onFileChange(event){
            const File = event.target.files[0];
            this.$emit('file-change', File);
        }
     }
   }
</script>