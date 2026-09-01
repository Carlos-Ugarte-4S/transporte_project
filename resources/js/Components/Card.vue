<script setup>
defineProps({
    titulo: String,

    cantidad: Number,

    icono: String,
});
</script>

<template>
    <div class="bg-white shadow rounded-lg p-5">
        <div class="flex justify-between">
            <div>
                <h2 class="text-gray-500 text-sm">
                    {{ titulo }}
                </h2>

                <p class="text-3xl font-bold mt-2">
                    {{ cantidad }}
                </p>
            </div>

            <div class="text-3xl">
                {{ icono }}
            </div>
        </div>
    </div>
</template>
