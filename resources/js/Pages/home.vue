<template>
  <div class="main-content">
  <NavBarBody/>
    <div class="main-body-content bg-main-color">
      <div class="p-5 bg-alert-msg position-relative bg-main-color">
         <div class="alert-msg-content text-center">
            <h4 class="fs-1 text-dark bg-font-big text-white">Enjoy seamless top up with ease and safety </h4>
              <h6 class="text-mute text-white">Cheapexpense is your goto version for easier top up to your airtime, data subscription, never miss a day subscribing to your 
                favourite Tv show, or go blanked out on your Electricity bills. 
              </h6>
         </div>
         <div class="d-flex justify-content-center gap-3 py-2">
          <Link href="/"><img :src="getGeneral('android')" alt="" width="120"/></Link>
          <Link href="/"><img :src="getGeneral('apple')" alt="" width="120"/></Link>
         </div>
      </div>

      <div class="text-center position-relative">
        <img :src="getGeneral('board of introduction')"  alt="" class="img-fluid px-4">
      </div>
      <div class="d-flex hero-pad-content p-4 bg-main-color">
        <div class="hero-pad-general w-100">
          <div class="hero-left-pad  position-relative overflow-hidden override-main" >
            <div class="hero-main-content first-bg-state">
              <img :src="getGeneral('firststage')" alt="" class="img-fluid ">
            </div>
          </div>
           <div class="hero-right-pad align-item-center position-relative override-main">
            <div class="hero-right-main hero-main-content first-bg-state ">
              <img :src="getGeneral('set2')" class="bg-main-image" alt="">
            </div>
          </div>
        </div>
      </div>
     </div>

     <section class="bg-activities first-bg-state m-0">
      <div class="alert-msg-content text-center pad-up mb-5">
        <h4 class="fs-1 text-dark bg-font-big text-whit">Important Guide to establish a smooth connection with us </h4>
          <h6 class="text-whit">Secure your chance to enjoy premium package with us
          </h6>
     </div>
     <div class="bg-activities-card-layout">
      <div class="col-12">
        <div class="row px-4">
          <div class="col-lg-4 col-md-4 col-12 mb-3">
            <div class="card p-5 shadow-none bg-outline-card first-bg-state">
               <div class="d-flex color-bg-holder1 mb-2">
                <i class="fa-regular fa-chess-queen p-2"></i> 
               </div>
               <h4 class="text- fw-bold">Create an account</h4>
               <h6 class="text- space-x-3">Register on our mobile app, pass in your necessary details  as we tends to process your data to give you discounts on holidays and your birthdays</h6>
            </div>
          </div>

          <div class="col-lg-4 col-md-4 col-12 mb-3">
            <div class=" p-5  bg-lone-card first-bg-state">
               <div class="d-flex color-bg-holder1 mb-2">
                <i class="fa-regular fa-chess-queen p-2"></i> 
               </div>
               <h4 class="fw-bold fs-4">Pay for Services</h4>
               <h6>For every service bought we offer you a discount to enjoy from, never miss any day from our vouchers and incredible discounts on each purchase</h6>
            </div>
          </div>

          <div class="col-lg-4 col-md-4 col-12 mb-3">
            <div class="card p-5 shadow-none bg-outline-card mb-2 first-bg-state">
               <div class="d-flex color-bg-holder1">
                <i class="fa-regular fa-chess-queen p-2"></i> 
               </div>
               <h4 class="fw-bold">Refer & Earn</h4>
               <h6>For every user you bring to our platform we offer you the sum of NGN 150 cashback to your referral balance on your will to push back to your main account</h6>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="bg-achievement-rate first-bg-state" >
    <div class="bg-forward-holder">
      <div class="bg-forward-pad">
        <div class="column column-forward ">
          <h4 class="fw-bold fs-2 text-white">It's simpler with us when you top up</h4>
          <h6 class="text-white text-">Instant top up to all your subscription worries and more...</h6>
        </div>
            <div class="text-center">

              <a
              href="https://www.facebook.com/profile.php?id=61566769515133&mibextid=LQQJ4d"
              target="_blank"
              rel="noopener noreferrer"
            >
              <i class="fa-brands fa-facebook text-white me-4"></i>
            </a>

              <a
              href="https://www.instagram.com/cheapexpense?igsh=cjJuZjE1ZHNkaWds&utm_source=qr"
              target="_blank"
              rel="noopener noreferrer"
            >
              <i class="fa-brands fa-instagram text-white"></i>
            </a>
            </div>
      </div>

    

    </div>
  </section>

  <!-- <section class="bg-achievement-rate bg-main-color">
    <div class="main-row-achievement" >
       <div class="row">
        <div class="col-12">
           <div class="hero-pad-general" style="background: #000;">
            <div class="hero-left-pad position-relative bg-main-color">
                <div class="hero-main-content ">
                  <img :src="getGeneral('set3')" class="bg-main-image" alt="">
                </div>
            </div>
            <div class="hero-right-pad position-relative bg-white">
               <div class="hero-main-conten px-3 py-4">
                <h4 class="fw-bold fs-m-1">Speed Up with our ease signup </h4>
                <h6>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Earum magnam repellat, eius alias non quas obcaecati soluta delectus laborum nemo totam porro perspiciatis repudiandae amet reprehenderit consectetur deserunt eligendi! Ad.</h6>
               </div>
            </div>
           </div>
        </div>
       </div>
    </div>
  </section> -->
     

<!-- 
  <section class="bg-achievement-rate">
    <div class="main-row-achievement">
       <div class="row">
        <div class="col-12">
           <div class="hero-pad-general">
            <div class="hero-right-pad position-relative">
              <div class="hero-main-conten px-3 py-4">
               <h4 class="fw-bold fs-1">Boost your financial track, Get updated how you spend and what you need most</h4>
              </div>
           </div>

            <div class="hero-left-pad position-relative">
                <div class="hero-main-content">
                  <img :src="getGeneral('set2')" class="bg-main-image" alt="">
                </div>
            </div>
           
           </div>
        </div>
       </div>
    </div>
  </section> -->

  <!-- <section class="bg-achievement-rate">
    <div class="main-row-achievement">
       <div class="row">
        <div class="col-12">
           <div class="hero-pad-general">
            <div class="hero-left-pad position-relative">
                <div class="hero-main-content">
                  <img :src="getGeneral('set1')" class="bg-main-image" alt="">
                </div>
            </div>
            <div class="hero-right-pad position-relative">
               <div class="hero-main-conten px-3 py-4">
                <h4 class="fw-bold fs-1">Boost your financial track, Get updated how you spend and what you need most</h4>
               </div>
            </div>
           </div>
        </div>
       </div>
    </div>
  </section>
      -->

    </div>
</template>

<script>
  import NavBarBody from './navigation/navcomponent.vue';
  // Import Font Awesome CSS and JS
  import '@fortawesome/fontawesome-free/css/all.css';
  import '@fortawesome/fontawesome-free/js/all.js';
  import '../assets/demo/landing.css';
  import '../assets/mainset.css';
  import '../assets/demo/mediaquery.css';


 export default{
  components:{
    NavBarBody,
  },
  methods:{
      getImage(name){
        return `/bankimages/${name}.png`;
      },
      getGeneral(name){
        return `/general/${name}.png`;
      }
    }
 };
</script>