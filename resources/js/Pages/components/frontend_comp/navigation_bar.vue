<template>
    <div class="bg-bg_main_color p-6">
        <nav class="d-flex justify-content-between">
            <ul class="flex flex-direction-row gap-3">
                <li class="menu-link text-white fw-bold">MealXpress</li>
            </ul>
            <div class="d-flex gap-2">
                <ul>
                    <li class="menu-link gap-2">
                        <Link href="/" class="menu-link bg-footer-link fw-bold">
                            About
                        </Link>
                        <Link href="/" class="menu-link bg-footer-link fw-bold">
                             Partnership
                        </Link>
                        <Link href="/" class="menu-link bg-footer-link fw-bold">
                            Contact us
                        </Link>
                    </li>
                </ul>
            </div>
            <div class="d">
                <Link href="/" class="infobutton py-3 w-20">Get Started</Link>
            </div>
        </nav>          
    </div>
</template>

<script>
import {Link} from '@inertiajs/vue3';
export default {
    name: 'NavigationOnline',

    components:{
        Link,

    }
}
</script>

