<template>
    <table ref="TVTABLE" class="tv-table w-full	">
        <slot></slot>
    </table>
</template>

<script setup>
import {defineComponent} from 'vue';

defineComponent({
    name: 'TTable',
})

</script>