<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import TaskStoreForm from '@/Components/Tasks/StoreForm.vue';
    import Modal from '@/Components/Modal.vue'
    import TasksSearch from '@/Components/Tasks/Search.vue';
    import TasksList from '@/Components/Tasks/List.vue';
    import { useTasksStore } from '@/Stores/useTasksStore';

    import { ref } from 'vue';

    const modalRegister = ref(false);

    const tasksStore = useTasksStore();

    const showModalRegister = () => {
        modalRegister.value = true;
    }

    const closeModalRegister = () => {
        modalRegister.value = false;
    }
</script>
<template>
    <div class="c-tasks__search">
        <AuthenticatedLayout>
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">My Tasks</h2>
            </template> 

            <div class="py-12 w-full">
                <div class="max-w-7xl mx-auto space-y-6">
                    <div class="px-8">
                        <TasksSearch>
                            <template #after>
                                <button @click="tasksStore.openModalFormRegister()" class="bg-green-800 text-green-300 py-3 px-4 font-bold hover:bg-green-300 hover:text-green-800"> 
                                    New Task
                                </button>
                            </template>
                        </TasksSearch>
                    </div>

                    <div class="px-8 w-full">
                        <TasksList />
                    </div>
                </div>
            </div>

        </AuthenticatedLayout>  
            
        <Modal :closable="false" :show="tasksStore.modalFormRegister.show" @close="tasksStore.closeModalFormRegister()">
            <TaskStoreForm >
                <template #end>
                    <button 
                        class="bg-red-600 text-white hover:bg-red-400 hover:text-red-950 py-3 px-4 " 
                        type="button"
                        @click="closeModalRegister">
                            Cancel
                    </button>
                </template>
            </TaskStoreForm>
        </Modal>
    </div> 
</template>