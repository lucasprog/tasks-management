<script setup>
    import ListsItem from '@/Components/Tasks/ListsItem.vue'
    import { IconPencilSimpleLine } from '@iconify-prerendered/vue-ph';
    import { IconCheck, IconX, IconPlus, IconTrash } from '@iconify-prerendered/vue-ph';
    import TextInput from '@/Components/TextInput.vue';
    import Textarea from '@/Components/Textarea.vue';
    import { useForm } from '@inertiajs/vue3';
    import { useTasksStore } from '@/Stores/useTasksStore';

    import { ref } from 'vue';    
        
    const tasksStore = useTasksStore();


    const saveChange = () => {
        tasksStore.requestUpdate();
    }

    const cancel = (who) => {
        tasksStore.modalFormUpdate.modeView[who] = 'view';
    }

</script>
<template>
    <div class="c-view-edit-form flex flex-col gap-2 text-white p-4">
        <template v-if="tasksStore.modalFormUpdate.modeView.title === 'view'">
            <h1 class="flex items-end gap-2">
                {{ tasksStore.viewTasksUpdate.title}} 
                <button @click="tasksStore.modalFormUpdate.modeView.title = 'edit'">
                    <IconPencilSimpleLine class="mb-[4px]"/>
                </button>
            </h1>
        </template>
        <template v-else>
            <div class="w-full flex gap-2">
                <TextInput 
                    class="w-full"
                    type="text"
                    name="title"
                    v-model="tasksStore.formTasksUpdate.title" 
                    autofocus
                    autocomplete="title"
                    placeholder="Title of Task"
               />
               <button @click="saveChange()">
                   <IconCheck />
               </button>
               <button @click="cancel('title')">
                   <IconX />
               </button>
            </div>
            <template v-if="tasksStore.formTaskUpdateError.title">
                <span class="text-red-600">{{ tasksStore.formTaskUpdateError.title }}</span>
            </template>
        </template>

        <template v-if="tasksStore.modalFormUpdate.modeView.description === 'view'">
            <div class="flex items-end gap-2 mb-4">
                {{ tasksStore.viewTasksUpdate.description }} 
                <button @click="tasksStore.modalFormUpdate.modeView.description = 'edit'">
                    <IconPencilSimpleLine  class="mb-[4px]"/>
                </button>
            </div>
        </template>
        <template v-else>
            <div class="w-full flex gap-2">
                <Textarea 
                    class="w-full"
                   name="description" 
                   placeholder="Description of Task"
                   v-model="tasksStore.formTasksUpdate.description" />
               <button @click="saveChange()">
                   <IconCheck />
               </button>
               <button @click="cancel('description')">
                   <IconX />
               </button>
            </div>
            <template v-if="tasksStore.formTaskUpdateError.description">
                <span class="text-red-600">{{ tasksStore.formTaskUpdateError.description }}</span>
            </template>
        </template>
        <input 
            type="datetime-local" 
            name="starts_at" 
            v-model="tasksStore.formTasksUpdate.starts_at" 
            placeholder="Start Date" 
            :min="today"
            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
        />
        <template v-if="tasksStore.formTaskUpdateError.starts_at">
            <span class="text-red-600">{{ tasksStore.formTaskUpdateError.starts_at }}</span>
        </template>

        <ListsItem :taskId="tasksStore.viewTasksUpdate.id"/>
    </div>
</template>