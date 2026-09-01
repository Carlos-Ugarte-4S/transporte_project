<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useForm, Link } from "@inertiajs/vue3";

const props = defineProps({
    tipo_transportes: Array,
    lineas: Array,
    personas: Array,
});

const form = useForm({
    modelo: "",
    anio: "",
    codigo: "",
    idtipotransporte: "",
    idlinea: "",
    ci: "",
    estado: true,
});

function guardar() {
    form.post(route("vehiculos.store"));
}
</script>

<template>
    <AdminLayout>
        <div class="p-6">
            <h1 class="text-3xl font-bold mb-6">Nuevo Vehículo</h1>

            <form
                @submit.prevent="guardar"
                class="bg-white shadow rounded-lg p-6 space-y-5"
            >
                <!-- MODELO -->

                <div>
                    <label class="block font-semibold mb-2">
                        Nombre del Modelo
                    </label>

                    <input
                        v-model="form.modelo"
                        type="text"
                        class="border rounded w-full p-2"
                        placeholder="Ejemplo: Corolla"
                    />

                    <div
                        v-if="form.errors.modelo"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.modelo }}
                    </div>
                </div>

                <!-- AÑO -->

                <div>
                    <label class="block font-semibold mb-2"> Año </label>

                    <input
                        v-model="form.anio"
                        type="text"
                        class="border rounded w-full p-2"
                        placeholder="Ejemplo: 2020"
                    />

                    <div
                        v-if="form.errors.anio"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.anio }}
                    </div>
                </div>

                <div>
                    <label class="block font-semibold mb-2">
                        Codigo de identificación
                    </label>

                    <input
                        v-model="form.codigo"
                        type="text"
                        class="border rounded w-full p-2"
                        placeholder="Ejemplo: VEH-0002"
                    />

                    <div
                        v-if="form.errors.codigo"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.codigo }}
                    </div>
                </div>

                <!-- TIPO DE TRANSPORTE -->

                <div>
                    <label class="block font-semibold mb-2">
                        Tipo de Transporte
                    </label>

                    <select
                        v-model="form.idtipotransporte"
                        class="border rounded w-full p-2"
                    >
                        <option value="">Seleccione tipo de transporte</option>

                        <option
                            v-for="tipo_transporte in tipo_transportes"
                            :key="tipo_transporte.idtipotransporte"
                            :value="tipo_transporte.idtipotransporte"
                        >
                            {{ tipo_transporte.nombre }}
                        </option>
                    </select>

                    <div
                        v-if="form.errors.idtipotransporte"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.idtipotransporte }}
                    </div>
                </div>

                <!-- PERSONA -->

                <div>
                    <label class="block font-semibold mb-2"> Persona </label>

                    <select v-model="form.ci" class="border rounded w-full p-2">
                        <option value="">Seleccione persona</option>

                        <option
                            v-for="persona in personas"
                            :key="persona.ci"
                            :value="persona.ci"
                        >
                            {{ persona.nombre }} {{ persona.apellido }}
                        </option>
                    </select>

                    <div
                        v-if="form.errors.ci"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.ci }}
                    </div>
                </div>

                <!-- LINEA -->

                <div>
                    <label class="block font-semibold mb-2"> Línea </label>

                    <select
                        v-model="form.idlinea"
                        class="border rounded w-full p-2"
                    >
                        <option value="">Seleccione línea</option>

                        <option
                            v-for="linea in lineas"
                            :key="linea.idlinea"
                            :value="linea.idlinea"
                        >
                            {{ linea.nombre }}
                        </option>
                    </select>

                    <div
                        v-if="form.errors.idlinea"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.idlinea }}
                    </div>
                </div>

                <!-- BOTONES -->

                <div class="flex gap-3">
                    <Link
                        :href="route('vehiculos.index')"
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
