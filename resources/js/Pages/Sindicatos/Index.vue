<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";

const props = defineProps({
    sindicatos: Array,

    filters: Object,
});

function formatoFecha(fecha) {
    if (!fecha) return "";

    const partes = fecha.split("-");

    return `${partes[2]}/${partes[1]}/${partes[0]}`;
}

function buscar() {
    router.get(
        route("sindicatos.index"),

        {
            buscar: props.filters.buscar,
        },

        {
            preserveState: true,
            replace: true,
        },
    );
}
</script>

<template>
    <Head title="Sindicatos" />

    <AdminLayout>
        <div class="flex justify-between mb-6">
            <h1 class="text-3xl font-bold">Sindicatos</h1>

            <div class="flex gap-3 mb-5">
                <input
                    v-model="props.filters.buscar"
                    @keyup="buscar"
                    type="text"
                    placeholder="Buscar sindicato..."
                    class="border rounded px-4 py-2 w-80"
                />
            </div>

            <Link
                :href="route('sindicatos.create')"
                class="bg-blue-600 text-white px-4 py-2 rounded"
            >
                + Nuevo Sindicato
            </Link>
        </div>

        <div class="bg-white shadow rounded-lg p-5">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-3">ID</th>

                        <th class="text-left p-3">Nombre</th>

                        <th class="text-left p-3">Fundación</th>

                        <th class="text-left p-3">Estado</th>

                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="sindicato in sindicatos"
                        :key="sindicato.idsindicato"
                        class="border-b"
                    >
                        <td class="p-3">
                            {{ sindicato.idsindicato }}
                        </td>

                        <td class="p-3">
                            {{ sindicato.nombre }}
                        </td>

                        <td class="p-3">
                            {{ sindicato.fundacion_formateada }}
                        </td>

                        <td class="p-3">
                            <span
                                v-if="sindicato.estado"
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
                                        'sindicatos.edit',
                                        sindicato.idsindicato,
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
