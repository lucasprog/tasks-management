<script setup>
    import { computed, ref, onMounted } from 'vue';
    import { useTasksStore } from '@/Stores/useTasksStore';
    import TaskCard from '@/Components/Tasks/Card.vue';
    import Modal from '@/Components/Modal.vue'
    import TaskViewEditForm from '@/Components/Tasks/ViewEditForm.vue';

    const tasksStore = useTasksStore();

    const tasksToList = computed(() => tasksStore.tasks );
    
    tasksStore.requestTasks();

    const seeMore = () => {
        tasksStore.nextPage();
        window.scrollTo(0, document.body.scrollHeight);
    }

    
</script>
<template>
    <div class="flex py-8 items-center justify-center flex-col gap-4 w-full">
        <div class="flex flex-1 gap-4 flex-wrap items-start justify-start w-full">
            <template v-for="(task, key) in tasksToList" :key="key">
                <div class="flex-shrink flex-grow basis-[25%] w-1/3">
                    <TaskCard :task="task" />
                </div>
            </template>
        </div>
        <div v-if="tasksStore.loadingRequestList" class="animate-pulse flex flex-1 gap-4 flex-wrap items-start justify-start w-full">
            <template v-for="i in tasksStore.paginate.per_page">
                <div class="h-80 bg-gray-700 flex-shrink flex-grow basis-[25%] max-w-[400px] w-full"></div>
            </template>            
        </div>
        
        <template v-if="!tasksStore.noMoreTasks">
            <button @click="seeMore()" class="bg-emerald-600 text-white hover:bg-emerald-200 hover:text-emerald-950 py-4 px-8">
                See More
            </button>
        </template>

        <Modal :show="tasksStore.modalFormUpdate.show" :closeable="true" @close="tasksStore.modalFormUpdate.show = false">            
            <TaskViewEditForm />
        </Modal>
    </div>
</template>