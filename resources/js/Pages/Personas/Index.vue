<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { watch } from "vue";

const props = defineProps({
    personas: Object,

    filters: Object,
});

const form = useForm({
    buscar: props.filters.buscar ?? "",

    estado: props.filters.estado ?? "",
});

function buscar() {
    router.get(
        route("personas.index"),

        form,

        {
            preserveState: true,

            preserveScroll: true,

            replace: true,
        },
    );
}

function cambiarEstado(persona) {
    const accion = persona.estado ? "desactivar" : "activar";

    if (!confirm(`¿Desea ${accion} esta persona?`)) {
        return;
    }

    router.patch(
        route("personas.estado", persona.ci),
        {},
        {
            preserveScroll: true,
            preserveState: true,
        },
    );
}

let timeout = null;
let primeraCarga = true;

watch(
    () => [form.buscar, form.estado],

    () => {
        if (primeraCarga) {
            primeraCarga = false;
            return;
        }

        clearTimeout(timeout);

        timeout = setTimeout(() => {
            buscar();
        }, 400);
    },
);
</script>

<template>
    <Head title="Personas" />

    <AdminLayout>
        <div class="flex justify-between mb-6">
            <h1 class="text-3xl font-bold">Personas</h1>

            <div class="flex">
                <input
                    v-model="form.buscar"
                    type="text"
                    placeholder="Buscar por CI, nombre o apellido..."
                    class="border rounded-l px-4 py-2 w-80"
                />

                <button
                    @click="buscar"
                    class="bg-blue-600 text-white px-4 rounded-r hover:bg-blue-700"
                >
                    🔍
                </button>
            </div>

            <select v-model="form.estado" class="border rounded px-4 py-2">
                <option value="">Todos</option>

                <option :value="1">Activos</option>

                <option :value="0">Inactivos</option>
            </select>

            <Link
                href="/personas/create"
                class="bg-blue-600 text-white px-4 py-2 rounded"
            >
                + Nueva Persona
            </Link>
        </div>

        <div class="bg-white shadow rounded-lg p-5">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-3">CI</th>

                        <th class="text-left p-3">Nombre Completo</th>

                        <th class="text-left p-3">Teléfono</th>

                        <th class="text-left p-3">Sexo</th>

                        <th class="text-left p-3">Tipo Persona</th>

                        <th class="text-left p-3">Estado</th>

                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="persona in personas.data"
                        :key="persona.ci"
                        class="border-b"
                    >
                        <td class="p-3">
                            {{ persona.ci }}
                        </td>

                        <td class="p-3">
                            <div class="font-medium">
                                {{ persona.nombre }} {{ persona.apellido }}
                            </div>
                        </td>

                        <td class="p-3">
                            {{ persona.telefono }}
                        </td>

                        <td class="p-3">
                            <span
                                v-if="persona.sexo == 'M'"
                                class="px-2 py-1 rounded bg-blue-100 text-blue-700 text-xs font-semibold"
                            >
                                Masculino
                            </span>

                            <span
                                v-else
                                class="px-2 py-1 rounded bg-pink-100 text-pink-700 text-xs font-semibold"
                            >
                                Femenino
                            </span>
                        </td>

                        <td class="p-3">
                            <div class="flex flex-wrap gap-1">
                                <span
                                    v-if="persona.tipoa"
                                    class="px-2 py-1 rounded bg-blue-100 text-blue-700 text-xs font-semibold"
                                >
                                    Administrador
                                </span>

                                <span
                                    v-if="persona.tipoc"
                                    class="px-2 py-1 rounded bg-green-100 text-green-700 text-xs font-semibold"
                                >
                                    Chofer
                                </span>

                                <span
                                    v-if="persona.tipop"
                                    class="px-2 py-1 rounded bg-yellow-100 text-yellow-700 text-xs font-semibold"
                                >
                                    Propietario
                                </span>
                            </div>
                        </td>

                        <td class="p-3">
                            <span
                                v-if="persona.estado"
                                class="inline-flex items-center rounded-full bg-green-100 text-green-700 px-3 py-1 text-xs font-semibold"
                            >
                                🟢 Activo
                            </span>

                            <span
                                v-else
                                class="inline-flex items-center rounded-full bg-red-100 text-red-700 px-3 py-1 text-xs font-semibold"
                            >
                                🔴 Inactivo
                            </span>
                        </td>

                        <td class="p-3">
                            <div class="flex gap-2">
                                <Link
                                    :href="route('personas.edit', persona.ci)"
                                    class="px-3 py-1 bg-blue-600 text-white rounded"
                                >
                                    ✏ Editar
                                </Link>

                                <button
                                    @click="cambiarEstado(persona)"
                                    class="px-3 py-1 rounded text-white text-sm"
                                    :class="
                                        persona.estado
                                            ? 'bg-red-600 hover:bg-red-700'
                                            : 'bg-green-600 hover:bg-green-700'
                                    "
                                >
                                    {{
                                        persona.estado
                                            ? "Desactivar"
                                            : "Activar"
                                    }}
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="flex justify-center mt-6">
                <div class="flex gap-1">
                    <Link
                        v-for="link in personas.links"
                        :key="link.label"
                        :href="link.url ?? '#'"
                        v-html="link.label"
                        class="px-3 py-2 border rounded"
                        :class="{
                            'bg-blue-600 text-white': link.active,

                            'text-gray-400 pointer-events-none': !link.url,
                        }"
                    />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
