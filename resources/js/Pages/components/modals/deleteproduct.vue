<template>
    <div class="deletecontainer" v-if="deleteVisible">
        <div class="deletepadding">
            <div class="d-flex justify-content-end">
                <div class="bg-circle" @click="closeModal" >
                    <i class="fas fa-x"></i>
                </div>
            </div>
          <div class="text-center">
            <input type="text" v-model="identifier.targetitem" class="d-none">
            <h1>Are you sure?</h1>
            <h6 class="mb-4">Deleting this product is permanent</h6>
            <button 
              :class="[deleteloading ? 'loading btn' : 'btn bg-button-submit']"
              :disabled="deleteloading"
              @click="deleteState" >
              {{ deleteloading ? '...' : 'Delete Now' }}
            </button>
          
          </div>
        </div>
    </div>
</template>

<script>
 export default {
    setup(_, { emit }) {
        const deleteState = () => {
            emit('deletesubmit');
        };
        const closeModal = () => {
            emit('close');
        }
        return {
            deleteState,
            closeModal,
        };
    },
    props: {
        deleteVisible: Boolean,
        deleteloading: Boolean,
        identifier: Object,
    },
 }
</script>

<style scoped>
/* Add a 'loading' class to apply spinner or other loading styles */
.loading {
    background: #f0f0f0;
    cursor: not-allowed;
}
</style>
