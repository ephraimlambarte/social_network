<script setup>
import InputSection from './components/InputSection.vue';
import MessageDisplay from './components/MessageDisplay.vue';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});
</script>
<template>
    <div class="border border-slate-400 m-container">
        <MessageDisplay
            :user="user"/>
        <InputSection
            :user="user"
            class="input-section"/>
    </div>
</template>
<style scoped>
.m-container {
    background-color: white;
    padding-bottom: 100px;
    position: relative;
    display: flex;
    flex-direction: column;
}
.input-section {
    position: absolute;
    bottom: 0px;
    right: 0px;
    left: 0px;
}
</style>