<script setup lang="ts">
import { nextTick } from 'vue';
import { onMounted, ref,PropType, reactive , watch} from 'vue';

interface opt {
  value: string,
  text: string
};

const props =defineProps<{
    modelValue: string;
    optionsValues : opt[] , 
}>();
const localoptionsValues = ref(props.optionsValues)
var select_show = true
defineEmits(['update:modelValue']);

watch(() => props.optionsValues, (val) => {
      console.log(
        "Watch props.selected function called with args:",
        val
      );
      select_show=false
      localoptionsValues.value =val
    //  nextTick(()=>{
        select_show=true
      })
   // });

const input = ref<HTMLInputElement | null>(null);

onMounted(() => {
    if (input.value?.hasAttribute('autofocus')) {
        input.value?.focus();
    }
});

defineExpose({ focus: () => input.value?.focus() });
</script>

<template>
    <select v-if="select_show"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        :value="modelValue"
        @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
        ref="input"
    >
    <option v-for="item of localoptionsValues" :value="item.value" >{{ item.text }}</option>
    </select>

</template>
