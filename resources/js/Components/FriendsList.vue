<script setup>
import DeleteButton from '@/Components/DeleteButton.vue';
import Button from '@/Components/Button.vue';
import Icon from '@/Components/Icon.vue';
import { snackbarStore } from '@/Stores/snackbars';

const sbStore = snackbarStore();
const emit = defineEmits(['friend-removed']);

defineProps({
    friends: {
        type: Array,
    },
});

const removeFriend = (friend, index) => {
    console.log(friend);
    window.axios.delete('/remove-friend/'+friend.id)
    .then(res => {
        emit('friend-removed', friend, index);
        sbStore.snackbars.push({
            title: 'Friend removed!',
            icon: 'success',
        });
    });
};
const sendMessageToFriend = (friend) => {
    console.log(friend);
}
</script>

<template>
    <div>
        <ul>
            <li
                class="mx-6 mb-3 p-3 hover:bg-sky-100"
                v-for="(friend, index) in friends">
                <div class="flex w-full">
                    <div>
                        {{ friend.name }}
                    </div>
                    <div class="ml-auto">
                        <DeleteButton
                            @confirm-delete="removeFriend(friend, index)"/>
                        <Button
                            color="yellow"
                            @click="sendMessageToFriend(friend)">
                            <Icon icon="chat-bubble"/>
                        </Button>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>