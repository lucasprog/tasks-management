<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

    import TasksSearch from '@/Components/Tasks/Search.vue';
    import TasksList from '@/Components/Tasks/List.vue';


</script>
<template>
    <div class="c-tasks__search">
        <AuthenticatedLayout>
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">My Tasks</h2>
            </template>

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    <div>
                        <TasksSearch>
                            <template #after>
                                <button class="bg-green-800 text-green-300 py-3 px-4 font-bold hover:bg-green-300 hover:text-green-800"> 
                                    New Task
                                </button>
                            </template>
                        </TasksSearch>
                    </div>

                    <div>
                        <TasksList />
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>  
    </div> 
</template>