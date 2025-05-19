
<script setup>

    import ListsItem from '@/Components/Tasks/ListsItem.vue'

    import TextInput from '@/Components/TextInput.vue';
    import Textarea from '@/Components/Textarea.vue';
    import { useTasksStore } from '@/Stores/useTasksStore';

    const tasksStore = useTasksStore();
    
    const submit = () => {
        tasksStore.requestSave()
    }

    const today = (new Date()).toISOString().slice(0,16);

</script>
<template>
    <form class="c-form flex flex-col gap-4 p-4" @submit.prevent="submit">
        <h3 class="text-white font-bold">
            Fill the form below to create your new task
        </h3>
        <TextInput 
            type="text"
            name="title"
            v-model="tasksStore.formTasksStore.title" 
            autofocus
            autocomplete="title"
            placeholder="Title of Task"
        />
        <template v-if="tasksStore.formTaskStoreError.title">
            <span class="text-red-600">{{ tasksStore.formTaskStoreError.title }}</span>
        </template>

        <Textarea 
            name="description" 
            placeholder="Description of Task"
            v-model="tasksStore.formTasksStore.description" />
        <template v-if="tasksStore.formTaskStoreError.description">
            <span class="text-red-600">{{ tasksStore.formTaskStoreError.description }}</span>
        </template>

        <input 
            type="datetime-local" 
            name="starts_at" 
            v-model="tasksStore.formTasksStore.starts_at" 
            placeholder="Start Date" 
            :min="today"
            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
        />
        <template v-if="tasksStore.formTaskStoreError.starts_at">
            <span class="text-red-600">{{ tasksStore.formTaskStoreError.starts_at }}</span>
        </template>

        <ListsItem v-model="tasksStore.listStore"/>

        <div class="c-form__footer flex gap-4 justify-end pt-4">
            <slot name="end" />
            <button 
                :disable="tasksStore.loadingRequestSave"
                type="submit" 
                class="bg-green-600 text-white py-3 px-4 font-bold hover:bg-green-300 hover:text-green-600 disable:opacity-70">
                    Save
                </button>
        </div>
    </form>
</template>