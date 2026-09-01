```vue
<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    rutas: Array,
});
</script>

<template>
    <AdminLayout>
        <div class="p-6">
            <!-- ENCABEZADO -->

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold">Gestión de Rutas</h1>

                    <p class="text-gray-500 mt-1">
                        Administre las rutas registradas en el sistema.
                    </p>
                </div>

                <Link
                    :href="route('rutas.create')"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow"
                >
                    + Nueva Ruta
                </Link>
            </div>

            <!-- TABLA -->

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="text-left p-3 border-b">Ruta</th>

                            <th class="text-left p-3 border-b">Línea</th>

                            <th class="text-left p-3 border-b">Sindicato</th>

                            <th class="text-left p-3 border-b">Origen</th>

                            <th class="text-left p-3 border-b">Destino</th>

                            <th class="text-left p-3 border-b">Distancia</th>

                            <th class="text-left p-3 border-b">Estado</th>

                            <th class="text-left p-3 border-b">Acción</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="ruta in rutas"
                            :key="ruta.idruta"
                            class="border-b hover:bg-gray-50"
                        >
                            <!-- RUTA -->

                            <td class="p-3 font-semibold">
                                {{ ruta.nombreruta }}
                            </td>

                            <!-- LÍNEA -->

                            <td class="p-3">
                                {{ ruta.linea?.nombre ?? "Sin línea" }}
                            </td>

                            <!-- SINDICATO -->

                            <td class="p-3">
                                {{
                                    ruta.linea?.sindicato?.nombre ??
                                    "Sin sindicato"
                                }}
                            </td>

                            <!-- ORIGEN -->

                            <td class="p-3">
                                {{ ruta.origen ?? "Sin origen" }}
                            </td>

                            <!-- DESTINO -->

                            <td class="p-3">
                                {{ ruta.destino ?? "Sin destino" }}
                            </td>

                            <!-- DISTANCIA -->

                            <td class="p-3">
                                <span v-if="ruta.distancia">
                                    {{ ruta.distancia }} km
                                </span>

                                <span v-else class="text-gray-400"> — </span>
                            </td>

                            <!-- ESTADO -->

                            <td class="p-3">
                                <span
                                    v-if="ruta.estado"
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
                                <div class="flex gap-2">
                                    <Link
                                        :href="route('rutas.show', ruta.idruta)"
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded"
                                    >
                                        👁 Ver
                                    </Link>

                                    <Link
                                        :href="
                                            route('rutas.editor', ruta.idruta)
                                        "
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded"
                                    >
                                        ✏ Editar
                                    </Link>
                                </div>
                            </td>
                        </tr>

                        <!-- SIN REGISTROS -->

                        <tr v-if="rutas.length === 0">
                            <td
                                colspan="8"
                                class="text-center p-6 text-gray-500"
                            >
                                No existen rutas registradas.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
```
