<script setup>
import { Link } from "@inertiajs/vue3";
import { inject, ref } from "vue";

const sidebarOpen = inject("sidebarOpen");

const personas = ref(false);
const organizacion = ref(false);
const transporte = ref(false);
const rutas = ref(false);
const lineas = ref(false);
</script>

<template>
    <aside
        v-if="sidebarOpen"
        class="w-64 bg-gray-900 text-white min-h-screen transition-all duration-300"
    >
        <!-- TITULO -->

        <div class="p-5 text-xl font-bold border-b border-gray-700">
            🚍 Transporte
        </div>

        <nav class="mt-5">
            <!-- DASHBOARD -->

            <Link href="/dashboard" class="block px-5 py-3 hover:bg-gray-700">
                🏠 Dashboard
            </Link>

            <!-- PERSONAS -->

            <button
                @click="personas = !personas"
                class="w-full text-left px-5 py-3 hover:bg-gray-700"
            >
                👥 Personas

                <span class="float-right">
                    {{ personas ? "▲" : "▼" }}
                </span>
            </button>

            <div v-if="personas" class="bg-gray-800">
                <Link
                    href="/personas"
                    class="block px-10 py-2 hover:bg-gray-700"
                >
                    Personas
                </Link>

                <Link
                    :href="route('usuarios.index')"
                    class="block px-10 py-2 hover:bg-gray-700"
                >
                    Usuarios
                </Link>
            </div>

            <!-- ORGANIZACION -->

            <button
                @click="organizacion = !organizacion"
                class="w-full text-left px-5 py-3 hover:bg-gray-700"
            >
                🏢 Organización

                <span class="float-right">
                    {{ organizacion ? "▲" : "▼" }}
                </span>
            </button>

            <div v-if="organizacion" class="bg-gray-800">
                <Link
                    :href="route('sindicatos.index')"
                    class="block px-10 py-2 hover:bg-gray-700"
                >
                    Sindicatos
                </Link>

                <Link
                    :href="route('lineas.index')"
                    class="block px-10 py-2 hover:bg-gray-700"
                >
                    Lineas
                </Link>
            </div>

            <!-- TRANSPORTE -->

            <button
                @click="transporte = !transporte"
                class="w-full text-left px-5 py-3 hover:bg-gray-700"
            >
                🚌 Transporte

                <span class="float-right">
                    {{ transporte ? "▲" : "▼" }}
                </span>
            </button>

            <div v-if="transporte" class="bg-gray-800">
                <Link
                    :href="route('vehiculo_horarios.index')"
                    class="block px-10 py-2 hover:bg-gray-700"
                >
                    Horarios de Vehículos
                </Link>
                <Link
                    :href="route('vehiculos.index')"
                    class="block px-10 py-2 hover:bg-gray-700"
                >
                    Vehículos
                </Link>

                <Link
                    :href="route('tipo_transportes.index')"
                    class="block px-10 py-2 hover:bg-gray-700"
                >
                    Tipos Transporte
                </Link>
            </div>

            <!-- RUTAS -->

            <button
                @click="rutas = !rutas"
                class="w-full text-left px-5 py-3 hover:bg-gray-700"
            >
                🗺️ Rutas

                <span class="float-right">
                    {{ rutas ? "▲" : "▼" }}
                </span>
            </button>

            <div v-if="rutas" class="bg-gray-800">
                <Link
                    :href="route('rutas.index')"
                    class="block px-10 py-2 hover:bg-gray-700"
                >
                    Rutas
                </Link>

                <Link href="#" class="block px-10 py-2 hover:bg-gray-700">
                    Paradas
                </Link>
            </div>

            <!-- REPORTES -->

            <Link href="#" class="block px-5 py-3 hover:bg-gray-700">
                📊 Reportes
            </Link>
        </nav>
    </aside>
</template>
