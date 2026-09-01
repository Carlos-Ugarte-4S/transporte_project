```vue
<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    vehiculo_horario: Object,
    vehiculos: Array,
    turnos: Array,
});

const form = useForm({
    idvehiculo: props.vehiculo_horario.vehiculo.idvehiculo,
    idturno: props.vehiculo_horario.horario.idturno,
    dia: props.vehiculo_horario.horario.dia,
    horaini: props.vehiculo_horario.horario.horaini?.substring(0, 5),
    horafin: props.vehiculo_horario.horario.horafin?.substring(0, 5),
    estado: props.vehiculo_horario.estado,
});

function actualizar() {
    form.put(
        route(
            "vehiculo_horarios.update",
            props.vehiculo_horario.idvehiculohorario,
        ),
    );
}
</script>

<template>
    <AdminLayout>
        <div class="p-6">
            <h1 class="text-3xl font-bold mb-6">Editar Horario de Vehículo</h1>

            <form
                @submit.prevent="actualizar"
                class="bg-white shadow rounded-lg p-6 space-y-5"
            >
                <!-- VEHÍCULO -->

                <div>
                    <label class="block font-semibold mb-2"> Vehículo </label>

                    <select
                        v-model="form.idvehiculo"
                        class="border rounded w-full p-2"
                    >
                        <option value="">Seleccione vehículo</option>

                        <option
                            v-for="vehiculo in vehiculos"
                            :key="vehiculo.idvehiculo"
                            :value="vehiculo.idvehiculo"
                        >
                            {{ vehiculo.codigo }} - {{ vehiculo.modelo }} -
                            {{ vehiculo.linea?.nombre ?? "Sin línea" }}
                        </option>
                    </select>

                    <div
                        v-if="form.errors.idvehiculo"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.idvehiculo }}
                    </div>
                </div>

                <!-- TURNO -->

                <div>
                    <label class="block font-semibold mb-2"> Turno </label>

                    <select
                        v-model="form.idturno"
                        class="border rounded w-full p-2"
                    >
                        <option value="">Seleccione turno</option>

                        <option
                            v-for="turno in turnos"
                            :key="turno.idturno"
                            :value="turno.idturno"
                        >
                            {{ turno.nombre }}
                        </option>
                    </select>

                    <div
                        v-if="form.errors.idturno"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.idturno }}
                    </div>
                </div>

                <!-- DÍA -->

                <div>
                    <label class="block font-semibold mb-2"> Día </label>

                    <select
                        v-model="form.dia"
                        class="border rounded w-full p-2"
                    >
                        <option value="">Seleccione día</option>

                        <option value="Lunes">Lunes</option>
                        <option value="Martes">Martes</option>
                        <option value="Miércoles">Miércoles</option>
                        <option value="Jueves">Jueves</option>
                        <option value="Viernes">Viernes</option>
                        <option value="Sábado">Sábado</option>
                        <option value="Domingo">Domingo</option>
                    </select>

                    <div
                        v-if="form.errors.dia"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.dia }}
                    </div>
                </div>

                <!-- HORA INICIO -->

                <div>
                    <label class="block font-semibold mb-2">
                        Hora de inicio
                    </label>

                    <input
                        v-model="form.horaini"
                        type="time"
                        class="border rounded w-full p-2"
                    />

                    <div
                        v-if="form.errors.horaini"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.horaini }}
                    </div>
                </div>

                <!-- HORA FIN -->

                <div>
                    <label class="block font-semibold mb-2">
                        Hora de finalización
                    </label>

                    <input
                        v-model="form.horafin"
                        type="time"
                        class="border rounded w-full p-2"
                    />

                    <div
                        v-if="form.errors.horafin"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.horafin }}
                    </div>
                </div>

                <!-- ESTADO -->

                <div>
                    <label class="block font-semibold mb-2"> Estado </label>

                    <select
                        v-model="form.estado"
                        class="border rounded w-full p-2"
                    >
                        <option :value="true">Activo</option>

                        <option :value="false">Inactivo</option>
                    </select>
                </div>

                <!-- BOTONES -->

                <div class="flex gap-3">
                    <Link
                        :href="route('vehiculo_horarios.index')"
                        class="bg-gray-500 text-white px-4 py-2 rounded"
                    >
                        Cancelar
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="bg-blue-600 text-white px-5 py-2 rounded disabled:opacity-50"
                    >
                        {{ form.processing ? "Actualizando..." : "Actualizar" }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
```
