<script setup>
import { ref, provide } from "vue";

import Sidebar from "@/Components/Sidebar.vue";
import Navbar from "@/Components/Navbar.vue";

const sidebarOpen = ref(true);

provide("sidebarOpen", sidebarOpen);
</script>

<template>
    <div class="flex min-h-screen">
        <Sidebar />

        <div class="flex-1">
            <Navbar />

            <main class="p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
