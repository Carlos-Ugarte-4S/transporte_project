<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";

import Card from "@/Components/Card.vue";

import { Head } from "@inertiajs/vue3";

defineProps({
    estadisticas: Object,
});
</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout>
        <h1 class="text-3xl font-bold mb-6">Dashboard</h1>

        <div class="grid grid-cols-4 gap-6">
            <Card
                titulo="Personas"
                :cantidad="estadisticas.personas"
                icono="👥"
            />
            <Card
                titulo="Vehículos"
                :cantidad="estadisticas.vehiculos"
                icono="🚌"
            />
            <Card titulo="Rutas" :cantidad="estadisticas.rutas" icono="🗺️" />

            <Card titulo="Líneas" :cantidad="estadisticas.lineas" icono="🚍" />
        </div>
    </AdminLayout>
</template>
