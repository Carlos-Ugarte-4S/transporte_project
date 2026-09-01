<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    sindicato: Object,
});

const form = useForm({
    nombre: props.sindicato.nombre,

    fundacion: props.sindicato.fundacion,

    estado: props.sindicato.estado,
});

function guardar() {
    form.put(route("sindicatos.update", props.sindicato.idsindicato));
}

function cambiarEstado() {
    if (
        confirm(
            form.estado
                ? "¿Desea desactivar este sindicato?"
                : "¿Desea activar este sindicato?",
        )
    ) {
        form.estado = !form.estado;
    }
}
</script>

<template>
    <Head title="Editar Sindicato" />

    <AdminLayout>
        <div class="max-w-xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">Editar Sindicato</h1>

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

                <!-- Fundación -->

                <div>
                    <label class="font-semibold"> Fecha Fundación </label>

                    <input
                        type="date"
                        v-model="form.fundacion"
                        class="border rounded w-full p-2"
                    />

                    <p
                        v-if="form.errors.fundacion"
                        class="text-red-600 text-sm"
                    >
                        {{ form.errors.fundacion }}
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
                        :href="route('sindicatos.index')"
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
