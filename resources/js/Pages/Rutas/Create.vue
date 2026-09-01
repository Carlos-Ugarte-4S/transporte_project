<script setup>
/*
|--------------------------------------------------------------------------
| IMPORTACIONES
|--------------------------------------------------------------------------
*/

import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { onMounted, onBeforeUnmount, ref } from "vue";

import L from "leaflet";
import "leaflet/dist/leaflet.css";

import axios from "axios";

/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({
    lineas: {
        type: Array,
        default: () => [],
    },
});

/*
|--------------------------------------------------------------------------
| FORMULARIO
|--------------------------------------------------------------------------
*/

const form = useForm({
    nombreruta: "",
    idlinea: "",
    origen: "",
    destino: "",

    tipo_ruta: "manual",

    puntos: [],

    paradas: [],

    coordenadas: [],

    segmentos: [],

    distancia: 0,
});

/*
|--------------------------------------------------------------------------
| DATOS DE LA RUTA
|--------------------------------------------------------------------------
*/

/*
 * Estos son los puntos MANUALES.
 *
 * IMPORTANTE:
 *
 * Estos puntos NO son la geometría OSRM.
 *
 * Son los puntos que el usuario define
 * haciendo clic en el mapa.
 *
 * OSRM utilizará estos puntos como origen,
 * intermedios y destino para calcular la ruta.
 */
const puntos = ref([]);

/*
 * Paradas reales de la ruta.
 */
const paradas = ref([]);

/*
 * Geometría generada por OSRM.
 *
 * Formato:
 *
 * [
 *   { lat: ..., lng: ... },
 *   ...
 * ]
 */
const geometriaOSRM = ref([]);

/*
|--------------------------------------------------------------------------
| MODO DEL MAPA
|--------------------------------------------------------------------------
*/

/*
 * ruta
 *  -> agregar puntos manuales
 *
 * parada
 *  -> agregar una parada
 */
const modoMapa = ref("ruta");

/*
|--------------------------------------------------------------------------
| LEAFLET
|--------------------------------------------------------------------------
*/

let map = null;

const segmentos = ref([]);

const guardarSegmento = (tipo, coordinates) => {
    segmentos.value = [
        {
            orden: 1,
            tipo: tipo,
            geometria: {
                type: "LineString",
                coordinates: coordinates,
            },
        },
    ];
};
/*
 * Marcadores correspondientes a los puntos manuales.
 *
 * MUY IMPORTANTE:
 *
 * El índice del marcador corresponde
 * al índice de puntos.value.
 *
 * marcadoresPuntos[0] -> puntos[0]
 * marcadoresPuntos[1] -> puntos[1]
 * etc.
 */
let marcadoresPuntos = [];

/*
 * Marcadores de las paradas.
 */
let marcadoresParadas = [];

/*
 * Línea manual azul.
 */
let lineaManual = null;

/*
 * Línea calculada por OSRM roja.
 */
let lineaOSRM = null;

/*
|--------------------------------------------------------------------------
| ICONO DE PARADA
|--------------------------------------------------------------------------
*/

function crearIconoParada() {
    return L.divIcon({
        className: "",

        html: `
            <div style="
                width: 30px;
                height: 30px;
                background: #f59e0b;
                border: 3px solid white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 6px rgba(0,0,0,0.4);
                color: white;
                font-size: 16px;
                font-weight: bold;
            ">
                📍
            </div>
        `,

        iconSize: [30, 30],

        iconAnchor: [15, 15],
    });
}

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

    /*
     * CLICK IZQUIERDO
     *
     * Se utiliza para agregar puntos.
     */
    map.on("click", manejarClickMapa);
});

/*
|--------------------------------------------------------------------------
| CLICK SOBRE MAPA
|--------------------------------------------------------------------------
*/

function manejarClickMapa(e) {
    /*
     * Si estamos agregando una parada,
     * NO agregamos un punto manual.
     */
    if (modoMapa.value === "parada") {
        agregarParada(e.latlng.lat, e.latlng.lng);

        return;
    }

    /*
     * Caso normal:
     *
     * agregar punto manual.
     */
    agregarPuntoRuta(e.latlng.lat, e.latlng.lng);
}

/*
|--------------------------------------------------------------------------
| AGREGAR PUNTO MANUAL
|--------------------------------------------------------------------------
*/

function agregarPuntoRuta(lat, lng) {
    /*
     * Evitar puntos prácticamente iguales.
     */
    const existe = puntos.value.some(
        (punto) =>
            Math.abs(punto.lat - lat) < 0.00001 &&
            Math.abs(punto.lng - lng) < 0.00001,
    );

    if (existe) {
        alert("Este punto ya existe.");

        return;
    }

    /*
     * Crear punto.
     */
    const punto = {
        lat,
        lng,
    };

    /*
     * Guardar punto.
     */
    puntos.value.push(punto);

    /*
     * Crear marcador.
     */
    crearMarcadorPunto(punto, puntos.value.length - 1);

    /*
     * Actualizar línea manual.
     */
    dibujarRutaManual();

    /*
     * Si ya existía una ruta OSRM,
     * la quitamos porque los puntos base cambiaron.
     *
     * IMPORTANTE:
     *
     * La geometría OSRM anterior ya no representa
     * los puntos manuales actuales.
     */
    limpiarLineaOSRM();
}

/*
|--------------------------------------------------------------------------
| CREAR MARCADOR DEL PUNTO
|--------------------------------------------------------------------------
*/

function guardarRuta() {
    /*
    |--------------------------------------------------------------------------
    | PREPARAR PUNTOS
    |--------------------------------------------------------------------------
    */

    form.puntos = puntos.value.map((punto) => ({
        lat: Number(punto.lat),
        lng: Number(punto.lng),
    }));

    /*
    |--------------------------------------------------------------------------
    | PREPARAR PARADAS
    |--------------------------------------------------------------------------
    */

    form.paradas = paradas.value.map((parada, index) => ({
        nombre: parada.nombre?.trim() || "",
        ubicacion: parada.ubicacion?.trim() || null,
        latitud: Number(parada.latitud),
        longitud: Number(parada.longitud),
        orden: index + 1,
    }));

    /*
    |--------------------------------------------------------------------------
    | PREPARAR COORDENADAS
    |--------------------------------------------------------------------------
    */

    form.coordenadas = form.coordenadas.map((coord) => ({
        lat: Number(coord.lat),
        lng: Number(coord.lng),
    }));

    /*
    |--------------------------------------------------------------------------
    | PREPARAR SEGMENTOS
    |--------------------------------------------------------------------------
    */

    form.segmentos = segmentos.value.map((segmento, index) => ({
        orden: index + 1,
        tipo: segmento.tipo,
        geometria: segmento.geometria,
    }));

    /*
    |--------------------------------------------------------------------------
    | VALIDACIONES
    |--------------------------------------------------------------------------
    */

    if (!form.nombreruta?.trim()) {
        alert("Debes ingresar el nombre de la ruta.");
        return;
    }

    if (!form.idlinea) {
        alert("Debes seleccionar una línea.");
        return;
    }

    if (!form.origen?.trim()) {
        alert("Debes ingresar el origen.");
        return;
    }

    if (!form.destino?.trim()) {
        alert("Debes ingresar el destino.");
        return;
    }

    if (!["osrm", "manual"].includes(form.tipo_ruta)) {
        alert("Debes seleccionar un tipo de ruta válido.");
        return;
    }

    if (form.puntos.length < 2) {
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

    /*
    |--------------------------------------------------------------------------
    | DEBUG
    |--------------------------------------------------------------------------
    */

    console.log("========== GUARDANDO RUTA ==========");

    console.log("Nombre:", form.nombreruta);
    console.log("Línea:", form.idlinea);
    console.log("Origen:", form.origen);
    console.log("Destino:", form.destino);
    console.log("Tipo de ruta:", form.tipo_ruta);
    console.log("Distancia:", form.distancia);
    console.log("Puntos:", form.puntos);
    console.log("Paradas:", form.paradas);
    console.log("Coordenadas:", form.coordenadas);
    console.log("Segmentos:", form.segmentos);

    /*
    |--------------------------------------------------------------------------
    | ENVIAR A LARAVEL
    |--------------------------------------------------------------------------
    */

    form.post(route("rutas.guardar"), {
        preserveScroll: true,

        onSuccess: () => {
            console.log("Ruta guardada correctamente.");
        },

        onError: (errors) => {
            console.error("Errores de validación:", errors);
        },

        onFinish: () => {
            console.log("Petición finalizada.");
        },
    });
}

function crearMarcadorPunto(punto, index) {
    const marcador = L.circleMarker([punto.lat, punto.lng], {
        radius: 7,

        color: "#1f2937",

        fillColor: "#ffffff",

        fillOpacity: 1,

        weight: 3,
    }).addTo(map);

    /*
     * Popup.
     */
    marcador.bindPopup(`
        <strong>Punto ${index + 1}</strong><br>
        Lat: ${punto.lat.toFixed(6)}<br>
        Lng: ${punto.lng.toFixed(6)}
        <br><br>
        <small>
            Arrastra para mover.<br>
            Clic derecho para eliminar.
        </small>
    `);

    /*
     * ARRATRAR
     *
     * Actualiza el punto manual.
     */
    marcador.on("mousedown", function (e) {
        /*
         * No hacemos nada especial aquí.
         *
         * Leaflet maneja el drag del CircleMarker
         * si se utilizara draggable.
         *
         * Por eso el punto manual se implementa
         * como marcador draggable abajo.
         */
    });

    /*
     * Para permitir arrastre utilizamos Marker.
     *
     * Reemplazamos el CircleMarker.
     */
    map.removeLayer(marcador);

    const marcadorMovible = L.marker([punto.lat, punto.lng], {
        draggable: true,

        autoPan: true,
    }).addTo(map);

    /*
     * Popup.
     */
    marcadorMovible.bindPopup(`
        <strong>Punto ${index + 1}</strong><br>
        Lat: ${punto.lat.toFixed(6)}<br>
        Lng: ${punto.lng.toFixed(6)}
        <br><br>
        <small>
            Arrastra para mover.<br>
            Clic derecho para eliminar.
        </small>
    `);

    /*
     |--------------------------------------------------------------------------
     | MOVER PUNTO
     |--------------------------------------------------------------------------
     */

    marcadorMovible.on("dragend", function (e) {
        const posicion = e.target.getLatLng();

        /*
         * Actualizar el punto correspondiente.
         */
        puntos.value[index] = {
            lat: posicion.lat,

            lng: posicion.lng,
        };

        /*
         * Redibujar la ruta manual.
         */
        dibujarRutaManual();

        /*
         * El OSRM anterior deja de ser válido.
         */
        limpiarLineaOSRM();
    });

    /*
     |--------------------------------------------------------------------------
     | CLIC DERECHO = ELIMINAR
     |--------------------------------------------------------------------------
     */

    marcadorMovible.on("contextmenu", function (e) {
        /*
         * Evitar que aparezca el menú
         * contextual del navegador.
         */
        if (e.originalEvent) {
            e.originalEvent.preventDefault();
        }

        eliminarPunto(index);
    });

    /*
     * Guardar marcador.
     */
    marcadoresPuntos[index] = marcadorMovible;
}

/*
|--------------------------------------------------------------------------
| ELIMINAR PUNTO MANUAL
|--------------------------------------------------------------------------
|
| Se ejecuta mediante:
|
| 🖱️ CLIC DERECHO SOBRE EL PUNTO
|
| o desde la lista mediante eliminarPuntoLista().
|
|--------------------------------------------------------------------------
*/

function eliminarPunto(index) {
    /*
     * Verificar índice.
     */
    if (index < 0 || index >= puntos.value.length) {
        return;
    }

    /*
     * Eliminar marcador.
     */
    const marcador = marcadoresPuntos[index];

    if (marcador) {
        map.removeLayer(marcador);
    }

    /*
     * Eliminar punto.
     */
    puntos.value.splice(index, 1);

    /*
     * Eliminar marcador del array.
     */
    marcadoresPuntos.splice(index, 1);

    /*
     * Reorganizar índices.
     *
     * Esto es importante porque después de eliminar
     * el punto 2:
     *
     * punto 3 pasa a ser punto 2.
     */
    reconstruirMarcadoresPuntos();

    /*
     * Redibujar línea manual.
     */
    dibujarRutaManual();

    /*
     * Eliminar OSRM anterior.
     */
    limpiarLineaOSRM();
}

/*
|--------------------------------------------------------------------------
| RECONSTRUIR MARCADORES
|--------------------------------------------------------------------------
|
| Después de eliminar un punto necesitamos que:
|
| puntos[0] -> marcador[0]
| puntos[1] -> marcador[1]
| puntos[2] -> marcador[2]
|
|--------------------------------------------------------------------------
*/

function reconstruirMarcadoresPuntos() {
    /*
     * Guardar posiciones actuales.
     */
    const posiciones = puntos.value.map((punto) => [punto.lat, punto.lng]);

    /*
     * Eliminar todos los marcadores actuales.
     */
    marcadoresPuntos.forEach((marcador) => {
        if (map.hasLayer(marcador)) {
            map.removeLayer(marcador);
        }
    });

    /*
     * Vaciar array.
     */
    marcadoresPuntos = [];

    /*
     * Crear nuevamente los marcadores.
     */
    posiciones.forEach(([lat, lng], index) => {
        crearMarcadorPunto(
            {
                lat,
                lng,
            },
            index,
        );
    });
}

/*
|--------------------------------------------------------------------------
| DIBUJAR RUTA MANUAL
|--------------------------------------------------------------------------
*/

function dibujarRutaManual() {
    /// Eliminar línea manual anterior.
    if (lineaManual) {
        map.removeLayer(lineaManual);

        lineaManual = null;
    }

    /*
     * Si hay menos de 2 puntos,
     * no podemos crear una línea.
     */
    if (puntos.value.length < 2) {
        form.coordenadas = puntos.value.map((punto) => ({
            lat: punto.lat,
            lng: punto.lng,
        }));

        return;
    }

    /*
     * Convertir puntos a formato Leaflet.
     */
    const coordenadas = puntos.value.map((punto) => [punto.lat, punto.lng]);

    /*
     * Crear línea azul.
     */
    lineaManual = L.polyline(coordenadas, {
        color: "blue",

        weight: 5,

        opacity: 0.85,
    }).addTo(map);

    // Guardar geometría manual.

    form.coordenadas = puntos.value.map((punto) => ({
        lat: punto.lat,
        lng: punto.lng,
    }));
    // Guardar segmentos manual
    segmentos.value = [
        {
            orden: 1,
            tipo: "manual",
            geometria: {
                type: "LineString",
                coordinates: puntos.value.map((punto) => [
                    punto.lng,
                    punto.lat,
                ]),
            },
        },
    ];
}

/*
|--------------------------------------------------------------------------
| LIMPIAR LÍNEA OSRM
|--------------------------------------------------------------------------
*/

function limpiarLineaOSRM() {
    if (lineaOSRM) {
        map.removeLayer(lineaOSRM);

        lineaOSRM = null;
    }

    /*
     * La geometría OSRM anterior ya no es válida.
     */
    geometriaOSRM.value = [];

    /*
     * Si la ruta es manual,
     * volvemos a guardar los puntos manuales.
     */
    if (form.tipo_ruta === "manual") {
        form.coordenadas = puntos.value.map((punto) => ({
            lat: punto.lat,
            lng: punto.lng,
        }));
    }
}

/*
|--------------------------------------------------------------------------
| CALCULAR OSRM
|--------------------------------------------------------------------------
|
| IMPORTANTE:
|
| OSRM SIEMPRE utiliza los puntos manuales actuales.
|
| Ejemplo:
|
| Punto 1
|    ↓
| Punto 2
|    ↓
| Punto 3
|    ↓
| Punto 4
|
| Estos son enviados a Laravel.
|
|--------------------------------------------------------------------------
*/

async function calcularRutaOSRM() {
    /*
     * Necesitamos al menos 2 puntos manuales.
     */
    if (puntos.value.length < 2) {
        alert("Debes definir al menos 2 puntos manuales.");

        return;
    }

    try {
        /*
         * ============================================================
         * 1. ENVIAR LOS PUNTOS MANUALES A LARAVEL
         * ============================================================
         *
         * Aquí solamente enviamos los puntos que el usuario
         * colocó en el mapa.
         */

        const response = await axios.post(route("rutas.calcular"), {
            puntos: puntos.value,
        });

        /*
         * ============================================================
         * 2. VALIDAR RESPUESTA DE OSRM
         * ============================================================
         */

        if (
            !response.data ||
            !response.data.coordinates ||
            response.data.coordinates.length < 2
        ) {
            alert("OSRM no devolvió una geometría válida.");

            return;
        }

        /*
         * ============================================================
         * 3. COORDENADAS QUE DEVUELVE OSRM
         * ============================================================
         *
         * OSRM devuelve:
         *
         * [
         *     [lng, lat],
         *     [lng, lat],
         *     ...
         * ]
         *
         * Leaflet necesita:
         *
         * [
         *     [lat, lng],
         *     [lat, lng],
         *     ...
         * ]
         */

        const coordenadasOSRM = response.data.coordinates;

        const coordenadasLeaflet = coordenadasOSRM.map((coord) => [
            coord[1], // latitud

            coord[0], // longitud
        ]);

        /*
         * ============================================================
         * 4. GUARDAR GEOMETRÍA OSRM
         * ============================================================
         *
         * Esta variable es para trabajar en el frontend.
         *
         * NO es todavía el registro de la base de datos.
         */

        geometriaOSRM.value = coordenadasOSRM.map((coord) => ({
            lat: coord[1],

            lng: coord[0],
        }));

        /*
         * ============================================================
         * 5. ELIMINAR LÍNEA OSRM ANTERIOR
         * ============================================================
         */

        if (lineaOSRM) {
            map.removeLayer(lineaOSRM);

            lineaOSRM = null;
        }

        /*
         * ============================================================
         * 6. DIBUJAR NUEVA LÍNEA OSRM
         * ============================================================
         */

        lineaOSRM = L.polyline(coordenadasLeaflet, {
            color: "red",

            weight: 6,

            opacity: 0.85,
        }).addTo(map);

        /*
         * OSRM queda delante de cualquier línea manual.
         */

        lineaOSRM.bringToFront();

        /*
         * ============================================================
         * 7. GUARDAR COORDENADAS GENERALES
         * ============================================================
         *
         * Estas son las coordenadas que representan el recorrido
         * completo de la ruta.
         */

        form.coordenadas = geometriaOSRM.value.map((punto) => ({
            lat: punto.lat,

            lng: punto.lng,
        }));

        /*
         * ============================================================
         * 8. CREAR SEGMENTO OSRM
         * ============================================================
         *
         * Aquí está la parte nueva e importante.
         *
         * La geometría se guarda en formato GeoJSON:
         *
         * {
         *     type: "LineString",
         *     coordinates: [
         *         [lng, lat],
         *         ...
         *     ]
         * }
         */

        segmentos.value = [
            {
                orden: 1,

                tipo: "osrm",

                geometria: {
                    type: "LineString",

                    coordinates: coordenadasOSRM,
                },
            },
        ];

        /*
         * ============================================================
         * 9. INDICAR QUE LA RUTA ES OSRM
         * ============================================================
         */

        form.tipo_ruta = "osrm";

        /*
         * ============================================================
         * 10. AJUSTAR MAPA
         * ============================================================
         */

        map.fitBounds(lineaOSRM.getBounds(), {
            padding: [40, 40],
        });

        /*
         * ============================================================
         * 11. DEBUG
         * ============================================================
         */

        console.log("PUNTOS MANUALES UTILIZADOS:", puntos.value);

        console.log("GEOMETRÍA OSRM:", geometriaOSRM.value);

        console.log("COORDENADAS GENERALES:", form.coordenadas);

        console.log("SEGMENTOS A GUARDAR:", segmentos.value);
    } catch (error) {
        console.error("Error OSRM:", error);

        console.error("Respuesta Laravel:", error.response?.data);

        alert("No se pudo calcular la ruta.");
    }
}

/*
|--------------------------------------------------------------------------
| AGREGAR PARADA
|--------------------------------------------------------------------------
*/

function agregarParada(lat, lng) {
    const nuevaParada = {
        nombre: "",

        ubicacion: "",

        latitud: lat,

        longitud: lng,

        orden: paradas.value.length + 1,
    };

    /*
     * Guardar parada.
     */
    paradas.value.push(nuevaParada);

    /*
     * Crear marcador.
     */
    const marcador = L.marker([lat, lng], {
        icon: crearIconoParada(),
    }).addTo(map);

    /*
     * Popup.
     */
    marcador.bindPopup(`
        <strong>📍 Nueva parada</strong><br>
        Latitud: ${lat.toFixed(6)}<br>
        Longitud: ${lng.toFixed(6)}
    `);

    /*
     * Guardar marcador.
     */
    marcadoresParadas.push(marcador);

    /*
     * Volver al modo ruta.
     */
    modoMapa.value = "ruta";
}

/*
|--------------------------------------------------------------------------
| ELIMINAR PARADA
|--------------------------------------------------------------------------
*/

function eliminarParada(index) {
    if (marcadoresParadas[index]) {
        map.removeLayer(marcadoresParadas[index]);
    }

    marcadoresParadas.splice(index, 1);

    paradas.value.splice(index, 1);

    /*
     * Actualizar orden.
     */
    paradas.value.forEach((parada, i) => {
        parada.orden = i + 1;
    });
}

/*
|--------------------------------------------------------------------------
| LIMPIAR TODO EL MAPA
|--------------------------------------------------------------------------
*/

function limpiarMapa() {
    /*
     * Marcadores de puntos.
     */
    marcadoresPuntos.forEach((marcador) => {
        if (map.hasLayer(marcador)) {
            map.removeLayer(marcador);
        }
    });

    marcadoresPuntos = [];

    /*
     * Marcadores de paradas.
     */
    marcadoresParadas.forEach((marcador) => {
        if (map.hasLayer(marcador)) {
            map.removeLayer(marcador);
        }
    });

    marcadoresParadas = [];

    /*
     * Línea manual.
     */
    if (lineaManual) {
        map.removeLayer(lineaManual);

        lineaManual = null;
    }

    /*
     * Línea OSRM.
     */
    if (lineaOSRM) {
        map.removeLayer(lineaOSRM);

        lineaOSRM = null;
    }

    /*
     * Limpiar datos.
     */
    puntos.value = [];

    paradas.value = [];

    geometriaOSRM.value = [];

    form.puntos = [];

    form.paradas = [];

    form.coordenadas = [];

    form.segmentos = [];

    /*
     * Volver a modo ruta.
     */
    modoMapa.value = "ruta";
}

/*
|--------------------------------------------------------------------------
| GUARDAR RUTA
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| CAMBIAR TIPO DE RUTA
|--------------------------------------------------------------------------
*/

function cambiarTipoRuta() {
    /*
     * Manual:
     *
     * Mostrar nuevamente la ruta manual.
     */
    if (form.tipo_ruta === "manual") {
        limpiarLineaOSRM();

        dibujarRutaManual();

        return;
    }

    /*
     * OSRM:
     *
     * No calculamos automáticamente.
     *
     * El usuario debe presionar
     * "Calcular OSRM".
     */
    if (form.tipo_ruta === "osrm") {
        /*
         * Si ya existe geometría OSRM,
         * la mostramos.
         */
        if (geometriaOSRM.value.length >= 2) {
            if (lineaOSRM) {
                map.removeLayer(lineaOSRM);
            }

            lineaOSRM = L.polyline(
                geometriaOSRM.value.map((punto) => [punto.lat, punto.lng]),
                {
                    color: "red",

                    weight: 6,

                    opacity: 0.85,
                },
            ).addTo(map);

            lineaOSRM.bringToFront();

            form.coordenadas = geometriaOSRM.value.map((punto) => ({
                lat: punto.lat,

                lng: punto.lng,
            }));
        }
    }
}

/*
|--------------------------------------------------------------------------
| LIMPIAR AL SALIR
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
                <h1 class="text-3xl font-bold">Nueva Ruta</h1>

                <p class="text-gray-500 mt-1">
                    Configure los datos de la ruta y posteriormente defina su
                    recorrido en el mapa.
                </p>
            </div>

            <!-- DATOS DE LA RUTA -->

            <div class="bg-white rounded-xl shadow-md border p-6 mb-6">
                <h2 class="text-xl font-bold mb-5">Datos de la Ruta</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- NOMBRE -->

                    <div>
                        <label class="block mb-2 font-semibold">
                            Nombre de la Ruta
                        </label>

                        <input
                            v-model="form.nombreruta"
                            type="text"
                            placeholder="Ej. Ruta Centro"
                            class="w-full border rounded-lg p-3"
                        />

                        <div
                            v-if="form.errors.nombreruta"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors.nombreruta }}
                        </div>
                    </div>

                    <!-- LÍNEA -->

                    <div>
                        <label class="block mb-2 font-semibold"> Línea </label>

                        <select
                            v-model="form.idlinea"
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

                        <div
                            v-if="form.errors.idlinea"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors.idlinea }}
                        </div>
                    </div>

                    <!-- ORIGEN -->

                    <div>
                        <label class="block mb-2 font-semibold"> Origen </label>

                        <input
                            v-model="form.origen"
                            type="text"
                            placeholder="Ej. Terminal"
                            class="w-full border rounded-lg p-3"
                        />

                        <div
                            v-if="form.errors.origen"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors.origen }}
                        </div>
                    </div>

                    <!-- DESTINO -->

                    <div>
                        <label class="block mb-2 font-semibold">
                            Destino
                        </label>

                        <input
                            v-model="form.destino"
                            type="text"
                            placeholder="Ej. Mercado Central"
                            class="w-full border rounded-lg p-3"
                        />

                        <div
                            v-if="form.errors.destino"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors.destino }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- TIPO DE RUTA -->

            <div class="bg-white rounded-xl shadow-md border p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Tipo de Ruta</h2>

                <select
                    v-model="form.tipo_ruta"
                    class="border rounded-lg p-3 w-full md:w-1/2"
                >
                    <option value="osrm">OSRM</option>
                    <option value="manual">Manual</option>
                </select>
            </div>

            <div class="flex gap-3 mb-4">
                <button
                    v-if="form.tipo_ruta === 'osrm'"
                    @click="calcularRutaOSRM"
                    type="button"
                    class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg"
                >
                    🛣️ Calcular OSRM
                </button>

                <button
                    v-if="form.tipo_ruta === 'manual'"
                    @click="dibujarRutaManual"
                    type="button"
                    class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg"
                >
                    ✏️ Dibujar Ruta Manual
                </button>

                <button
                    @click="modoMapa = 'parada'"
                    type="button"
                    class="px-5 py-2 bg-yellow-500 text-white rounded-lg"
                >
                    📍 Agregar parada
                </button>

                <button
                    @click="limpiarMapa"
                    type="button"
                    class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg"
                >
                    🗑️ Limpiar
                </button>
            </div>

            <div class="mb-4">
                <span
                    v-if="modoMapa === 'ruta'"
                    class="inline-block bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm"
                >
                    🛣️ Modo: Dibujar recorrido
                </span>

                <span
                    v-if="modoMapa === 'parada'"
                    class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm"
                >
                    📍 Modo: Agregar parada
                </span>

                <button
                    v-if="modoMapa === 'parada'"
                    @click="modoMapa = 'ruta'"
                    type="button"
                    class="px-5 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"
                >
                    🛣️ Volver a dibujar ruta
                </button>
            </div>

            <!-- MAPA -->

            <div class="bg-white rounded-xl shadow-md border p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h2 class="text-xl font-bold">🗺️ Mapa</h2>

                        <p class="text-gray-500">
                            Modo actual:
                            <strong>
                                {{
                                    form.tipo_ruta === "osrm"
                                        ? "OSRM"
                                        : "Manual"
                                }}
                            </strong>
                        </p>
                    </div>
                </div>

                <div
                    id="map"
                    class="rounded-lg border"
                    style="height: 600px"
                ></div>
            </div>

            <div class="mt-8">
                <h2 class="text-xl font-bold mb-4">📍 Paradas de la Ruta</h2>

                <div v-if="paradas.length === 0" class="text-gray-500">
                    Todavía no existen paradas.
                </div>

                <div
                    v-for="(parada, index) in paradas"
                    :key="index"
                    class="bg-white border rounded-lg p-4 mb-3"
                >
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="font-semibold"> Nombre </label>

                            <input
                                v-model="parada.nombre"
                                type="text"
                                placeholder="Ej. Plaza Principal"
                                class="w-full border rounded-lg p-2"
                            />
                        </div>

                        <div>
                            <label class="font-semibold"> Ubicación </label>

                            <input
                                v-model="parada.ubicacion"
                                type="text"
                                placeholder="Ej. Av. Principal"
                                class="w-full border rounded-lg p-2"
                            />
                        </div>
                    </div>

                    <div class="mt-3 text-sm text-gray-600">
                        Latitud:
                        {{ parada.latitud.toFixed(6) }}

                        <br />

                        Longitud:
                        {{ parada.longitud.toFixed(6) }}
                    </div>

                    <div class="mt-3">
                        <button
                            @click="eliminarParada(index)"
                            type="button"
                            class="bg-red-500 text-white px-3 py-1 rounded"
                        >
                            🗑️ Eliminar
                        </button>
                    </div>
                </div>
            </div>

            <!-- BOTONES -->

            <div class="flex gap-3">
                <Link
                    :href="route('rutas.index')"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg"
                >
                    Cancelar
                </Link>

                <button
                    @click="guardarRuta"
                    :disabled="form.processing"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg disabled:opacity-50"
                >
                    {{ form.processing ? "Guardando..." : "💾 Guardar Ruta" }}
                </button>
            </div>

            <!-- INDICACIONES DEL MAPA  -->
            <div class="mt-6">
                <h2 class="text-xl font-bold mb-4">Puntos de la ruta</h2>

                <div v-if="puntos.length === 0" class="text-gray-500 italic">
                    Haz clic sobre el mapa para agregar puntos.
                </div>

                <div
                    v-for="(punto, index) in puntos"
                    :key="index"
                    class="bg-gray-50 border rounded-lg p-3 mb-2"
                >
                    <strong>Punto {{ index + 1 }}</strong>

                    <div class="text-sm text-gray-600">
                        Latitud:
                        {{ punto.lat.toFixed(6) }}

                        <br />

                        Longitud:
                        {{ punto.lng.toFixed(6) }}
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
