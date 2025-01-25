<template>
    <div class="main-content bg-main-color py-3">
     <div class="d-flex justify-content-between bg-navbar-color ">
        <nav class="d-flex">
          <ul class=" d-flex gap-5  align-item-center">
            <Link href="./">
              <img :src="getImage('logo')" class="fav-main-image" alt="logo">
            </Link>
          </ul> 
        </nav>
       
        <!-- <nav class="d-flex">
           <Link class="btn btn-cheapexpense-main">Log in</Link>
           <Link class="btn btn-cheapexpense-main-outline">Get Started </Link>
        </nav> -->
     </div>


     <!-- main hero section -->

     
    </div>
</template>

<script>

import { Link } from '@inertiajs/vue3';
import '../../assets/mainset.css';
export default{
    name:'NavBarBody',
    methods:{
      getImage(name){
        return `/bankimages/${name}.png`;
      },
      getGeneral(name){
        return `/general/${name}.png`;
      }
    }
}
</script>