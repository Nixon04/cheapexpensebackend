<template>
    
    <aside class="aside-mealxpress">
      <div class="flex-wrap-container">
          <div class="aside-header">
              <h1 class="text-white fs-5">Cheapxpense</h1>
          </div>
          <div class="aside-navigation">
              <ul class="ul-flex">
                  <Link href="/cheapx/dashboard/home" class="mb-3">
                      <li :class="['mealxpress-navlink', { active: $page.url === '/cheapx/dashboard/home' } ]">
                          <i class="fas fa-home mealxpress-icons" ></i>
                          Dashboard
                      </li>
                  </Link>

                  <Link href="/cheapx/dashboard/regusers" class="mb-3">
                    <li :class="['mealxpress-navlink', { active: $page.url === '/cheapx/dashboard/regusers' } ]">
                        <i class="fas fa-signal mealxpress-icons" ></i>
                       Reg Users
                    </li>
                </Link>

                  <Link href="/cheapx/dashboard/transactions" class="mb-3">
                      <li :class="['mealxpress-navlink', { active: $page.url === '/cheapx/dashboard/transactions' } ]">
                          <i class="fas fa-rocket mealxpress-icons" ></i>
                         Transactions
                      </li>
                  </Link>

                  <!-- <Link href="/cheapx/dashboard/airtime" class="mb-4" replace>
                      <li :class="['mealxpress-navlink', { active: $page.url === '/cheapx/dashboard/airtime' } ]">
                          <i class="fa fa-lemon mealxpress-icons" ></i>
                         Airtime Package
                      </li>
                  </Link> -->

                  <Link href="/cheapx/dashboard/data" class="mb-4" replace>
                    <li :class="['mealxpress-navlink', { active: $page.url === '/cheapx/dashboard/data' } ]">
                        <i class="fa fa-bolt mealxpress-icons" ></i>
                       Data Package
                    </li>
                </Link>

                  <Link href="/cheapx/dashboard/cable" class="mb-4">
                      <li :class="['mealxpress-navlink', { active: $page.url === '/cheapx/dashboard/cable' } ]">
                          <i class="fas fa-droplet mealxpress-icons" ></i>
                         Cable Package
                      </li>
                  </Link>

                  <!-- <Link href="/cheapx/dashboard/utility" class="mb-4">
                    <li :class="['mealxpress-navlink', { active: $page.url === '/cheapx/dashboard/utility' } ]">
                        <i class="fas fa-store mealxpress-icons" ></i>
                       Utility Package
                    </li>
                </Link> -->

                <Link href="/cheapx/dashboard/notification" class="mb-4">
                    <li :class="['mealxpress-navlink', { active: $page.url === '/cheapx/dashboard/notification' } ]">
                        <i class="fas fa-car mealxpress-icons" ></i>
                      Send Notification
                    </li>
                </Link>

                  <Link href="/cheapx/dashboard/logout" class="mb-4">
                      <li :class="['mealxpress-navlink', { active: $page.url === '/cheapx/dashboard/logout' } ]">
                          <i class="fa fa-cog mealxpress-icons" ></i>
                         LogOut
                      </li>
                  </Link>
              </ul>
          </div>
          <!-- <div class="aside-footer">
              <div class="aside-centered">
                  <h1 class=" fs-2 text-white">Having troubles</h1>
                  <h6 class="fs-7 text-white">We are active 24/7</h6>
              </div>
          </div> -->
      </div>
    </aside>
</template>

<script>
import {Link} from '@inertiajs/vue3';

export default{
  components:{
      Link
  }
}
</script>


