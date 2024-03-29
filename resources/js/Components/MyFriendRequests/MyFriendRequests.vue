<script setup>
import { ref, onMounted, computed } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import Icon from '@/Components/Icon.vue';
import Badge from '@/Components/Badge.vue';
import Button from '@/Components/Button.vue';
import { snackbarStore } from '@/Stores/snackbars';
import { usePage } from '@inertiajs/vue3';

const authenticatedUser = usePage().props.auth.user;
const sbStore = snackbarStore();

const friendRequests = ref([]);

const getMyFriendRequests = () => {
    return window.axios.get('/my-friend-requests')
    .then((res) => {
        friendRequests.value = res.data;
    })
};
const friendRequestsCount = computed(() => friendRequests.value.length);

const acceptFriendRequest = (friendRequest, index) => {
    friendRequests.value[index].disabled = true;
    return window.axios.post('/accept-friend-request/'+friendRequest.id)
    .then(() => {
        friendRequests.value.splice(index, 1);
        sbStore.snackbars.push({
            title: 'Friend Accepted!',
            icon: 'success',
        });
    });
}
const ignoreFriendRequest = (friendRequest, index) => {
    friendRequests.value[index].disabled = true;
    return window.axios.post('/ignore-friend-request/'+friendRequest.id)
    .then(() => {
        friendRequests.value.splice(index, 1);
        sbStore.snackbars.push({
            title: 'Friend Ignored!',
            icon: 'success',
        });
    });
}

window.Echo.private(`friend-request-received.${authenticatedUser.id}`)
    .listen('FriendRequestSentEvent', (e) => {
        friendRequests.value.unshift(e.friendRequest);
    });

onMounted(() => {
    getMyFriendRequests();
});
</script>
<template>
    <Dropdown align="right" width="100">
        <template #trigger>
            <div class="relative">
                <Icon
                    icon="user-plus"
                    height="25px"
                    width="25px"/>
                <Badge class="inbox-badge" v-show="friendRequestsCount > 0">{{ friendRequestsCount }}</Badge>
            </div>
        </template>

        <template #content>
            <h3 v-show="friendRequestsCount === 0">
                No Friend Requests.
            </h3>
            <ul>
                <li
                    class="mx-6 mb-1 p-1 hover:bg-sky-100"
                    v-for="(friendRequest, index) in friendRequests">
                    <div class="flex w-full items-center">
                        <div class="text-ellipsis">
                            {{ friendRequest.sender.name }}
                        </div>
                        <div class="ml-auto">
                            <Button
                                color="yellow"
                                :disabled="friendRequest.disabled"
                                @click="acceptFriendRequest(friendRequest, index)">
                                Accept
                            </Button>
                            <Button
                                color="red"
                                :disabled="friendRequest.disabled"
                                @click="ignoreFriendRequest(friendRequest, index)">
                                Ignore
                            </Button>
                        </div>
                    </div>
                </li>
            </ul>
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