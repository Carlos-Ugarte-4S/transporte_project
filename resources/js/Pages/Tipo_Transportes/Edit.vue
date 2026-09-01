<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    tipo_transporte: Object,
});

const form = useForm({
    nombre: props.tipo_transporte.nombre,

    capacidad: props.tipo_transporte.capacidad,

    estado: props.tipo_transporte.estado,
});

function guardar() {
    form.put(
        route(
            "tipo_transportes.update",
            props.tipo_transporte.idtipotransporte,
        ),
    );
}

function cambiarEstado() {
    if (
        confirm(
            form.estado
                ? "¿Desea desactivar este tipo de transporte?"
                : "¿Desea activar este tipo de transporte?",
        )
    ) {
        form.estado = !form.estado;
    }
}
</script>

<template>
    <Head title="Editar Tipo de Transporte" />

    <AdminLayout>
        <div class="max-w-xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">Editar Tipo de Transporte</h1>

            <form
                @submit.prevent="guardar"
                class="bg-white shadow rounded-lg p-6 space-y-5"
            >
                <!-- Nombre -->

                <div>
                    <label class="font-semibold"> Nombre </label>

                    <input
                        v-model="form.nombre"
                        class="border rounded w-full p-2"
                    />

                    <p v-if="form.errors.nombre" class="text-red-600 text-sm">
                        {{ form.errors.nombre }}
                    </p>
                </div>

                <!-- Capacidad -->

                <div>
                    <label class="font-semibold"> Capacidad </label>

                    <input
                        type="number"
                        v-model="form.capacidad"
                        class="border rounded w-full p-2"
                    />

                    <p
                        v-if="form.errors.capacidad"
                        class="text-red-600 text-sm"
                    >
                        {{ form.errors.capacidad }}
                    </p>
                </div>

                <!-- Estado -->

                <div>
                    <label class="font-semibold"> Estado </label>

                    <div class="flex gap-3 mt-2">
                        <span
                            v-if="form.estado"
                            class="bg-green-100 text-green-700 px-3 py-1 rounded-full"
                        >
                            🟢 Activo
                        </span>

                        <span
                            v-else
                            class="bg-red-100 text-red-700 px-3 py-1 rounded-full"
                        >
                            🔴 Inactivo
                        </span>

                        <button
                            type="button"
                            @click="cambiarEstado"
                            class="bg-yellow-500 text-white px-3 py-2 rounded"
                        >
                            Cambiar estado
                        </button>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <Link
                        :href="route('tipo_transportes.index')"
                        class="bg-gray-500 text-white px-4 py-2 rounded"
                    >
                        Cancelar
                    </Link>

                    <button class="bg-blue-600 text-white px-5 py-2 rounded">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
