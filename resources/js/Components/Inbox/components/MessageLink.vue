<script setup>
import DropdownLink from '@/Components/DropdownLink.vue';
import { usePage } from '@inertiajs/vue3';
const authenticatedUser = usePage().props.auth.user;

defineProps({
    message: {
        type: Object,
        required: true,
    },
});
</script>
<template>
    <DropdownLink
        :href="route('message.friend', {user: message.sender.id})"
        method="get"
        as="button"
        :class="(message.read) || (!message.read && message.sender.id === authenticatedUser.id) ? 'read' : 'unread'">
        <h3>{{ message.sender.name }}</h3>
        <div class="text-ellipsis">
            <p>{{ message.message }}</p>
        </div>
    </DropdownLink>
</template>
<style scoped>
.read {
    background-color: #FFFFFF;
}
 
.unread {
    background-color: #EEEEEE;
}   
    
</style>