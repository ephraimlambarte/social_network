<script setup>
import { ref, onMounted, computed } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import Icon from '@/Components/Icon.vue';
import Badge from '@/Components/Badge.vue';
import MessageLink from './components/MessageLink.vue';
import { usePage } from '@inertiajs/vue3';
const authenticatedUser = usePage().props.auth.user;

const messages = ref([]);
onMounted(() => {
    window.axios.get('/my-inbox')
    .then((res) => {
        messages.value = res.data;
    });
});

window.Echo.private(`read-messages.${authenticatedUser.id}`)
    .listen('ReadMessageEvent', (e) => {
        for(let i in messages.value) {
            if (e.messages.find(m => m.id === messages.value[i].id)) {
                messages.value[i].read = true;
            }
        }
    });
window.Echo.private(`my-inbox.${authenticatedUser.id}`)
    .listen('MessageSentEvent', (e) => {
        const index = messages.value.findIndex(m => m.user_sender_id === e.message.user_sender_id || m.user_receiver_id === e.message.user_receiver_id);
        if (index >= 0) {
            messages.value[index] = e.message;
        } else {
            messages.value.push(e.message);
        }
    });

const unreadMessageCount = computed(() => {
    return messages.value.filter(m => !m.read && m.user_sender_id !== authenticatedUser.id).length;
});
</script>
<template>
    <Dropdown align="right" width="48">
        <template #trigger>
            <div class="relative">
                <Icon
                    icon="mail"
                    height="25px"
                    width="25px"/>
                <Badge class="inbox-badge" v-show="unreadMessageCount > 0">{{ unreadMessageCount }}</Badge>
            </div>
        </template>

        <template #content>
            <MessageLink
                v-for="(message, index) in messages"
                :message="message"/>
        </template>
    </Dropdown>
</template>
<style scoped>
.inbox-badge {
    position: absolute;
    bottom: -10px;
    right: -10px;
}
</style>