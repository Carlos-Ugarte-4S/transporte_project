<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link } from "@inertiajs/vue3";
import { onMounted, onBeforeUnmount } from "vue";
import L from "leaflet";
import "leaflet/dist/leaflet.css";

const props = defineProps({
    ruta: {
        type: Object,
        required: true,
    },
});

let map = null;
let lineaRuta = null;
let marcadoresParadas = [];

/*
|--------------------------------------------------------------------------
| INICIALIZAR MAPA
|--------------------------------------------------------------------------
*/

onMounted(() => {
    inicializarMapa();
});

/*
|--------------------------------------------------------------------------
| CREAR MAPA
|--------------------------------------------------------------------------
*/

function inicializarMapa() {
    if (!props.ruta) {
        console.error("No se recibió la ruta.");
        return;
    }

    map = L.map("map").setView([-20.027486787, -63.526018412], 14);

    /*
    |--------------------------------------------------------------------------
    | MAPA BASE
    |--------------------------------------------------------------------------
    */

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap",
    }).addTo(map);

    /*
    |--------------------------------------------------------------------------
    | DIBUJAR RECORRIDO
    |--------------------------------------------------------------------------
    */

    dibujarRecorrido();

    /*
    |--------------------------------------------------------------------------
    | DIBUJAR PARADAS
    |--------------------------------------------------------------------------
    */

    dibujarParadas();
}

/*
|--------------------------------------------------------------------------
| DIBUJAR RECORRIDO
|--------------------------------------------------------------------------
*/

function dibujarRecorrido() {
    if (!props.ruta.coordenadas || props.ruta.coordenadas.length === 0) {
        console.warn("La ruta no tiene coordenadas.");
        return;
    }

    const coordenadas = props.ruta.coordenadas.map((coord) => [
        Number(coord.latitud),
        Number(coord.longitud),
    ]);

    lineaRuta = L.polyline(coordenadas, {
        color: props.ruta.tipo_ruta === "osrm" ? "red" : "blue",

        weight: 6,

        opacity: 0.85,
    }).addTo(map);

    /*
    |--------------------------------------------------------------------------
    | AJUSTAR MAPA
    |--------------------------------------------------------------------------
    */

    map.fitBounds(lineaRuta.getBounds());
}

/*
|--------------------------------------------------------------------------
| DIBUJAR PARADAS
|--------------------------------------------------------------------------
*/

function dibujarParadas() {
    if (!props.ruta.paradas || props.ruta.paradas.length === 0) {
        return;
    }

    props.ruta.paradas.forEach((parada) => {
        const marcador = L.marker([
            Number(parada.latitud),
            Number(parada.longitud),
        ]).addTo(map).bindPopup(`
                <strong>📍 ${parada.nombre || "Parada"}</strong>
                <br>
                ${parada.ubicacion || "Sin ubicación"}
                <br><br>
                Orden: ${parada.orden}
            `);

        marcadoresParadas.push(marcador);
    });
}

/*
|--------------------------------------------------------------------------
| LIMPIAR MAPA
|--------------------------------------------------------------------------
*/

onBeforeUnmount(() => {
    if (map) {
        map.remove();
    }
});
</script>

<template>
    <AdminLayout>
        <!-- ENCABEZADO -->

        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold">
                        {{ ruta.nombreruta }}
                    </h1>

                    <p class="text-gray-500 mt-1">
                        Información y recorrido de la ruta
                    </p>
                </div>

                <Link
                    :href="route('rutas.index')"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg"
                >
                    ← Volver
                </Link>
            </div>
        </div>

        <!-- DATOS DE LA RUTA -->

        <div class="bg-white rounded-xl shadow-md border p-6 mb-6">
            <h2 class="text-xl font-bold mb-5">Datos de la Ruta</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- NOMBRE -->

                <div>
                    <p class="text-sm text-gray-500">Nombre</p>

                    <p class="font-semibold text-lg">
                        {{ ruta.nombreruta }}
                    </p>
                </div>

                <!-- LINEA -->

                <div>
                    <p class="text-sm text-gray-500">Línea</p>

                    <p class="font-semibold text-lg">
                        {{ ruta.linea?.nombre ?? "Sin línea" }}
                    </p>
                </div>

                <!-- SINDICATO -->

                <div>
                    <p class="text-sm text-gray-500">Sindicato</p>

                    <p class="font-semibold text-lg">
                        {{ ruta.linea?.sindicato?.nombre ?? "Sin sindicato" }}
                    </p>
                </div>

                <!-- DISTANCIA -->

                <div>
                    <p class="text-sm text-gray-500">Distancia</p>

                    <p class="font-semibold text-lg">
                        {{ ruta.distancia ?? 0 }} km
                    </p>
                </div>

                <!-- ORIGEN -->

                <div>
                    <p class="text-sm text-gray-500">Origen</p>

                    <p class="font-semibold">
                        {{ ruta.origen }}
                    </p>
                </div>

                <!-- DESTINO -->

                <div>
                    <p class="text-sm text-gray-500">Destino</p>

                    <p class="font-semibold">
                        {{ ruta.destino }}
                    </p>
                </div>

                <!-- TIPO -->

                <div>
                    <p class="text-sm text-gray-500">Tipo de ruta</p>

                    <span
                        v-if="ruta.tipo_ruta === 'osrm'"
                        class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded-full font-semibold"
                    >
                        🛣️ OSRM
                    </span>

                    <span
                        v-else-if="ruta.tipo_ruta === 'manual'"
                        class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-semibold"
                    >
                        ✏️ Manual
                    </span>

                    <span
                        v-else
                        class="inline-block bg-purple-100 text-purple-700 px-3 py-1 rounded-full font-semibold"
                    >
                        🔄 Híbrida
                    </span>
                </div>

                <!-- ESTADO -->

                <div>
                    <p class="text-sm text-gray-500">Estado</p>

                    <span
                        v-if="ruta.estado === 'activo'"
                        class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full font-semibold"
                    >
                        🟢 Activo
                    </span>

                    <span
                        v-else
                        class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded-full font-semibold"
                    >
                        🔴 Inactivo
                    </span>
                </div>
            </div>
        </div>

        <!-- MAPA -->

        <div class="bg-white rounded-xl shadow-md border p-6 mb-6">
            <div class="mb-4">
                <h2 class="text-xl font-bold">🗺️ Recorrido de la Ruta</h2>

                <p class="text-gray-500 mt-1">
                    Visualización del recorrido almacenado.
                </p>
            </div>

            <div id="map" class="rounded-lg border" style="height: 600px"></div>
        </div>

        <!-- PARADAS -->

        <div class="bg-white rounded-xl shadow-md border p-6 mb-6">
            <h2 class="text-xl font-bold mb-5">📍 Paradas de la Ruta</h2>

            <div
                v-if="!ruta.paradas || ruta.paradas.length === 0"
                class="text-gray-500 italic"
            >
                Esta ruta no tiene paradas registradas.
            </div>

            <div v-else class="space-y-3">
                <div
                    v-for="(parada, index) in ruta.paradas"
                    :key="parada.idparada ?? index"
                    class="border rounded-lg p-4 bg-gray-50"
                >
                    <div class="flex items-start gap-4">
                        <!-- NUMERO -->

                        <div
                            class="w-10 h-10 rounded-full bg-yellow-500 text-white flex items-center justify-center font-bold flex-shrink-0"
                        >
                            {{ parada.orden }}
                        </div>

                        <!-- INFORMACION -->

                        <div class="flex-1">
                            <h3 class="font-bold text-lg">
                                {{ parada.nombre || "Sin nombre" }}
                            </h3>

                            <p class="text-gray-600">
                                {{ parada.ubicacion || "Sin ubicación" }}
                            </p>

                            <p class="text-sm text-gray-500 mt-2">
                                Latitud:
                                {{ Number(parada.latitud).toFixed(6) }}

                                <br />

                                Longitud:
                                {{ Number(parada.longitud).toFixed(6) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- INFORMACION DEL RECORRIDO -->

        <div class="bg-white rounded-xl shadow-md border p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">🛣️ Información del recorrido</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- COORDENADAS -->

                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Puntos del recorrido</p>

                    <p class="text-2xl font-bold">
                        {{ ruta.coordenadas?.length ?? 0 }}
                    </p>
                </div>

                <!-- PARADAS -->

                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Paradas</p>

                    <p class="text-2xl font-bold">
                        {{ ruta.paradas?.length ?? 0 }}
                    </p>
                </div>

                <!-- SEGMENTOS -->

                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Segmentos</p>

                    <p class="text-2xl font-bold">
                        {{ ruta.segmentos?.length ?? 0 }}
                    </p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
