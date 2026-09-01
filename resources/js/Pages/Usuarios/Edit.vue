<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    usuario: Object,
});

const form = useForm({
    correo: props.usuario.correo,

    estado: props.usuario.estado,
});

function guardar() {
    form.put(route("usuarios.update", props.usuario.ci));
}

function cambiarEstado() {
    if (
        confirm(
            form.estado
                ? "¿Desea desactivar este usuario?"
                : "¿Desea activar este usuario?",
        )
    ) {
        form.estado = !form.estado;
    }
}
</script>

<template>
    <Head title="Editar Usuario" />

    <AdminLayout>
        <div class="max-w-xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">Editar Usuario</h1>

            <form
                @submit.prevent="guardar"
                class="bg-white shadow rounded-lg p-6 space-y-5"
            >
                <div>
                    <label class="font-semibold"> Persona </label>

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

                <div>
                    <label> Correo </label>

                    <input
                        v-model="form.correo"
                        type="email"
                        class="border rounded w-full p-2"
                    />

                    <p v-if="form.errors.correo" class="text-red-600 text-sm">
                        {{ form.errors.correo }}
                    </p>
                </div>

                <div>
                    <label> Estado </label>

                    <div class="flex items-center gap-5 mt-2">
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
                            Cambiar Estado
                        </button>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <Link
                        :href="route('usuarios.index')"
                        class="bg-gray-500 text-white px-4 py-2 rounded"
                    >
                        Cancelar
                    </Link>

                    <button class="bg-blue-600 text-white px-5 py-2 rounded">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
