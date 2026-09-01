<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    personas: Array,
});

const form = useForm({
    ci: "",

    correo: "",

    contrasena: "",

    contrasena_confirmation: "",
});

function guardar() {
    form.post(route("usuarios.store"));
}
</script>
<template>
    <Head title="Nuevo Usuario" />

    <AdminLayout>
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">Nuevo Usuario</h1>

            <form
                @submit.prevent="guardar"
                class="bg-white shadow rounded-lg p-6 space-y-5"
            >
                <div>
                    <label class="block mb-2"> Persona </label>

                    <select v-model="form.ci" class="border rounded w-full p-2">
                        <p
                            v-if="form.errors.ci"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors.ci }}
                        </p>
                        <option value="">Seleccione una persona</option>

                        <option
                            v-for="persona in personas"
                            :key="persona.ci"
                            :value="persona.ci"
                        >
                            {{ persona.ci }} - {{ persona.nombre }}
                            {{ persona.apellido }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2"> Correo </label>

                    <input
                        type="email"
                        v-model="form.correo"
                        class="border rounded w-full p-2"
                    />
                    <p
                        v-if="form.errors.correo"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.correo }}
                    </p>
                </div>

                <div>
                    <label class="block mb-2"> Contraseña </label>

                    <input
                        type="password"
                        v-model="form.contrasena"
                        class="border rounded w-full p-2"
                    />
                    <p
                        v-if="form.errors.contrasena"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.contrasena }}
                    </p>
                </div>

                <div>
                    <label class="block mb-2"> Confirmar contraseña </label>

                    <input
                        type="password"
                        v-model="form.contrasena_confirmation"
                        class="border rounded w-full p-2"
                    />
                    <p
                        v-if="form.errors.contrasena"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.contrasena }}
                    </p>
                </div>

                <div class="flex justify-end gap-3">
                    <Link
                        :href="route('usuarios.index')"
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
