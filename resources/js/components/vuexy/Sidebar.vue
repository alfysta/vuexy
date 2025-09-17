<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLogo from '../AppLogo.vue'

// Menu dinamis
const menuItems = ref([
    { title: 'Dashboards', icon: 'ti ti-smart-home', link: '/dashboard' },
    {
        title: 'Layouts', icon: 'ti ti-layout-sidebar', children: [
            { title: 'Collapsed menu', link: '/layouts-collapsed-menu' },
            { title: 'Content navbar', link: '/layouts-content-navbar' },
            { title: 'Horizontal', link: '/horizontal-menu-template', target: '_blank' }
        ]
    },
    {
        title: 'Cards', icon: 'ti ti-id', badge: '6', children: [
            { title: 'Basic', link: '/cards-basic' },
            { title: 'Advance', link: '/cards-advance' }
        ]
    },
    {
        title: 'Settings', icon: 'ti ti-settings', children: [
            { title: 'Profile', link: '/settings/profile' },
            {
                title: 'Appearance',
                link: '/settings/appearance',
                isDarkMode: true // tandai sebagai toggle dark mode
            }
        ]
    },
    { title: 'Logout', icon: 'ti ti-logout', link: 'logout' }
])

// Track current path
const currentPath = ref(window.location.pathname)

// Mode saat ini
const darkMode = ref(localStorage.getItem('darkMode') === 'true')

// Inisialisasi Vuexy menu helper & dark mode
onMounted(() => {
    // set dark mode body
    if (darkMode.value) document.body.classList.add('dark')

    const menuEl = document.querySelector('#layout-menu')
    if (menuEl && window.Helpers && window.Menu) {
        window.Helpers.mainMenu = new window.Menu(menuEl, {
            orientation: menuEl.classList.contains('menu-horizontal') ? 'horizontal' : 'vertical',
            closeChildren: false
        })
    }
})

const isSidebarCollapsed = ref(false)
const isSidebarMobileActive = ref(false)

const toggleSidebar = () => {
    const layoutMenu = document.querySelector('#layout-menu')
    if (!layoutMenu) return

    if (window.innerWidth >= 1200) {
        // Desktop
        isSidebarCollapsed.value = !isSidebarCollapsed.value
        if (isSidebarCollapsed.value) layoutMenu.classList.add('menu-collapsed')
        else layoutMenu.classList.remove('menu-collapsed')
    } else {
        // Mobile
        isSidebarMobileActive.value = !isSidebarMobileActive.value
        if (isSidebarMobileActive.value) layoutMenu.classList.add('menu-active')
        else layoutMenu.classList.remove('menu-active')
    }
}

const props = defineProps({
    collapsed: Boolean,
    mobileActive: Boolean
});

// Handle klik SPA & dark mode toggle
const handleMenuClick = (item: any) => {
    if (item.link === 'logout') {
        router.post('/logout')
    } else if (item.isDarkMode) {
        // toggle dark mode
        darkMode.value = !darkMode.value
        if (darkMode.value) {
            document.body.classList.add('dark')
        } else {
            document.body.classList.remove('dark')
        }
        localStorage.setItem('darkMode', darkMode.value.toString())
    } else if (!item.children) {
        // SPA navigation
        router.visit(item.link)
        currentPath.value = item.link
    }
}
</script>


<template>
    <aside id="layout-menu" :class="[
        'layout-menu menu-vertical menu bg-menu-theme',
        { 'menu-collapsed': props.collapsed, 'menu-active': props.mobileActive }
    ]">
        <div class=" app-brand demo">
            <Link href="/" class="app-brand-link">
            <span class="app-brand-logo demo">
                <AppLogo />
            </span>
            <span class="app-brand-text demo menu-text fw-bold">E-Presensi</span>
            </Link>
            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto"
                @click.prevent="toggleSidebar">
                <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
                <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
            </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
            <li v-for="(item, index) in menuItems" :key="index" class="menu-item" :class="{
                open: item.children && item.children.some(c => c.link === currentPath),
                active: !item.children && item.link === currentPath
            }">

                <!-- Parent dengan children -->
                <a v-if="item.children" href="javascript:void(0);" class="menu-link menu-toggle" @click.prevent>
                    <i class="menu-icon tf-icons" :class="item.icon"></i>
                    <div>{{ item.title }}</div>
                    <div v-if="item.badge" class="badge bg-label-primary rounded-pill ms-auto">{{ item.badge }}</div>
                </a>

                <!-- Leaf menu / SPA -->
                <Link v-else :href="item.link" class="menu-link" @click.prevent="handleMenuClick(item)">
                <i class="menu-icon tf-icons" :class="item.icon"></i>
                <div>{{ item.title }}</div>
                <div v-if="item.badge" class="badge bg-label-primary rounded-pill ms-auto">{{ item.badge }}</div>
                </Link>

                <!-- Children -->
                <ul v-if="item.children" class="menu-sub">
                    <li v-for="(child, cIndex) in item.children" :key="cIndex" class="menu-item"
                        :class="{ active: child.link === currentPath }">
                        <Link :href="child.link" class="menu-link" @click.prevent="handleMenuClick(child)">
                        <div>{{ child.title }}</div>
                        </Link>
                    </li>
                </ul>
            </li>
        </ul>
    </aside>
</template>
