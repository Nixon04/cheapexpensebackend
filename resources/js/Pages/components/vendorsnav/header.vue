<template>
  <div class="mealxpress-header">
    <div class="d-flex justify-content-between" style="display: flex; justify-content:space-between">
      <div class="d-flex">
        <div @click="toggleAside" class="toggle-button"><l class="fas fa-bars-staggered cursor-pointer" style="font-size: 20px!important;"></l></div>
      </div>
      <div class="d-flex">
        <img src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp" style="height: 40px; width: 40px;" alt="" class="rounded-circle border-standard cursor-pointer">
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    userid: {
      type: String,
      required: false,
    },
  },
  data() {
    return {
      isAsideVisible: false, // State for showing/hiding the aside (only in the header)
    };
  },
  methods: {
    toggleAside() {
      this.$emit('toggle-aside');
      console.log('toggle');
    },
  },
};
</script>

<style scoped>
/* Add any necessary styles here */
</style>
