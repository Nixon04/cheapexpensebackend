<template>
    
    <aside class="aside-mealxpress">
      <div class="flex-wrap-container">
          <div class="aside-header">
              <h1 class="text-white fs-5">Cheapxpense</h1>
          </div>
          <div class="aside-navigation">
              <ul class="ul-flex">
                  <Link href="/prisent/dashboard/home" class="mb-3">
                      <li :class="['mealxpress-navlink', { active: $page.url === '/prisent/dashboard/home' } ]">
                          <i class="fas fa-home mealxpress-icons" ></i>
                          Dashboard
                      </li>
                  </Link>

                  <Link href="/prisent/dashboard/currentsales" class="mb-3">
                      <li :class="['mealxpress-navlink', { active: $page.url === '/prisent/dashboard/currentsales' } ]">
                          <i class="fas fa-signal mealxpress-icons" ></i>
                         Current Sales
                      </li>
                  </Link>

                  <Link href="/prisent/dashboard/payouts" class="mb-4" replace>
                      <li :class="['mealxpress-navlink', { active: $page.url === '/prisent/dashboard/payouts' } ]">
                          <i class="fa fa-store mealxpress-icons" ></i>
                         Payouts
                      </li>
                  </Link>

                  <Link href="/prisent/dashboard/sendappnotification" class="mb-4" replace>
                    <li :class="['mealxpress-navlink', { active: $page.url === '/prisent/dashboard/sendappnotification' } ]">
                        <i class="fa fa-bell mealxpress-icons" ></i>
                       App Notification
                    </li>
                </Link>

                  <Link href="/prisent/dashboard/regusers" class="mb-4">
                      <li :class="['mealxpress-navlink', { active: $page.url === '/prisent/dashboard/regusers' } ]">
                          <i class="fas fa-users mealxpress-icons" ></i>
                         Reg Users
                      </li>
                  </Link>

                  <Link href="/prisent/dashboard/vendors" class="mb-4">
                    <li :class="['mealxpress-navlink', { active: $page.url === '/prisent/dashboard/vendors' } ]">
                        <i class="fas fa-store mealxpress-icons" ></i>
                       Vendors
                    </li>
                </Link>

                <Link href="/prisent/dashboard/drivers" class="mb-4">
                    <li :class="['mealxpress-navlink', { active: $page.url === '/prisent/dashboard/drivers' } ]">
                        <i class="fas fa-car mealxpress-icons" ></i>
                      Drivers
                    </li>
                </Link>

                  <Link href="/prisent/auth/login" class="mb-4">
                      <li :class="['mealxpress-navlink', { active: $page.url === '/prisent/dashboard/logout' } ]">
                          <i class="fa fa-cog mealxpress-icons" ></i>
                         LogOut
                      </li>
                  </Link>


              </ul>
          </div>
          <div class="aside-footer">
              <div class="aside-centered">
                  <h1 class=" fs-2 text-white">Having troubles</h1>
                  <h6 class="fs-7 text-white">We are active 24/7</h6>
              </div>
          </div>
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


