<script setup>
    const props = defineProps({ task: Object });
</script>
<template>
    <div class="c-card bg-slate-200 text-slate-800 p-4 h-80">
        <h3 class="flex flex-col gap-2">
            {{ task.id }} {{ task.title }}
            <small class="block">
                {{ task.description }}
            </small>
        </h3>
    </div>    
</template>