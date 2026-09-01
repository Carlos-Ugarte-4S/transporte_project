<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    persona: Object,
});

const form = useForm({
    ci: props.persona.ci,

    nombre: props.persona.nombre,

    apellido: props.persona.apellido,

    telefono: props.persona.telefono,

    sexo: props.persona.sexo,

    licencia: props.persona.licencia,

    tipoa: props.persona.tipoa,

    tipoc: props.persona.tipoc,

    tipop: props.persona.tipop,

    estado: props.persona.estado,
});

function guardar() {
    form.put(route("personas.update", form.ci));
}

function cambiarEstado() {
    if (
        confirm(
            form.estado
                ? "¿Desea desactivar esta persona?"
                : "¿Desea activar esta persona?",
        )
    ) {
        form.estado = !form.estado;
    }
}
</script>

<template>
    <Head title="Editar Persona" />

    <AdminLayout>
        <div class="max-w-3xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">Editar Persona</h1>

            <form
                @submit.prevent="guardar"
                class="bg-white shadow rounded-lg p-6 space-y-5"
            >
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label>CI</label>

                        <input
                            v-model="form.ci"
                            disabled
                            class="border rounded w-full p-2 bg-gray-100"
                        />
                    </div>

                    <div>
                        <label>Teléfono</label>

                        <input
                            v-model="form.telefono"
                            class="border rounded w-full p-2"
                        />
                    </div>

                    <div>
                        <label>Nombre</label>

                        <input
                            v-model="form.nombre"
                            class="border rounded w-full p-2"
                        />
                    </div>

                    <div>
                        <label>Apellido</label>

                        <input
                            v-model="form.apellido"
                            class="border rounded w-full p-2"
                        />
                    </div>

                    <div>
                        <label>Sexo</label>

                        <select
                            v-model="form.sexo"
                            class="border rounded w-full p-2"
                        >
                            <option value="M">Masculino</option>

                            <option value="F">Femenino</option>
                        </select>
                    </div>

                    <div>
                        <label>Licencia</label>

                        <input
                            v-model="form.licencia"
                            class="border rounded w-full p-2"
                        />
                    </div>
                </div>

                <hr />

                <h2 class="font-bold">Tipo Persona</h2>

                <div class="flex gap-6">
                    <label>
                        <input type="checkbox" v-model="form.tipoa" />

                        Administrador
                    </label>

                    <label>
                        <input type="checkbox" v-model="form.tipoc" />

                        Chofer
                    </label>

                    <label>
                        <input type="checkbox" v-model="form.tipop" />

                        Propietario
                    </label>
                </div>

                <hr />

                <div class="flex items-center justify-between">
                    <div>
                        <span
                            v-if="form.estado"
                            class="px-3 py-1 rounded bg-green-100 text-green-700"
                        >
                            🟢 Activo
                        </span>

                        <span
                            v-else
                            class="px-3 py-1 rounded bg-red-100 text-red-700"
                        >
                            🔴 Inactivo
                        </span>
                    </div>

                    <button
                        type="button"
                        @click="cambiarEstado"
                        class="bg-yellow-500 text-white px-4 py-2 rounded"
                    >
                        Cambiar Estado
                    </button>
                </div>

                <hr />

                <div class="flex justify-end gap-3">
                    <Link
                        :href="route('personas.index')"
                        class="bg-gray-500 text-white px-4 py-2 rounded"
                    >
                        Cancelar
                    </Link>

                    <button class="bg-blue-600 text-white px-5 py-2 rounded">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
