<script setup>
 import { Link } from '@inertiajs/vue3';
 const getImage =  (filename) => `/bankimages/${filename}`;
 const getGenerealName =  (filename) => `/general/${filename}`;
</script>

<template>
  <div>
    <div class="front-main bg-decoration-title" :style="{
      backgroundImage: 'url(' + getImage('regedbackground.png') + ')',
      backgroundSize: 'cover',
      backgroundRepeat: 'no-repeat',
      backgroundPosition: 'center'
    }">
    <div class="front-header-container">
      <div class="front-header-content">
       
          <div class="logo-container d-flex gap-3 align-items-center">
            <img :src="getImage('color.png')" 
            width="30"
            alt="_Logo">
            <span class="fs-3 fw-bold text-dark">Cheapexpense</span>
          </div>

          <div class="">
            <Link href="" class="button-actions">
              <span>Download our App</span>
            </Link>
          </div>
      </div>
    </div>


    <section>
      <div class="front-hero-section">
        <div class="front-hero-content ">
            <div class="row gx-5 gy-1">
              <div class="col-lg-6 col-12">
                <div class="fr-inner-layer">
                  <span class="header-bigtext mb-1">Giving Ease where one warmth feels warm</span>
                  <span class="mb-3">From Virtual Card Transaction to more, TV Subscription, Airtime, Data and more
                    Subscription all at your tip.
                  </span>

                  <div class="d-flex gap-3">
                    <a href="/google" class="text-decoration-none">
                      <img 
                      :src="getGenerealName('android.png')"
                      width="150"
                      alt="_image">
                    </a>

                    <a href="/apple" class="text-decoration-none">
                      <img 
                      :src="getGenerealName('apple.png')"
                      width="150"
                      alt="_image">
                    </a>

                  </div>


                </div>
              </div>
              <div class="col-lg-6 col-12">
                <div class="image-relay">
                  <img
                   :src="getImage('homeimage.png')"
                   class="img-fluid"
                   alt="">
                </div>
              </div>
            </div>
        </div>
      </div>
    </section>


    <section>
      <div class="front-hero-section">
        <div class="front-hero-content-about">
           <div class="p-2 d-flex flex-column gap-2">
            <span class="text-uppercase text-dark fw-normal">how it works</span>
            <span class="fs-1 text-dark">We have articulate all from.</span>
           </div>

           <div class="d-flex flex-column py-3">
            <div class="row gx-3 gy-1">
              <div class="col-lg-4 col-12">
                 <div class="front-icons-contents">
                    <span>
                      <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><!-- Icon from Solar by 480 Design - https://creativecommons.org/licenses/by/4.0/ --><path fill="currentColor" fill-rule="evenodd" d="M5.796 18.204L21.512 2.488c-.988-.989-2.86-.364-6.606.884l-9.331 3.11c-2.082.694-3.123 1.041-3.439 1.804q-.112.271-.133.564c-.059.824.717 1.6 2.269 3.151l.283.283c.254.254.382.382.478.523c.19.28.297.607.31.945c.008.171-.019.35-.072.705c-.196 1.304-.294 1.956-.179 2.458c.114.495.362.938.704 1.289" clip-rule="evenodd"/><path fill="currentColor" d="m17.498 18.486l3.13-9.392c1.25-3.745 1.873-5.617.885-6.606L5.797 18.204c.348.356.794.617 1.296.74c.5.122 1.153.033 2.46-.144l.071-.01c.369-.05.553-.075.73-.064c.32.02.63.124.898.303c.147.099.278.23.541.493l.251.251c1.51 1.51 2.266 2.265 3.067 2.226c.22-.01.438-.062.64-.151c.734-.323 1.072-1.336 1.747-3.362" opacity=".5"/></svg>
                    </span>
                    <span class="fs-2 mb-2 fw-bold text-dark">Fast Payment Catch</span>
                    <span class="fs-4">
                      Lightning fast, from making purchases of any services we have and having in return all in that percent time.
                    </span>
                 </div>
              </div>

              <div class="col-lg-4 col-12">
                <div class="front-icons-contents">
                   <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><!-- Icon from Solar by 480 Design - https://creativecommons.org/licenses/by/4.0/ --><path fill="currentColor" fill-rule="evenodd" d="M5.25 10.055V8a6.75 6.75 0 0 1 13.5 0v2.055c1.115.083 1.84.293 2.371.824C22 11.757 22 13.172 22 16s0 4.243-.879 5.121C20.243 22 18.828 22 16 22H8c-2.828 0-4.243 0-5.121-.879C2 20.243 2 18.828 2 16s0-4.243.879-5.121c.53-.531 1.256-.741 2.371-.824M6.75 8a5.25 5.25 0 0 1 10.5 0v2.004Q16.676 9.999 16 10H8q-.677-.001-1.25.004zM8 17a1 1 0 1 0 0-2a1 1 0 0 0 0 2m4 0a1 1 0 1 0 0-2a1 1 0 0 0 0 2m5-1a1 1 0 1 1-2 0a1 1 0 0 1 2 0" clip-rule="evenodd"/></svg>
                   </span>
                   <span class="fs-2 mb-2 fw-bold text-dark">Secure and Reliable</span>
                   <span class="fs-4">
                    We use the latest security protocols to ensure that your data is safe and secure.
                   </span>
                </div>
             </div>

             <div class="col-lg-4 col-12">
              <div class="front-icons-contents">
                 <span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><!-- Icon from Solar by 480 Design - https://creativecommons.org/licenses/by/4.0/ --><path fill="currentColor" fill-rule="evenodd" d="M12 22c-1.886 0-2.828 0-3.414-.586S8 19.886 8 18s0-2.828.586-3.414S10.114 14 12 14s2.828 0 3.414.586S16 16.114 16 18s0 2.828-.586 3.414S13.886 22 12 22m2.25-4.862a.667.667 0 0 0-.944-.943l-2.195 2.195l-.417-.417a.667.667 0 0 0-.943.943l.889.889c.26.26.682.26.942 0z" clip-rule="evenodd"/><path fill="currentColor" d="M6.5 18v-.09c0-.865 0-1.659.087-2.304c.095-.711.32-1.463.938-2.08c.618-.619 1.37-.844 2.08-.94c.646-.086 1.44-.086 2.306-.086h.178c.866 0 1.66 0 2.305.087c.711.095 1.463.32 2.08.938c.619.618.844 1.37.94 2.08c.085.637.086 1.416.086 2.267c2.573-.55 4.5-2.812 4.5-5.52c0-2.47-1.607-4.572-3.845-5.337C17.837 4.194 15.415 2 12.476 2C9.32 2 6.762 4.528 6.762 7.647c0 .69.125 1.35.354 1.962a4.4 4.4 0 0 0-.83-.08C3.919 9.53 2 11.426 2 13.765S3.919 18 6.286 18z"/></svg>
                 </span>
                 <span class="fs-2 mb-2 fw-bold text-dark">Made For You</span>
                 <span class="fs-4">
                   Get Competitive prices, that match what you pay no extra
                 </span>
              </div>
            </div>

            </div>
            
           </div>
          </div>
        </div>
    </section>


    <section>
      <div class="front-hero-section-rix bg-light-color">
        <div class="front-hero-content ">
          <div class="row gx-5 gy-1">
            <div class="col-lg-6 col-12">
              <div class="fr-inner-layer">
                <span class="header-bigtext mb-1">Transact with swift and gain response on time.</span>
                <span class="mb-3">Get all information in response of your purchase on time with no delay at any cost, all services built just for you.
                </span>

                <div class="d-flex gap-3">
                  <a href="/google" class="text-decoration-none">
                    <img 
                    :src="getGenerealName('android.png')"
                    width="150"
                    alt="_image">
                  </a>

                  <a href="/apple" class="text-decoration-none">
                    <img 
                    :src="getGenerealName('apple.png')"
                    width="150"
                    alt="_image">
                  </a>

                </div>


              </div>
            </div>
            <div class="col-lg-6 col-12">
              <div class="image-relay image-toplayer">
                <img
                 :src="getImage('transact.png')"
                 class="img-fluid"
                 alt="">
              </div>
            </div>
          </div>
      </div>
        </div>
    </section>




    <section>
      <div class="front-footer">
        <div class="front-hero-content-about">
          <div class="row gx-1 gy-1  w-100 mb-5">

            <div class="col-lg-6 col-12 mb-3">
              <div class="logo-container d-flex gap-3 align-items-center">
                <img :src="getImage('color.png')" 
                width="30"
                alt="_Logo">
               <div class="d-flex flex-column">
                <span class="fs-3 fw-bold text-dark">Cheapexpense</span>
                <span>Making More Accessible</span>
               </div>
              </div>
            </div>

            <div class="col-lg-6 col-12 d-flex justify-content-start justify-content-lg-end align-items-center">
              <div class="d-flex gap-3">
                <Link href="">
                  <div class="br-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><!-- Icon from Google Material Icons by Material Design Authors - https://github.com/material-icons/material-icons/blob/master/LICENSE --><path fill="currentColor" d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95"/></svg>
                  </div>
                </Link>

                <Link href="">
                  <div class="br-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><!-- Icon from Material Line Icons by Vjacheslav Trushkin - https://github.com/cyberalien/line-md/blob/master/license.txt --><circle cx="17" cy="7" r="1.5" fill="currentColor" fill-opacity="0"><animate fill="freeze" attributeName="fill-opacity" begin="1.3s" dur="0.15s" values="0;1"/></circle><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path stroke-dasharray="72" stroke-dashoffset="72" d="M16 3c2.76 0 5 2.24 5 5v8c0 2.76 -2.24 5 -5 5h-8c-2.76 0 -5 -2.24 -5 -5v-8c0 -2.76 2.24 -5 5 -5h4Z"><animate fill="freeze" attributeName="stroke-dashoffset" dur="0.6s" values="72;0"/></path><path stroke-dasharray="28" stroke-dashoffset="28" d="M12 8c2.21 0 4 1.79 4 4c0 2.21 -1.79 4 -4 4c-2.21 0 -4 -1.79 -4 -4c0 -2.21 1.79 -4 4 -4"><animate fill="freeze" attributeName="stroke-dashoffset" begin="0.7s" dur="0.6s" values="28;0"/></path></g></svg>                  </div>
                </Link>

                <Link href="">
                  <div class="br-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><!-- Icon from Google Material Icons by Material Design Authors - https://github.com/material-icons/material-icons/blob/master/LICENSE --><path fill="currentColor" d="M19.05 4.91A9.82 9.82 0 0 0 12.04 2c-5.46 0-9.91 4.45-9.91 9.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21c5.46 0 9.91-4.45 9.91-9.91c0-2.65-1.03-5.14-2.9-7.01m-7.01 15.24c-1.48 0-2.93-.4-4.2-1.15l-.3-.18l-3.12.82l.83-3.04l-.2-.31a8.26 8.26 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24c2.2 0 4.27.86 5.82 2.42a8.18 8.18 0 0 1 2.41 5.83c.02 4.54-3.68 8.23-8.22 8.23m4.52-6.16c-.25-.12-1.47-.72-1.69-.81c-.23-.08-.39-.12-.56.12c-.17.25-.64.81-.78.97c-.14.17-.29.19-.54.06c-.25-.12-1.05-.39-1.99-1.23c-.74-.66-1.23-1.47-1.38-1.72c-.14-.25-.02-.38.11-.51c.11-.11.25-.29.37-.43s.17-.25.25-.41c.08-.17.04-.31-.02-.43s-.56-1.34-.76-1.84c-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31c-.22.25-.86.85-.86 2.07s.89 2.4 1.01 2.56c.12.17 1.75 2.67 4.23 3.74c.59.26 1.05.41 1.41.52c.59.19 1.13.16 1.56.1c.48-.07 1.47-.6 1.67-1.18c.21-.58.21-1.07.14-1.18s-.22-.16-.47-.28"/></svg>                  </div>
                </Link>

                <Link href="">
                  <div class="br-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><!-- Icon from Remix Icon by Remix Design - https://github.com/Remix-Design/RemixIcon/blob/master/License --><path fill="currentColor" d="M6.94 5a2 2 0 1 1-4-.002a2 2 0 0 1 4 .002M7 8.48H3V21h4zm6.32 0H9.34V21h3.94v-6.57c0-3.66 4.77-4 4.77 0V21H22v-7.93c0-6.17-7.06-5.94-8.72-2.91z"/></svg>                  </div>
                </Link>

              </div>
            </div>
          </div>


          <div class="col-lg-12">
            <div class="d-flex flex-column">
              <span class="mb-4">
                Cheapexpense is a leading payment service provider that enables users to easily and securely pay for various bills and subscriptions, including electricity, internet, airtime, data bundles, education bills, cable TV, and more.
              </span>

              <span class="fw-bold text-dark mb-5">
                © 2024 Cheapexpense. All rights reserved.
              </span>


              <span class="fw-bold text-dark text-center">
                <a href="https://osarotechlimited.com" class="text-decoration-underline text-dark">
                  Powered by: OsaroTechLimited
                </a>
              </span>


            </div>
          </div>
        </div>
      </div>
    </section>

   </div>


  </div>
</template>