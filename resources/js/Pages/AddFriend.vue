<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import Button from '@/Components/Button.vue';
import Spinner from '@/Components/Spinner.vue';
import _ from 'lodash';
import { snackbarStore } from '@/Stores/snackbars';
const sbStore = snackbarStore();

const searchInput = ref('');
const people = ref([]);
const searchingForPeople = ref(false);

const searchPeople = _.debounce(() => {
    searchingForPeople.value = true;
    return window.axios.get('/search-people', {
        params: {
            search_input: searchInput.value,
        },
    })
    .then((res) => {
        people.value = res.data;
    })
    .finally(() => {
        searchingForPeople.value = false;
    });
}, 1000);
const submitSearch = (e) => {
    e.preventDefault();
    searchPeople();
};

const getButtonDisplay = (person) => {
    if (person.sent_friend_request) {
        return "Friend Request Received";
    }
    if (person.received_friend_request) {
        return "Friend Request Sent";
    }
    return 'Add Friend';
};

const addFriend = (person, index) => {
    people.value[index].disabled = true;

    window.axios.post('/add-friend/' + person.id)
    .then(() => {
        people.value[index].sent_friend_request = true;
        sbStore.snackbars.push({
            title: 'Friend request sent!',
            icon: 'success',
        });
    })
    .finally(() => {
        people.value[index].disabled = false;
    });
}

</script>

<template>
    <Head title="Add A Friend" />
    <AuthenticatedLayout main-class="flex items-center justify-center flex-col p-20 min-h-0">
        <template #header>
            <form @submit="submitSearch">
                <div class="form-container">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Search for people</h2>

                    <input type="text" v-model="searchInput" @input="searchPeople" placeholder="Enter name or email" class="search-input"/>
                </div>
            </form>    
        </template>
        <Spinner v-show="searchingForPeople"/>
        <div v-show="!searchingForPeople" class="people-container">
            <ul>
                <li
                    class="mx-6 mb-3 p-3 hover:bg-sky-100"
                    v-for="(person, index) in people">
                    <div class="flex w-full">
                        <div>
                            {{ person.name }}
                        </div>
                        <div class="ml-auto">
                            <Button
                                color="yellow"
                                :disabled="person.sent_friend_request || person.received_friend_request || person.disabled"
                                @click="addFriend(person, index)">
                                
                                {{ getButtonDisplay(person) }}
                            </Button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <!--  -->
    </AuthenticatedLayout>
</template>
<style scoped>
.form-container {
    display: flex;
    gap: 20px;
    align-items: center;
}
.search-input {
    flex: 1;
    min-width: 0;
}
.people-container {
    flex: 1;
    min-height: 0;
    overflow: auto;
    width: 70%;
    background-color: white;
    border-radius: 20px;
}
</style>
