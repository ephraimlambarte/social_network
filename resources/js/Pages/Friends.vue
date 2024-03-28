<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FriendsList from '@/Components/FriendsList.vue';
import { Head } from '@inertiajs/vue3';
import { ref, reactive, onMounted } from 'vue';

const friends = ref([]);

onMounted(() => {
    window.axios.get('/my-friends')
    .then(res => {
        friends.value = res.data;
    });
});
const removeFriend = (friend, index) => {
    friends.value.splice(index, 1);
};
</script>



<template>
    <Head title="Friends" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Friends</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">My Friends</div>
                    <FriendsList
                        :friends="friends"
                        @friend-removed="removeFriend"/>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
