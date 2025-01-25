<template>
    <div v-if="isVisible" class="bg-dialog-modal-container">
        <div class="bg-dialog-pad">
            <div class="header ms-4 me-4 py-3 d-flex justify-content-between">
                <h1 class="fs-3 fw-bold">Product List</h1>
               <div class="bg-circle" @click="$emit('close')">
                    <div class="fas fa-x fas-sm" ></div>
                </div>
            </div>
            <div class="bg-scrollable-meal">
                <div  v-if="loading" class="d-flex ">
                    <div class="rounded">
                        <div class="flex mb-4 w-100">
                            <Skeleton shape="circle" size="4rem" class="mr-2 shimmer-color"></Skeleton>
                            <div>
                                <Skeleton width="10rem" class="mb-2 shimmer-color p-4" style="background-color: #f1f1f1"></Skeleton>
                                <Skeleton width="5rem" class="mb-2 p-4 shimmer-padding"  style="background-color: #f1f1f1"></Skeleton>
                                <Skeleton height=".5rem" class="p-4"  style="background-color: #f1f1f1"></Skeleton>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else-if="ModalData" class="meal-details mb-5">
                    <div v-for="(item, index) in ModalData" :key="item.id" class="mb-4">
                        <h2>{{ item.productname }}</h2>
                        <p>Price: {{ item.price }}</p>
                        <p>Total: {{ item.total }}</p>
                        {{ item.cartrefcode }}
                        <p>Weight: {{ item.cartweight }} kg</p>
                        <img :src="item.cartimage" alt="Meal Image" style="width:40px;height:40px" class="mb-2 "/>
                        <hr />
                      </div>
                  </div>

                  <div :class="[isLoading ? 'bg-lazyloading text-center py-5 cursor-pointer' : 'bg-button-submit text-center py-5 cursor-pointer']"  @click="submitrequest(ModalData[0]?.cartrefcode)">
                    <span class="text-white">{{isLoading ? 'Loading....' : 'Reject Order'}}</span>
                  </div>
            </div>
        </div>
    </div>
</template>

<script>

import {ref} from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

export default{
    props:{
        isVisible:Boolean,
        ModalData:Array,
        loading:Boolean,
    },
    methods:{
        closeModal(){
            this.$emit('close');
        }
    },
   setup(props, {emit}){
    const isLoading = ref(false);
    const toast = useToast();
    const delay = (ms) => new Promise(resolve => setTimeout(resolve, ms));
    const closeModal = () =>{
      emit('close');
    };
    const submitrequest = async (cartrefcode) => {
    try {
        isLoading.value = true;
        await delay(300);
        const payload = {
            itemupdate: "returns",
            cartref: cartrefcode, // Use the first cartrefcode
        };
        const response = await axios.post('/vendorspath/rejectorder', payload, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
        });

        if (response.status === 200) {
            if (response.data.status === "success") {
                toast.success(response.data.message, {
                    timeout: 2000,
                    hideProgressBar: true,
                });
                closeModal();
            } else {
                toast.info(response.data.message, {
                    timeout: 2000,
                    hideProgressBar: true,
                });
                closeModal();
            }
        } else {
            console.log('Unexpected status code:', response.status);
        }
    } catch (error) {
        console.error('Error occurred:', error);
        toast.error('An error occurred while processing your request.');
    } finally {
        isLoading.value = false;
    }
};
    return {
        isLoading,
        submitrequest,
        closeModal,
    }
   }
}
</script>

