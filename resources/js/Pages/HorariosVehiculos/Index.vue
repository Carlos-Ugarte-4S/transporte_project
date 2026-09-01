```vue
<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    vehiculo_horarios: Array,
});
</script>

<template>
    <AdminLayout>
        <div class="flex justify-between mb-6">
            <h1 class="text-3xl font-bold">Horarios de Vehículos</h1>

            <Link
                :href="route('vehiculo_horarios.create')"
                class="bg-blue-600 text-white px-4 py-2 rounded"
            >
                + Nuevo Horario
            </Link>
        </div>

        <div class="bg-white shadow rounded-lg p-5">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-3">Vehículo</th>

                        <th class="text-left p-3">Código</th>

                        <th class="text-left p-3">Línea</th>

                        <th class="text-left p-3">Turno</th>

                        <th class="text-left p-3">Día</th>

                        <th class="text-left p-3">Inicio</th>

                        <th class="text-left p-3">Fin</th>

                        <th class="text-left p-3">Estado</th>

                        <th class="text-left p-3">Acción</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="horario in vehiculo_horarios"
                        :key="horario.idvehiculohorario"
                        class="border-b"
                    >
                        <!-- VEHÍCULO -->

                        <td class="p-3">
                            {{ horario.vehiculo?.modelo ?? "Sin vehículo" }}
                        </td>

                        <!-- CÓDIGO -->

                        <td class="p-3">
                            {{ horario.vehiculo?.codigo ?? "Sin código" }}
                        </td>

                        <!-- LÍNEA -->

                        <td class="p-3">
                            {{ horario.vehiculo?.linea?.nombre ?? "Sin línea" }}
                        </td>

                        <!-- TURNO -->

                        <td class="p-3">
                            {{ horario.horario?.turno?.nombre ?? "Sin turno" }}
                        </td>

                        <!-- DÍA -->

                        <td class="p-3">
                            {{ horario.horario?.dia ?? "Sin día" }}
                        </td>

                        <!-- HORA INICIO -->

                        <td class="p-3">
                            {{ horario.horario?.horaini ?? "--:--" }}
                        </td>

                        <!-- HORA FIN -->

                        <td class="p-3">
                            {{ horario.horario?.horafin ?? "--:--" }}
                        </td>

                        <!-- ESTADO -->

                        <td class="p-3">
                            <span
                                v-if="horario.estado"
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

                        <!-- ACCIONES -->

                        <td class="p-3">
                            <Link
                                :href="
                                    route(
                                        'vehiculo_horarios.edit',
                                        horario.idvehiculohorario,
                                    )
                                "
                                class="bg-blue-600 text-white px-3 py-1 rounded"
                            >
                                ✏ Editar
                            </Link>
                        </td>
                    </tr>

                    <!-- SIN REGISTROS -->

                    <tr v-if="vehiculo_horarios.length === 0">
                        <td colspan="9" class="text-center p-6 text-gray-500">
                            No existen horarios de vehículos registrados.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
```
