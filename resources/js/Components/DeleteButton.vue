
<script setup>
import { ref } from 'vue';
import Button from '@/Components/Button.vue';
import Icon from '@/Components/Icon.vue';

const emit = defineEmits(['confirm-delete']);

defineProps({
    disabled: Boolean,
    label: String,
});
const showConfirm = ref(false);
const confirmDelete = () => {
    showConfirm.value = false;
    emit('confirm-delete');
};
</script>
<template>
    <span>
        <Button
            color="red"
            v-show="!showConfirm"
            @click="showConfirm=true"
            :disabled="disabled">
            <Icon icon="trash"/>
        </Button>
        <div v-show="showConfirm">
            <Button
                color="green"
                @click="confirmDelete">
                <Icon icon="check"/>
            </Button>
            <Button
                color="red"
                @click="showConfirm=false">
                <Icon icon="x"/>
            </Button>
        </div>
    </span>
</template>
