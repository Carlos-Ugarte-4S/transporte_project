<script setup>
import { useForm, Link } from "@inertiajs/vue3";

const form = useForm({
    ci: "",
    nombre: "",
    apellido: "",
    telefono: "",
    sexo: "",
    licencia: "",

    tipoa: false,
    tipoc: false,
    tipop: false,

    estado: true,
});

function guardar() {
    form.post(route("personas.store"));
}
</script>

<template>
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Nueva Persona</h1>

            <Link
                :href="route('personas.index')"
                class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700"
            >
                Volver
            </Link>
        </div>

        <form
            @submit.prevent="guardar"
            class="bg-white shadow rounded-lg p-6 space-y-5"
        >
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="font-semibold">CI</label>

                    <input
                        v-model="form.ci"
                        type="text"
                        class="border rounded w-full p-2"
                    />
                </div>

                <div>
                    <label class="font-semibold">Teléfono</label>

                    <input
                        v-model="form.telefono"
                        type="text"
                        class="border rounded w-full p-2"
                    />
                </div>

                <div>
                    <label class="font-semibold">Nombre</label>

                    <input
                        v-model="form.nombre"
                        type="text"
                        class="border rounded w-full p-2"
                    />
                </div>

                <div>
                    <label class="font-semibold">Apellido</label>

                    <input
                        v-model="form.apellido"
                        type="text"
                        class="border rounded w-full p-2"
                    />
                </div>

                <div>
                    <label class="font-semibold">Sexo</label>

                    <select
                        v-model="form.sexo"
                        class="border rounded w-full p-2"
                    >
                        <option value="">Seleccione...</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
                </div>

                <div>
                    <label class="font-semibold">Licencia</label>

                    <input
                        v-model="form.licencia"
                        type="text"
                        class="border rounded w-full p-2"
                    />
                </div>
            </div>

            <hr />

            <h2 class="font-bold text-lg">Tipo de Persona</h2>

            <div class="grid grid-cols-3 gap-4">
                <label class="flex items-center gap-2">
                    <input type="checkbox" v-model="form.tipoa" />
                    Administrador
                </label>

                <label class="flex items-center gap-2">
                    <input type="checkbox" v-model="form.tipoc" />
                    Chofer
                </label>

                <label class="flex items-center gap-2">
                    <input type="checkbox" v-model="form.tipop" />
                    Propietario
                </label>
            </div>

            <div class="pt-5">
                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded"
                    :disabled="form.processing"
                >
                    Guardar Persona
                </button>
            </div>
        </form>
    </div>
</template>
