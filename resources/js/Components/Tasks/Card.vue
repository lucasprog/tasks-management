<script setup>
    import { IconBellRingingFill, IconBellSimpleSlashFill, IconTrash } from '@iconify-prerendered/vue-ph';
    import { useTasksStore } from '@/Stores/useTasksStore';

    const props = defineProps({ task: Object });

    const tasksStore = useTasksStore();

    const deleteTask = () => {
        tasksStore.deleteTask(props.task.id);
    }

    const viewTask = (task) => {
        tasksStore.openModalFormUpdate()
        tasksStore.setFormUpdate(task);
    }

</script>
<template>
    <div class="c-card bg-slate-200 text-slate-800 p-4 relative">
        <div class="c-card__header absolute top-0 flex justify-end w-full right-0 p-4">
            <button>
                <template v-if="task.warn_me">
                    <IconBellRingingFill size="32" />
                </template>
                <template v-else>
                    <IconBellSimpleSlashFill size="32" />
                </template>
            </button>   
            <button @click="deleteTask()">
                <IconTrash />
            </button>
        </div>
        <h3 class="flex flex-col gap-2 cursor-pointer" @click="viewTask(task)">
            {{ task.title }}
            <small class="block">
                {{ task.description }}
            </small>
        </h3>
        <div>
            <ul class="overflow-hidden max-h-[70px] h-full block">
                <template v-for="item in task.lists">
                    <li :class="{ 'line-through' : item.done }">
                        {{ item.item }}
                    </li>
                </template>
            </ul>
        </div>
    </div>    
</template>