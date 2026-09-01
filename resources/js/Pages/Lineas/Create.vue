<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useForm, Link } from "@inertiajs/vue3";

const props = defineProps({
    sindicatos: Array,
});

const form = useForm({
    nombre: "",

    contacto: "",

    idsindicato: "",
});

function guardar() {
    form.post(route("lineas.store"));
}
</script>

<template>
    <AdminLayout>
        <div class="p-6">
            <h1 class="text-3xl font-bold mb-6">Nueva Línea</h1>

            <form
                @submit.prevent="guardar"
                class="bg-white shadow rounded-lg p-6 space-y-5"
            >
                <!-- NOMBRE -->

                <div>
                    <label class="block font-semibold mb-2">
                        Nombre de la Línea
                    </label>

                    <input
                        v-model="form.nombre"
                        type="text"
                        class="border rounded w-full p-2"
                        placeholder="Ejemplo: Línea 101"
                    />

                    <div
                        v-if="form.errors.nombre"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.nombre }}
                    </div>
                </div>

                <!-- CONTACTO -->

                <div>
                    <label class="block font-semibold mb-2"> Contacto </label>

                    <input
                        v-model="form.contacto"
                        type="text"
                        class="border rounded w-full p-2"
                        placeholder="Ejemplo: 70000000"
                    />

                    <div
                        v-if="form.errors.contacto"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.contacto }}
                    </div>
                </div>

                <!-- SINDICATO -->

                <div>
                    <label class="block font-semibold mb-2"> Sindicato </label>

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

                    <div
                        v-if="form.errors.idsindicato"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.idsindicato }}
                    </div>
                </div>

                <!-- BOTONES -->

                <div class="flex gap-3">
                    <Link
                        :href="route('lineas.index')"
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
