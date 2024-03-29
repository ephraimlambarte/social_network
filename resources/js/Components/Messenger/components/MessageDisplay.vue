<script setup>
import { onMounted, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

const authenticatedUser = usePage().props.auth.user;
const messages = ref([]);
const page = ref(1);

let pagePrevValue = 0;
let lastPage = 0;

const message_container = ref(null);

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const getMessages = () => {
    if (page.value > pagePrevValue) {
        pagePrevValue = page.value;
        return window.axios.get('/messages/'+props.user.id, {
            params: {
                page: page.value,
            },
        })
        .then((res) => {
            const newMessages = res.data.data;
            lastPage = res.data.meta.last_page;
            messages.value = messages.value.concat(newMessages);
        });
    }
    return (new Promise).resolve();
}
const loadNextPage = (event) => {
    let scrollTop = event.target.scrollTop;
    

    if (scrollTop === 0 && lastPage != 0 && !(page.value + 1 > lastPage)) {
        page.value++;
        
        getMessages()
         .then(() => {
            setTimeout(() => {
                message_container.value.scrollTop = 100
            }, 100);
        });
    }
};

onMounted(() => {
    getMessages()
    .then(() => {
        setTimeout(() => {
            message_container.value.scrollTop = message_container.value.scrollHeight
        }, 100);

        let unreadMessages = messages.value.filter(m => !m.read && m.user_sender_id !== authenticatedUser.id);
        if (unreadMessages.length > 0) {
            window.axios.post('/read-messages', {
                messages: unreadMessages.map(m => m.id),
            });
        }
    });
});

window.Echo.private(`messages.${props.user.id}`)
    .listen('MessageSentEvent', (e) => {
        messages.value.unshift(e.message);
        window.axios.post('/read-messages', {
            messages: [e.message.id],
        });
        setTimeout(() => {
            message_container.value.scrollTop = message_container.value.scrollHeight
        }, 100);
    });
</script>
<template>
    <div
        class="messages-container"
        ref="message_container"
        @scroll.passive="loadNextPage">
        <div
            v-for="(message, index) in messages.slice().reverse()"
            class="bubble"
            :index="'message-'+index"
            :class="{
                'align-left': message.user_sender_id === authenticatedUser.id,
                'blue-bubble': message.user_sender_id === authenticatedUser.id,
                'gray-bubble': !(message.user_sender_id === authenticatedUser.id),
                'align-right': !(message.user_sender_id === authenticatedUser.id),
            }">
            {{ message.message }}
        </div>
    </div>
</template>
<style scoped>
.messages-container {
    display: flex;
    height: 100%;
    min-height: 0;
    flex-grow: 1;
    overflow: auto;
    flex-direction: column;
    padding: 10px;
    gap: 10px;
}
.bubble {
    padding: 10px;
    border-radius: 10px;
}
.gray-bubble {
    background-color: #002D62;
    color: white;
}
.blue-bubble {
    background-color: #7CB9E8;
}
.align-left {
    margin-right: auto;
}
.align-right {
    margin-left: auto;
}
</style>

