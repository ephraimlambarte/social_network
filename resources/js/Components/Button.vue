<script setup>
import { computed } from 'vue';
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
    disabled: Boolean,
});

const emitClick = (e) => {emit('click', e)};

const btnClass = computed(() => {
    switch (props.color) {
        case 'red':
            return 'focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900';
        case 'green':
            return 'focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800';
        case 'yellow':
            return "focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900";    
    }
    return props.btnClass;
});
</script>
<template>
<button
    :class="btnClass"
    :type="type"
    @click.stop="emitClick"
    :disabled="disabled">
    <slot></slot>
</button>
</template>