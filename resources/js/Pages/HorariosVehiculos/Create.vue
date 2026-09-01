```vue
<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    vehiculos: Array,
    turnos: Array,
});

const form = useForm({
    idvehiculo: "",
    idturno: "",
    dia: "",
    horaini: "",
    horafin: "",
});

function guardarRuta() {
    // Puntos manuales
    form.puntos = puntos.value.map((punto) => ({
        lat: punto.lat,
        lng: punto.lng,
    }));

    // Paradas
    form.paradas = paradas.value.map((parada, index) => ({
        nombre: parada.nombre,
        ubicacion: parada.ubicacion,
        latitud: parada.latitud,
        longitud: parada.longitud,
        orden: index + 1,
    }));

    // Coordenadas completas de la ruta
    form.coordenadas = form.coordenadas.map((coord) => ({
        lat: Number(coord.lat),
        lng: Number(coord.lng),
    }));

    // Segmentos
    form.segmentos = segmentos.value.map((segmento, index) => ({
        orden: index + 1,
        tipo: segmento.tipo,
        geometria: segmento.geometria,
    }));

    // Validaciones
    if (form.nombreruta.trim() === "") {
        alert("Debes ingresar el nombre de la ruta.");
        return;
    }

    if (!form.idlinea) {
        alert("Debes seleccionar una línea.");
        return;
    }

    if (puntos.value.length < 2) {
        alert("La ruta debe tener al menos 2 puntos.");
        return;
    }

    if (form.coordenadas.length < 2) {
        alert("Primero debes construir la ruta.");
        return;
    }

    if (form.segmentos.length === 0) {
        alert("La ruta no tiene segmentos.");
        return;
    }

    console.log("========== DATOS A GUARDAR ==========");
    console.log("PUNTOS:", form.puntos);
    console.log("PARADAS:", form.paradas);
    console.log("COORDENADAS:", form.coordenadas);
    console.log("SEGMENTOS:", form.segmentos);
    console.log("TIPO:", form.tipo_ruta);

    form.post(route("rutas.guardar"));
}
</script>

<template>
    <AdminLayout>
        <div class="p-6">
            <h1 class="text-3xl font-bold mb-6">Nuevo Horario de Vehículo</h1>

            <form
                @submit.prevent="guardar"
                class="bg-white shadow rounded-lg p-6 space-y-5"
            >
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
                        {{ form.processing ? "Guardando..." : "Guardar" }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
```
