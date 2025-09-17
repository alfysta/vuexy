<script setup lang="ts">
import { ref } from 'vue'
import Navbar from '@/components/vuexy/Navbar.vue'
import Sidebar from '@/components/vuexy/Sidebar.vue'

const isSidebarCollapsed = ref(false)
const isSidebarMobileActive = ref(false)

// Toggle sidebar dari navbar
const toggleSidebar = () => {
    if (window.innerWidth >= 1200) {
        isSidebarCollapsed.value = !isSidebarCollapsed.value
    } else {
        isSidebarMobileActive.value = !isSidebarMobileActive.value
    }
}
</script>

<template>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <Sidebar :collapsed="isSidebarCollapsed" :mobile-active="isSidebarMobileActive" />
            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <Navbar @toggle-sidebar="toggleSidebar" />

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <!-- Website Analytics -->
                            <slot />
                            <!--/ Projects table -->
                        </div>
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div
                                class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
                                <div>2025 made with ❤️ by <a href="https://pixinvent.com" target="_blank"
                                        class="fw-semibold">Pixinvent</a>
                                </div>
                                <div>
                                    <a href="https://themeforest.net/licenses/standard" class="footer-link me-4"
                                        target="_blank">License</a>
                                    <a href="https://1.envato.market/pixinvent_portfolio" target="_blank"
                                        class="footer-link me-4">More Themes</a>

                                    <a href="https://demos.pixinvent.com/vuexy-html-admin-template/documentation/"
                                        target="_blank" class="footer-link me-4">Documentation</a>

                                    <a href="https://pixinvent.ticksy.com/" target="_blank"
                                        class="footer-link d-none d-sm-inline-block">Support</a>
                                </div>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <div class="layout-overlay" :class="{ 'active': isSidebarMobileActive }" @click="closeSidebarMobile">
        </div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
</template>
