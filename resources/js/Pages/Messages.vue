<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Icon from '@/Components/Icon.vue';
import Messenger from '@/Components/Messenger/Messenger.vue';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

</script>

<template>
    <Head title="Messages" />

    <AuthenticatedLayout
        main-class="flex items-center justify-center flex-col p-20 min-h-0">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ user.name }}</h2>
        </template>
        <Messenger class="message-container" :user="user"/>
    </AuthenticatedLayout>
</template>
<style scoped>
.message-container {
    width: 70%;
    flex-grow: 1;
    min-height: 0;
}
</style>
