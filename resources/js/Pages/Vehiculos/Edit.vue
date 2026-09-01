<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    vehiculo: Object,

    tipo_transportes: Array,
    lineas: Array,
    personas: Array,
});

const form = useForm({
    modelo: props.vehiculo.modelo,
    anio: props.vehiculo.anio,
    codigo: props.vehiculo.codigo,
    idtipotransporte: props.vehiculo.idtipotransporte,
    idlinea: props.vehiculo.idlinea,
    ci: props.vehiculo.ci,
    estado: props.vehiculo.estado,
});

function guardar() {
    form.put(route("vehiculos.update", props.vehiculo.idvehiculo));
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
    <Head title="Editar Vehículo    " />

    <AdminLayout>
        <div class="max-w-xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">Editar Vehículo</h1>

            <form
                @submit.prevent="guardar"
                class="bg-white shadow rounded-lg p-6 space-y-5"
            >
                <!-- Nombre -->

                <div>
                    <label class="font-semibold"> Modelo </label>

                    <input
                        v-model="form.modelo"
                        class="border rounded w-full p-2"
                    />

                    <p v-if="form.errors.modelo" class="text-red-600 text-sm">
                        {{ form.errors.modelo }}
                    </p>
                </div>

                <!-- Año -->

                <div>
                    <label class="font-semibold"> Año </label>

                    <input
                        v-model="form.anio"
                        class="border rounded w-full p-2"
                    />

                    <p v-if="form.errors.anio" class="text-red-600 text-sm">
                        {{ form.errors.anio }}
                    </p>
                </div>

                <!-- Código -->

                <div>
                    <label class="font-semibold"> Código </label>

                    <input
                        v-model="form.codigo"
                        class="border rounded w-full p-2"
                    />

                    <p v-if="form.errors.codigo" class="text-red-600 text-sm">
                        {{ form.errors.codigo }}
                    </p>
                </div>

                <!-- Tipo de Transporte -->

                <div>
                    <label class="font-semibold"> Tipo de Transporte </label>

                    <select
                        v-model="form.idtipotransporte"
                        class="border rounded w-full p-2"
                    >
                        <option value="">Seleccione tipo de transporte</option>

                        <option
                            v-for="tipoTransporte in tipo_transportes"
                            :key="tipoTransporte.idtipotransporte"
                            :value="tipoTransporte.idtipotransporte"
                        >
                            {{ tipoTransporte.nombre }}
                        </option>
                    </select>

                    <p
                        v-if="form.errors.idtipotransporte"
                        class="text-red-600 text-sm"
                    >
                        {{ form.errors.idtipotransporte }}
                    </p>
                </div>

                <!-- Linea-->

                <div>
                    <label class="font-semibold"> Linea </label>

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

                    <p v-if="form.errors.idlinea" class="text-red-600 text-sm">
                        {{ form.errors.idlinea }}
                    </p>
                </div>

                <div>
                    <label class="font-semibold"> Persona </label>

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

                    <p v-if="form.errors.ci" class="text-red-600 text-sm">
                        {{ form.errors.ci }}
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
