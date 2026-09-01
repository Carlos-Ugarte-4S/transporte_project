<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link } from "@inertiajs/vue3";
import { onMounted, onBeforeUnmount, ref } from "vue";
import L from "leaflet";
import "leaflet/dist/leaflet.css";
import axios from "axios";

const props = defineProps({
    ruta: Object,
    lineas: Array,
});

/*
|--------------------------------------------------------------------------
| DATOS DE LA RUTA
|--------------------------------------------------------------------------
*/

const nombreruta = ref(props.ruta.nombreruta ?? "");
const idlinea = ref(props.ruta.idlinea ?? "");
const origen = ref(props.ruta.origen ?? "");
const destino = ref(props.ruta.destino ?? "");
const tipoRuta = ref(props.ruta.tipo_ruta ?? "manual");

/*
|--------------------------------------------------------------------------
| PUNTOS DEL RECORRIDO
|--------------------------------------------------------------------------
*/

const puntos = ref(
    (props.ruta.coordenadas ?? []).map((coord) => ({
        lat: Number(coord.latitud),
        lng: Number(coord.longitud),
    })),
);

/*
|--------------------------------------------------------------------------
| PARADAS
|--------------------------------------------------------------------------
*/

const paradas = ref(
    (props.ruta.paradas ?? []).map((parada) => ({
        idparada: parada.idparada,
        nombre: parada.nombre ?? "",
        ubicacion: parada.ubicacion ?? "",
        latitud: Number(parada.latitud),
        longitud: Number(parada.longitud),
        orden: Number(parada.orden),
    })),
);

/*
|--------------------------------------------------------------------------
| MAPA
|--------------------------------------------------------------------------
*/

let map = null;

let lineaRuta = null;
let lineaOSRM = null;

let marcadoresPuntos = [];
let marcadoresParadas = [];

/*
|--------------------------------------------------------------------------
| ESTADOS
|--------------------------------------------------------------------------
*/

const modoMapa = ref("ruta");

const guardando = ref(false);
const calculando = ref(false);

/*
|--------------------------------------------------------------------------
| INICIALIZAR MAPA
|--------------------------------------------------------------------------
*/

onMounted(() => {
    map = L.map("map").setView([-20.027486787, -63.526018412], 14);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap",
    }).addTo(map);

    map.on("click", manejarClickMapa);

    sincronizarMarcadores();
    dibujarRuta();
    mostrarParadas();

    ajustarMapa();
});

/*
|--------------------------------------------------------------------------
| CLICK EN EL MAPA
|--------------------------------------------------------------------------
*/

function manejarClickMapa(e) {
    /*
     * MODO PARADA
     */

    if (modoMapa.value === "parada") {
        agregarParada(e.latlng.lat, e.latlng.lng);

        return;
    }

    /*
     * MODO RUTA
     */

    if (modoMapa.value === "ruta") {
        puntos.value.push({
            lat: e.latlng.lat,
            lng: e.latlng.lng,
        });

        sincronizarMarcadores();
        dibujarRuta();
        ajustarMapa();
    }
}

/*
|--------------------------------------------------------------------------
| MARCADORES DE PUNTOS
|--------------------------------------------------------------------------
*/

function crearMarcadorPunto(punto, index) {
    const marker = L.marker([punto.lat, punto.lng], {
        draggable: true,
    }).addTo(map);

    /*
     * MOVER PUNTO
     */

    marker.on("dragend", function (e) {
        const posicion = e.target.getLatLng();

        puntos.value[index].lat = posicion.lat;
        puntos.value[index].lng = posicion.lng;

        dibujarRuta();
    });

    /*
     * CLIC DERECHO = ELIMINAR
     */

    marker.on("contextmenu", function () {
        eliminarPunto(index);
    });

    marcadoresPuntos.push(marker);

    return marker;
}

/*
|--------------------------------------------------------------------------
| SINCRONIZAR MARCADORES
|--------------------------------------------------------------------------
*/

function sincronizarMarcadores() {
    /*
     * Eliminar marcadores anteriores
     */

    marcadoresPuntos.forEach((marker) => {
        if (map && map.hasLayer(marker)) {
            map.removeLayer(marker);
        }
    });

    marcadoresPuntos = [];

    /*
     * Crear nuevamente los marcadores
     */

    puntos.value.forEach((punto, index) => {
        crearMarcadorPunto(punto, index);
    });
}

/*
|--------------------------------------------------------------------------
| DIBUJAR RECORRIDO MANUAL
|--------------------------------------------------------------------------
*/

function dibujarRuta() {
    /*
     * Eliminar línea anterior
     */

    if (lineaRuta) {
        map.removeLayer(lineaRuta);
        lineaRuta = null;
    }

    /*
     * También quitamos la línea OSRM
     */

    if (lineaOSRM) {
        map.removeLayer(lineaOSRM);
        lineaOSRM = null;
    }

    /*
     * No podemos dibujar con menos de 2 puntos
     */

    if (puntos.value.length < 2) {
        return;
    }

    const coordenadas = puntos.value.map((punto) => [punto.lat, punto.lng]);

    lineaRuta = L.polyline(coordenadas, {
        color: tipoRuta.value === "manual" ? "blue" : "red",

        weight: 6,
        opacity: 0.85,
    }).addTo(map);
}

/*
|--------------------------------------------------------------------------
| MOSTRAR PARADAS
|--------------------------------------------------------------------------
*/

function mostrarParadas() {
    /*
     * Eliminar marcadores anteriores
     */

    marcadoresParadas.forEach((marker) => {
        if (map && map.hasLayer(marker)) {
            map.removeLayer(marker);
        }
    });

    marcadoresParadas = [];

    /*
     * Crear marcadores
     */

    paradas.value.forEach((parada) => {
        const marcador = L.marker([parada.latitud, parada.longitud]).addTo(map)
            .bindPopup(`
                <strong>📍 Parada ${parada.orden}</strong>
                <br>
                ${parada.nombre || "Sin nombre"}
            `);

        marcadoresParadas.push(marcador);
    });
}

/*
|--------------------------------------------------------------------------
| AGREGAR PARADA
|--------------------------------------------------------------------------
*/

function agregarParada(lat, lng) {
    const nuevaParada = {
        idparada: null,
        nombre: "",
        ubicacion: "",
        latitud: Number(lat),
        longitud: Number(lng),
        orden: paradas.value.length + 1,
    };

    paradas.value.push(nuevaParada);

    mostrarParadas();

    /*
     * Volvemos automáticamente al modo ruta
     */

    modoMapa.value = "ruta";
}

/*
|--------------------------------------------------------------------------
| ELIMINAR PUNTO
|--------------------------------------------------------------------------
*/

function eliminarPunto(index) {
    if (puntos.value.length <= 2) {
        alert("La ruta debe conservar al menos 2 puntos.");

        return;
    }

    puntos.value.splice(index, 1);

    sincronizarMarcadores();
    dibujarRuta();
    ajustarMapa();
}

/*
|--------------------------------------------------------------------------
| SUBIR PUNTO
|--------------------------------------------------------------------------
*/

function subirPunto(index) {
    if (index === 0) {
        return;
    }

    const temporal = puntos.value[index];

    puntos.value[index] = puntos.value[index - 1];

    puntos.value[index - 1] = temporal;

    sincronizarMarcadores();
    dibujarRuta();
}

/*
|--------------------------------------------------------------------------
| BAJAR PUNTO
|--------------------------------------------------------------------------
*/

function bajarPunto(index) {
    if (index === puntos.value.length - 1) {
        return;
    }

    const temporal = puntos.value[index];

    puntos.value[index] = puntos.value[index + 1];

    puntos.value[index + 1] = temporal;

    sincronizarMarcadores();
    dibujarRuta();
}

/*
|--------------------------------------------------------------------------
| ELIMINAR PARADA
|--------------------------------------------------------------------------
*/

function eliminarParada(index) {
    paradas.value.splice(index, 1);

    paradas.value.forEach((parada, i) => {
        parada.orden = i + 1;
    });

    mostrarParadas();
}

/*
|--------------------------------------------------------------------------
| LIMPIAR RECORRIDO
|--------------------------------------------------------------------------
*/

function limpiarRecorrido() {
    if (!confirm("¿Deseas eliminar todo el recorrido actual?")) {
        return;
    }

    /*
     * Vaciar puntos
     */

    puntos.value = [];

    /*
     * Eliminar marcadores de puntos
     */

    marcadoresPuntos.forEach((marker) => {
        if (map && map.hasLayer(marker)) {
            map.removeLayer(marker);
        }
    });

    marcadoresPuntos = [];

    /*
     * Eliminar línea manual
     */

    if (lineaRuta) {
        map.removeLayer(lineaRuta);
        lineaRuta = null;
    }

    /*
     * Eliminar línea OSRM
     */

    if (lineaOSRM) {
        map.removeLayer(lineaOSRM);
        lineaOSRM = null;
    }

    /*
     * Regresar al modo ruta
     */

    modoMapa.value = "ruta";

    /*
     * Actualizar mapa
     */

    if (map) {
        map.invalidateSize();
    }
}

/*
|--------------------------------------------------------------------------
| LIMPIAR TODO
|--------------------------------------------------------------------------
*/

function limpiarTodo() {
    if (
        !confirm(
            "¿Deseas eliminar el recorrido y todas las paradas del editor?",
        )
    ) {
        return;
    }

    limpiarRecorrido();

    paradas.value = [];

    mostrarParadas();
}

/*
|--------------------------------------------------------------------------
| AJUSTAR MAPA
|--------------------------------------------------------------------------
*/

function ajustarMapa() {
    if (!map) {
        return;
    }

    if (puntos.value.length >= 2) {
        const coordenadas = puntos.value.map((punto) => [punto.lat, punto.lng]);

        const bounds = L.latLngBounds(coordenadas);

        map.fitBounds(bounds, {
            padding: [30, 30],
        });

        return;
    }

    if (puntos.value.length === 1) {
        map.setView([puntos.value[0].lat, puntos.value[0].lng], 16);
    }
}

/*
|--------------------------------------------------------------------------
| CALCULAR OSRM
|--------------------------------------------------------------------------
*/

async function calcularRutaOSRM() {
    if (puntos.value.length < 2) {
        alert("Necesitas al menos 2 puntos para calcular la ruta.");

        return;
    }

    calculando.value = true;

    try {
        const response = await axios.post(route("rutas.calcular"), {
            puntos: puntos.value,
        });

        const coordinates = response.data.coordinates;

        /*
         * OSRM devuelve:
         *
         * [lng, lat]
         */

        const latLngs = coordinates.map((coord) => [coord[1], coord[0]]);

        /*
         * Eliminar línea OSRM anterior
         */

        if (lineaOSRM) {
            map.removeLayer(lineaOSRM);
        }

        /*
         * Dibujar nueva ruta OSRM
         */

        lineaOSRM = L.polyline(latLngs, {
            color: "red",
            weight: 6,
            opacity: 0.9,
        }).addTo(map);

        /*
         * Ajustar mapa
         */

        map.fitBounds(lineaOSRM.getBounds(), {
            padding: [30, 30],
        });
    } catch (error) {
        console.error("Error OSRM:", error);

        alert("No se pudo calcular la ruta.");
    } finally {
        calculando.value = false;
    }
}

/*
|--------------------------------------------------------------------------
| GUARDAR RUTA
|--------------------------------------------------------------------------
*/

async function guardarRuta() {
    if (puntos.value.length < 2) {
        alert("La ruta necesita al menos 2 puntos.");

        return;
    }

    guardando.value = true;

    try {
        await axios.post(route("rutas.guardar"), {
            idruta: props.ruta.idruta,

            nombreruta: nombreruta.value,

            idlinea: idlinea.value,

            origen: origen.value,

            destino: destino.value,

            tipo_ruta: tipoRuta.value,

            puntos: puntos.value,

            paradas: paradas.value,

            coordenadas: puntos.value,

            segmentos: [
                {
                    orden: 1,

                    tipo: tipoRuta.value === "manual" ? "manual" : "osrm",

                    geometria: puntos.value,
                },
            ],

            distancia: props.ruta.distancia ?? 0,

            estado: props.ruta.estado,
        });

        alert("Ruta actualizada correctamente.");

        window.location.href = route("rutas.index");
    } catch (error) {
        console.error("Error al guardar:", error);

        if (error.response?.data?.errors) {
            console.error("Errores de validación:", error.response.data.errors);
        }

        alert("No se pudo actualizar la ruta.");
    } finally {
        guardando.value = false;
    }
}

/*
|--------------------------------------------------------------------------
| CAMBIO DE MODO
|--------------------------------------------------------------------------
*/

function activarModoRuta() {
    modoMapa.value = "ruta";

    /*
     * Actualizar tamaño del mapa
     */

    setTimeout(() => {
        map?.invalidateSize();
    }, 100);
}

function activarModoParada() {
    modoMapa.value = "parada";

    setTimeout(() => {
        map?.invalidateSize();
    }, 100);
}

/*
|--------------------------------------------------------------------------
| DESTRUIR MAPA
|--------------------------------------------------------------------------
*/

onBeforeUnmount(() => {
    if (map) {
        map.remove();
        map = null;
    }
});
</script>

<template>
    <AdminLayout>
        <div class="p-6">
            <!-- ENCABEZADO -->

            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold">Editar Ruta</h1>

                        <p class="text-gray-500 mt-1">
                            Modifique los datos y el recorrido de la ruta.
                        </p>
                    </div>

                    <Link
                        :href="route('rutas.show', props.ruta.idruta)"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg"
                    >
                        ← Volver
                    </Link>
                </div>
            </div>

            <!-- DATOS -->

            <div class="bg-white rounded-xl shadow-md border p-6 mb-6">
                <h2 class="text-xl font-bold mb-5">Datos de la Ruta</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 font-semibold"> Nombre </label>

                        <input
                            v-model="nombreruta"
                            type="text"
                            class="w-full border rounded-lg p-3"
                        />
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold"> Línea </label>

                        <select
                            v-model="idlinea"
                            class="w-full border rounded-lg p-3"
                        >
                            <option value="">Seleccione una línea</option>

                            <option
                                v-for="linea in lineas"
                                :key="linea.idlinea"
                                :value="linea.idlinea"
                            >
                                {{ linea.nombre }}
                                -
                                {{ linea.sindicato?.nombre ?? "Sin sindicato" }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold"> Origen </label>

                        <input
                            v-model="origen"
                            type="text"
                            class="w-full border rounded-lg p-3"
                        />
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold">
                            Destino
                        </label>

                        <input
                            v-model="destino"
                            type="text"
                            class="w-full border rounded-lg p-3"
                        />
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold">
                            Tipo de ruta
                        </label>

                        <select
                            v-model="tipoRuta"
                            class="w-full border rounded-lg p-3"
                        >
                            <option value="osrm">OSRM</option>

                            <option value="manual">Manual</option>

                            <option value="hibrida">Híbrida</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- HERRAMIENTAS -->

            <div class="bg-white rounded-xl shadow-md border p-6 mb-6">
                <div class="flex flex-wrap gap-3">
                    <button
                        @click="calcularRutaOSRM"
                        :disabled="calculando"
                        class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg"
                    >
                        {{
                            calculando ? "Calculando..." : "🛣️ Recalcular OSRM"
                        }}
                    </button>
                    <button
                        @click="activarModoParada"
                        :class="
                            modoMapa === 'parada'
                                ? 'bg-yellow-700'
                                : 'bg-yellow-500 hover:bg-yellow-600'
                        "
                        class="px-5 py-2 text-white rounded-lg"
                    >
                        📍 Agregar parada
                    </button>

                    <button
                        @click="activarModoRuta"
                        :class="
                            modoMapa === 'ruta'
                                ? 'bg-blue-800'
                                : 'bg-blue-600 hover:bg-blue-700'
                        "
                        class="px-5 py-2 text-white rounded-lg"
                    >
                        🛣️ Editar recorrido
                    </button>

                    <button
                        @click="limpiarRecorrido"
                        type="button"
                        class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg"
                    >
                        🗑️ Limpiar recorrido
                    </button>

                    <button
                        @click="guardarRuta"
                        :disabled="guardando"
                        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg"
                    >
                        {{ guardando ? "Guardando..." : "💾 Guardar cambios" }}
                    </button>

                    <button
                        @click="limpiarTodo"
                        type="button"
                        class="px-5 py-2 bg-red-800 hover:bg-red-900 text-white rounded-lg"
                    >
                        🗑️ Limpiar ruta completa
                    </button>
                </div>
            </div>

            <!-- MAPA -->

            <div class="bg-white rounded-xl shadow-md border p-6 mb-6">
                <div class="flex justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold">🗺️ Editar recorrido</h2>

                        <p class="text-gray-500">
                            Modo:
                            {{
                                modoMapa === "ruta"
                                    ? "Editar recorrido"
                                    : "Agregar parada"
                            }}
                        </p>
                    </div>

                    <span
                        class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full"
                    >
                        {{ puntos.length }} puntos
                    </span>
                </div>

                <div
                    id="map"
                    class="rounded-lg border"
                    style="height: 600px"
                ></div>
            </div>

            <!-- PUNTOS -->

            <div class="bg-white rounded-xl shadow-md border p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">🛣️ Puntos del recorrido</h2>

                <div v-if="puntos.length === 0" class="text-gray-500">
                    No existen puntos.
                </div>

                <div
                    v-for="(punto, index) in puntos"
                    :key="index"
                    class="border rounded-lg p-4 mb-3 flex justify-between"
                >
                    <div>
                        <strong> Punto {{ index + 1 }} </strong>

                        <p class="text-sm text-gray-600">
                            Latitud:
                            {{ punto.lat.toFixed(6) }}

                            <br />

                            Longitud:
                            {{ punto.lng.toFixed(6) }}
                        </p>
                    </div>

                    <div class="text-xs text-gray-500 mt-1">
                        💡 Arrastra el marcador para moverlo.
                        <br />
                        🖱️ Clic derecho sobre el marcador para eliminarlo.
                    </div>
                </div>
            </div>

            <!-- PARADAS -->

            <div class="bg-white rounded-xl shadow-md border p-6">
                <h2 class="text-xl font-bold mb-4">📍 Paradas</h2>

                <div v-if="paradas.length === 0" class="text-gray-500">
                    No existen paradas.
                </div>

                <div
                    v-for="(parada, index) in paradas"
                    :key="index"
                    class="border rounded-lg p-4 mb-3"
                >
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="font-semibold"> Nombre </label>

                            <input
                                v-model="parada.nombre"
                                class="w-full border rounded-lg p-2"
                            />
                        </div>

                        <div>
                            <label class="font-semibold"> Ubicación </label>

                            <input
                                v-model="parada.ubicacion"
                                class="w-full border rounded-lg p-2"
                            />
                        </div>
                    </div>

                    <div class="text-sm text-gray-500 mt-2">
                        Lat:
                        {{ parada.latitud.toFixed(6) }}

                        <br />

                        Lng:
                        {{ parada.longitud.toFixed(6) }}
                    </div>

                    <button
                        @click="eliminarParada(index)"
                        class="mt-3 bg-red-500 text-white px-3 py-1 rounded"
                    >
                        🗑️ Eliminar
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
