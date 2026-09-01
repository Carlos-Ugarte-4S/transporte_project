<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    linea: Object,

    sindicatos: Array,
});

const form = useForm({
    nombre: props.linea.nombre,

    contacto: props.linea.contacto,

    idsindicato: props.linea.idsindicato,

    estado: props.linea.estado,
});

function guardar() {
    form.put(route("lineas.update", props.linea.idlinea));
}

function cambiarEstado() {
    if (form.estado === "activo") {
        form.estado = "inactivo";
    } else {
        form.estado = "activo";
    }
}
</script>

<template>
    <Head title="Editar Línea" />

    <AdminLayout>
        <div class="max-w-xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">Editar Línea</h1>

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

                <!-- Contacto -->

                <div>
                    <label class="font-semibold"> Contacto </label>

                    <input
                        v-model="form.contacto"
                        class="border rounded w-full p-2"
                    />

                    <p v-if="form.errors.contacto" class="text-red-600 text-sm">
                        {{ form.errors.contacto }}
                    </p>
                </div>

                <!-- Sindicato -->

                <div>
                    <label class="font-semibold"> Sindicato </label>

                    <select
                        v-model="form.idsindicato"
                        class="border rounded w-full p-2"
                    >
                        <option value="">Seleccione sindicato</option>

                        <option
                            v-for="sindicato in sindicatos"
                            :key="sindicato.idsindicato"
                            :value="sindicato.idsindicato"
                        >
                            {{ sindicato.nombre }}
                        </option>
                    </select>

                    <p
                        v-if="form.errors.idsindicato"
                        class="text-red-600 text-sm"
                    >
                        {{ form.errors.idsindicato }}
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
                        :href="route('lineas.index')"
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
