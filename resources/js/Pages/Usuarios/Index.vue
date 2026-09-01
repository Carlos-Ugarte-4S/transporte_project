<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    usuarios: Array,
});
</script>

<template>
    <Head title="Usuarios" />

    <AdminLayout>
        <div class="flex justify-between mb-6">
            <h1 class="text-3xl font-bold">Usuarios</h1>

            <Link
                href="/usuarios/create"
                class="bg-blue-600 text-white px-4 py-2 rounded"
            >
                + Nuevo Usuario
            </Link>
        </div>

        <div class="bg-white rounded-lg shadow p-5">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-3">CI</th>

                        <th class="text-left p-3">Persona</th>

                        <th class="text-left p-3">Correo</th>

                        <th class="text-left p-3">Estado</th>

                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="usuario in usuarios"
                        :key="usuario.ci"
                        class="border-b"
                    >
                        <td class="p-3">
                            {{ usuario.ci }}
                        </td>

                        <td class="p-3">
                            {{ usuario.persona.nombre }}

                            {{ usuario.persona.apellido }}
                        </td>

                        <td class="p-3">
                            {{ usuario.correo }}
                        </td>

                        <td class="p-3">
                            <span
                                v-if="usuario.estado"
                                class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs"
                            >
                                🟢 Activo
                            </span>

                            <span
                                v-else
                                class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs"
                            >
                                🔴 Inactivo
                            </span>
                        </td>

                        <td class="p-3 flex gap-2">
                            <Link
                                :href="route('usuarios.edit', usuario.ci)"
                                class="bg-blue-600 text-white px-3 py-1 rounded"
                            >
                                ✏ Editar
                            </Link>

                            <Link
                                :href="route('usuarios.password', usuario.ci)"
                                class="bg-yellow-500 text-white px-3 py-1 rounded"
                            >
                                🔑 Contraseña
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
