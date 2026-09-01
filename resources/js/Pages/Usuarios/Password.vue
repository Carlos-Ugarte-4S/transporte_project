<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    usuario: Object,
});

const form = useForm({
    contrasena: "",

    contrasena_confirmation: "",
});

function guardar() {
    form.put(route("usuarios.updatePassword", props.usuario.ci));
}
</script>

<template>
    <Head title="Cambiar contraseña" />

    <AdminLayout>
        <div class="max-w-xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">Cambiar contraseña</h1>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="mb-5">
                    <label class="font-semibold"> Usuario </label>

                    <input
                        :value="
                            usuario.persona.nombre +
                            ' ' +
                            usuario.persona.apellido
                        "
                        disabled
                        class="border rounded w-full p-2 bg-gray-100"
                    />
                </div>

                <form @submit.prevent="guardar" class="space-y-5">
                    <div>
                        <label> Nueva contraseña </label>

                        <input
                            type="password"
                            v-model="form.contrasena"
                            class="border rounded w-full p-2"
                        />

                        <p
                            v-if="form.errors.contrasena"
                            class="text-red-600 text-sm"
                        >
                            {{ form.errors.contrasena }}
                        </p>
                    </div>

                    <div>
                        <label> Confirmar contraseña </label>

                        <input
                            type="password"
                            v-model="form.contrasena_confirmation"
                            class="border rounded w-full p-2"
                        />
                    </div>

                    <div class="flex justify-end gap-3">
                        <Link
                            :href="route('usuarios.index')"
                            class="bg-gray-500 text-white px-4 py-2 rounded"
                        >
                            Cancelar
                        </Link>

                        <button
                            class="bg-blue-600 text-white px-5 py-2 rounded"
                        >
                            Guardar contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
