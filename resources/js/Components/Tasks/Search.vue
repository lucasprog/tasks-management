<script setup>

    import { ref } from 'vue';

    const inputSearch = ref();
    const search = () => {}

</script>
<template>
    <form @submit.prevent="search" class="flex gap-4">
        <slot name="before" />
        <input 
            type="search" 
            name="search" 
            v-model="inputSearch" 
            placeholder="Search..."
            class="border border-color bg-transparent text-white h-12"
        />
        <button class="bg-cyan-600 text-cyan-950 py-3 px-4 font-bold hover:bg-cyan-950 hover:text-cyan-600" type="submit">
            Search
        </button>
        <slot name="after" />
    </form>
</template>