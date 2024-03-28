<template>
    <div class="ui-snackbar" @click="show=false" v-if="show" transition="ui-snackbar-toggle">
        <div class="ui-snackbar-text">{{snackbar.title}}</div>
        <div class="ui-snackbar-action">
            <Icon :icon="snackbar.icon" :class="snackbar.icon"/>
        </div>
    </div>
</template>
    
<script setup>
import { ref, onMounted} from 'vue';
import Icon from '@/Components/Icon.vue';

let show = ref(true);

onMounted(() => setTimeout(() => {
    show.value = false;
}, 4000));

const props = defineProps({
    snackbar: {
        type:Object,
        default: () => ({
            title: 'This is a snackbar',
            icon: 'warning',
        })
    },
});
</script>
    
<style lang="scss" scoped>
.ui-snackbar {
    display: inline-flex;
    align-items: center;
    
    min-width: 288px;
    max-width: 568px;
    min-height: 48px;
    
    padding: 10px;
    margin: 4px 4px 8px 4px;
    
    border-radius: 2px;
    background-color: #323232;
    
    box-shadow: 0 1px 3px #142c36, 0 1px 2px #07315380;
}

.ui-snackbar-text {
    font-size: 14px;
    color: white;
}

.ui-snackbar-action {
    margin-left: auto;
    padding-left: 20px;
    i {
        border: none;
        background: none;
        margin: 0;
        padding: 0;

        font-size: 20px;
        text-transform: uppercase;
    }
}

.error {
    color: #fa0f0f;
}

.warning {
    color: #faea0f;
}

.success {
    color: #0ffa0f;
}

.ui-snackbar-toggle-transition {
    transition: transform 0.3s ease;
    
    .ui-snackbar-text,
    .ui-snackbar-action {
        opacity: 1;
        transition: opacity 0.3s ease;
    }
}

.ui-snackbar-toggle-enter,
.ui-snackbar-toggle-leave {
    transform: translateY(60px);
    
    .ui-snackbar-text,
    .ui-snackbar-action {
        opacity: 0;
    }
}
</style>