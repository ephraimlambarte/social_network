<script setup>
import Button from '@/Components/Button.vue';
import { ref } from 'vue';
const emit = defineEmits(['message-send']);

const message = ref('');

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});
const sendMessage = () => {
    return window.axios.post('/send-message/'+props.user.id, {
        message: message.value,
    })
    .then(res => {
        message.value = '';
    });
};
const submitMessage = (e) => {
    e.preventDefault();
    sendMessage();
}
const handleEnterInput = (e) => {
    if (e.keyCode == 13) {          
        if(!e.shiftKey && message.value){
            sendMessage();
        }
    }
}
</script>
<template>
    <form @submit="submitMessage">
        <div class="input-section-container">
            <Button
                color="primary"
                icon="send"
                class="send-button"
                type="submit"
                iconHeight="30px"
                iconWidth="30px">
            </Button>
            <textarea type="text" v-model="message" required @keyup.enter="handleEnterInput" class="message-text-input"/>
        </div>
    </form>
</template>
<style scoped>
.input-section-container {
    display: flex;
    align-items: center;
    padding: 10px;
    height: 100px;
}
.send-button {
    height: 50px;
    width: 50px;
}
.message-text-input {
    flex-grow: 1;
    resize: none;
}
</style>