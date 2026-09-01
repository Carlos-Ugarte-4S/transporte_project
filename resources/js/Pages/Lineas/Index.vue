<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    lineas: Array,
});
</script>

<template>
    <Head title="Lineas" />

    <AdminLayout>
        <div class="flex justify-between mb-6">
            <h1 class="text-3xl font-bold">Lineas</h1>

            <Link
                :href="route('lineas.create')"
                class="bg-blue-600 text-white px-4 py-2 rounded"
            >
                + Nueva Linea
            </Link>
        </div>

        <div class="bg-white shadow rounded-lg p-5">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-3">Nombre</th>

                        <th class="text-left p-3">contacto</th>

                        <th class="text-left p-3">Sindicato</th>

                        <th class="text-left p-3">Estado</th>

                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="linea in lineas"
                        :key="linea.idlinea"
                        class="border-b"
                    >
                        <td class="p-3">
                            {{ linea.nombre }}
                        </td>

                        <td class="p-3">
                            {{ linea.contacto }}
                        </td>

                        <td class="p-3">
                            {{ linea.sindicato?.nombre ?? "Sin sindicato" }}
                        </td>

                        <td class="p-3">
                            <span
                                v-if="linea.estado === 'activo'"
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
                                :href="route('lineas.edit', linea.idlinea)"
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
