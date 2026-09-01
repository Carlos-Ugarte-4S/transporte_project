<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    vehiculos: Array,
    personas: Array,
    lineas: Array,
});
</script>

<template>
    <Head title="Vehiculos" />

    <AdminLayout>
        <div class="flex justify-between mb-6">
            <h1 class="text-3xl font-bold">Vehiculos</h1>

            <Link
                :href="route('vehiculos.create')"
                class="bg-blue-600 text-white px-4 py-2 rounded"
            >
                + Nuevo Vehiculo
            </Link>
        </div>

        <div class="bg-white shadow rounded-lg p-5">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-3">Tipo de transporte</th>

                        <th class="text-left p-3">Modelo</th>

                        <th class="text-left p-3">codigo de identificacion</th>

                        <th class="text-left p-3">Año</th>

                        <th class="text-left p-3">Persona</th>

                        <th class="text-left p-3">Linea</th>

                        <th class="text-left p-3">Estado</th>

                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="vehiculo in vehiculos"
                        :key="vehiculo.idvehiculo"
                        class="border-b"
                    >
                        <td class="p-3">
                            {{
                                vehiculo.tipotransporte?.nombre ??
                                "Sin tipo de transporte"
                            }}
                        </td>

                        <td class="p-3">
                            {{ vehiculo.modelo }}
                        </td>

                        <td class="p-3">
                            {{ vehiculo.codigo }}
                        </td>

                        <td class="p-3">
                            {{ vehiculo.anio }}
                        </td>

                        <td class="p-3">
                            {{ vehiculo.persona?.nombre ?? "Sin persona" }}
                        </td>

                        <td class="p-3">
                            {{ vehiculo.linea?.nombre ?? "Sin linea" }}
                        </td>

                        <td class="p-3">
                            <span
                                v-if="vehiculo.estado"
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
                                    route('vehiculos.edit', vehiculo.idvehiculo)
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
