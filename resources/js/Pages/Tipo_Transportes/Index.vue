<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    tipo_transportes: Array,
});
</script>

<template>
    <Head title="Tipos de Vehículos" />

    <AdminLayout>
        <div class="flex justify-between mb-6">
            <h1 class="text-3xl font-bold">Tipos de Vehículos</h1>

            <Link
                :href="route('tipo_transportes.create')"
                class="bg-blue-600 text-white px-4 py-2 rounded"
            >
                + Nuevo Tipo de Vehículo
            </Link>
        </div>

        <div class="bg-white shadow rounded-lg p-5">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-3">Nombre</th>

                        <th class="text-left p-3">Capacidad</th>

                        <th class="text-left p-3">Estado</th>

                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="tipo_transporte in tipo_transportes"
                        :key="tipo_transporte.idtipotransporte"
                        class="border-b"
                    >
                        <td class="p-3">
                            {{ tipo_transporte.nombre }}
                        </td>

                        <td class="p-3">
                            {{ tipo_transporte.capacidad }}
                        </td>

                        <td class="p-3">
                            <span
                                v-if="tipo_transporte.estado"
                                class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm"
                            >
                                🟢 Activo
                            </span>

                            <span
                                v-else
                                class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm"
                            >
                                🔴 Inactivo
                            </span>
                        </td>

                        <td class="p-3">
                            <Link
                                :href="
                                    route(
                                        'tipo_transportes.edit',
                                        tipo_transporte.idtipotransporte,
                                    )
                                "
                                class="bg-blue-600 text-white px-3 py-1 rounded"
                            >
                                ✏ Editar
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
