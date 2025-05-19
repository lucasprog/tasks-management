<script setup>
    import { ref, onMounted } from 'vue';
    import { IconCheck, IconX, IconPlus, IconTrash } from '@iconify-prerendered/vue-ph';
    import TextInput from '@/Components/TextInput.vue';
    import axios from 'axios';

    const {taskId}  = defineProps({
        taskId: Number
    });

    const emits = defineEmits(["update:modelValue"])

    const listsItem = ref([]);

    const getListOfTask = async () => {
        
        listsItem.value = [];
        
        const response = await axios.get(`/lists/${taskId}`);
            
        if( response.status === 200 ){
            response.data.map((item) => 
                listsItem.value.push({
                    item: item.item, 
                    done: item.done, 
                    id: item.id,
                    inputEdit: "",
                    mode: 'view'  
                })
            );
        }
    }

    const add = () => {
        listsItem.value.push({
            id : null,
            item: "",
            inputEdit: "",
            done: false,
            mode: 'edit'
        });
    }

    const remove = async (lItemId) => {
        const { status } = await axios.delete(`/lists/${taskId}/${lItemId}`);

        if( status === 200 ){
            getListOfTask();
        }
    }

    const cancel = (index) => {
        listsItem.value[index].mode = 'view';

        if( listsItem.value[index].item === "" ) {
            remove(index);
        }
    }

    const editItem = (lItem) => {
        lItem.mode = 'edit';
        lItem.inputEdit = lItem.inputEdit ?? lItem.item;
    }

    const toggleCheck = (lItem) => {
        lItem.inputEdit = lItem.item;
        saveChange(lItem);
    }

    const saveChange = async (lItem) => {
        if( taskId ){
            if( !lItem.id ){
                const { status, data } = await axios.post(`lists/${taskId}`,{
                    item: lItem.inputEdit,
                    done: lItem.done
                });
    
                if( status === 201 ){
                    lItem.item = lItem.inputEdit;
                    lItem.id = data.id
                }
            }else{
                const { status } = await axios.put(`lists/${taskId}`,{
                    id: lItem.id,
                    item: lItem.inputEdit,
                    done: lItem.done
                });
    
                if( status === 200 ){
                    lItem.item = lItem.inputEdit;
                }
            }
        }else{
            lItem.item = lItem.inputEdit;
            emits('update:modelValue',listsItem.value.map((item) => {
                return {
                    item: item.item,
                    done: item.done
                }  
            }));
        }
        
        lItem.mode = 'view';
    }

    onMounted( async () => {
        if( taskId ){
            getListOfTask()
        }
    });

</script>
<template>
    <div class="c-lists-item flex flex-col gap-2 justify-center items-start p-4 bg-gray-700 ">
        <h2>
            To do
        </h2>
        <template v-for="(lItem,index) in listsItem">
            <div class="text-white flex items-center justify-between max-w-80 w-full odd:bg-gray-600 even::bg-gray-500 hover:opacity-80 p-2" v-if="lItem.mode === 'view'">
                <button type="button" class="cursor-text" @click="editItem(lItem) ">
                    <span > {{lItem.item }} </span> 
                </button>
                <div class="flex items-center gap-2">
                    <button type="button" @click="remove(lItem.id)">
                        <IconTrash />
                    </button>
                    <input type="checkbox" v-model="lItem.done" @change="toggleCheck(lItem)"/>
                </div>
            </div>
            <div class="text-white flex items-center justify-between max-w-80 w-full odd:bg-gray-600 even::bg-gray-500 hover:opacity-80 p-2" v-if="lItem.mode === 'edit'">
                <TextInput 
                    type="text" 
                    v-model="lItem.inputEdit"
                    placeholder="Type item description" 
                    @keyup.enter="saveChange(lItem)"
                />
                <button type="button" @click="saveChange(lItem)">
                    <IconCheck />
                </button>
                <button type="button" @click="cancel(index)">
                    <IconX />
                </button>
            </div>
        </template>
        <button type="button" class="text-white flex items-center justify-between max-w-80 w-full odd:bg-gray-600 opacity-40 hover:opacity-100 p-2" @click="add()">
            <span>New Item</span> 
            <span>
                <IconPlus />
            </span>
        </button>
    </div>
</template>