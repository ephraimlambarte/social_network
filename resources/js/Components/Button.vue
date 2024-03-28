<script setup>
import { computed } from 'vue';
import Icon from '@/Components/Icon.vue';

const emit = defineEmits(['click']);

const props = defineProps({
    color: String,
    type: {
        type: String,
        default: 'button',
    },
    btnClass: {
        type: String,
        default: '',
    },
    icon: String,
    iconHeight: String,
    iconWidth: String,
    disabled: Boolean,
});

const emitClick = (e) => {emit('click', e)};

const btnClass = computed(() => {
    let colorClass = '';
    switch (props.color) {
        case 'primary':
            if (props.icon) {
                colorClass = "text-blue-700";
            } else {
                colorClass = 'focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900';
            }
            break;
        case 'red':
            if (props.icon) {
                colorClass = "text-red-700";
            } else {
                colorClass = 'focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900';
            }
            break;
        case 'green':
            if (props.icon) {
                colorClass = "text-green-700";
            } else {
                colorClass = 'focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800';
            }
            break;
        case 'yellow':
            if (props.icon) {
                colorClass = "text-yellow-700";
            } else {
                colorClass = "focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900";    
            }
            break;
    }
    return colorClass + props.btnClass;
});
</script>
<template>
<button
    :class="btnClass"
    :type="type"
    @click.stop="emitClick"
    :disabled="disabled">
    
    <Icon v-if="icon" :icon="icon" :height="iconHeight" :width="iconWidth"/>
    <slot></slot>
</button>
</template>