<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    nombre: "",
    capacidad: "",
});

function guardar() {
    form.post(route("tipo_transportes.store"));
}
</script>

<template>
    <Head title="Nuevo Tipo de Vehículo" />

    <AdminLayout>
        <div class="max-w-xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">Nuevo Tipo de Vehículo</h1>

            <form
                @submit.prevent="guardar"
                class="bg-white shadow rounded-lg p-6 space-y-5"
            >
                <!-- Nombre -->

                <div>
                    <label class="block mb-2 font-semibold">
                        Nombre del Tipo de Vehículo
                    </label>

                    <input
                        v-model="form.nombre"
                        type="text"
                        class="border rounded w-full p-2"
                    />

                    <p
                        v-if="form.errors.nombre"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.nombre }}
                    </p>
                </div>

                <!-- Fundación -->

                <div>
                    <label class="block mb-2 font-semibold">
                        Capacidad del Tipo de Vehículo
                    </label>

                    <input
                        v-model="form.capacidad"
                        type="number"
                        class="border rounded w-full p-2"
                    />

                    <p
                        v-if="form.errors.capacidad"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.capacidad }}
                    </p>
                </div>

                <div class="flex justify-end gap-3">
                    <Link
                        :href="route('tipo_transportes.index')"
                        class="bg-gray-500 text-white px-4 py-2 rounded"
                    >
                        Cancelar
                    </Link>

                    <button
                        :disabled="form.processing"
                        class="bg-blue-600 text-white px-5 py-2 rounded disabled:opacity-50"
                    >
                        {{ form.processing ? "Guardando..." : "Guardar" }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
