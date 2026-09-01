<script setup>
import { inject } from "vue";

const sidebarOpen = inject("sidebarOpen");

function toggleSidebar() {
    sidebarOpen.value = !sidebarOpen.value;
}
</script>

<template>
    <header class="h-16 bg-white shadow flex items-center px-5">
        <button @click="toggleSidebar" class="text-2xl">☰</button>

        <h1 class="ml-5 font-bold">🚍 Sistema Transporte</h1>
    </header>
</template>
