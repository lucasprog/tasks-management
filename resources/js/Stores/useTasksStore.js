import { defineStore } from 'pinia'
import axios from 'axios'

export const useTasksStore = defineStore('alerts', {
    state: () => {
        return {
            tasks: [],

            paginate: {
                total: 0,
                per_page: 9,
                current_page: 1
            },

            loadingRequestList: false,
            loadingRequestSave: false,
            loadingRequestUpdate: false,

            formTasksStore: {
                title: "",
                description: "",
                starts_at: "",
                warn_me: false
            },
            formTaskStoreError: {
                title: "",
                description: "",
                starts_at: ""
            },
            modalFormRegister: {
                show: false,
                task: null
            },
            listStore: [],

            viewTasksUpdate: {
                title: "",
                description: "",
                starts_at: "",
                warn_me: false
            },
            formTasksUpdate: {
                title: "",
                description: "",
                starts_at: "",
                warn_me: false
            },
            formTaskUpdateError: {
                title: "",
                description: "",
                starts_at: ""
            },
            modalFormUpdate: {
                show: false,
                task: null,
                modeView: {
                    title: 'view',
                    description: 'view'
                }
            },
            term: ""
        }
    },
    
    getters: {
        noMoreTasks(){
            return this.tasks.length >= this.paginate.total;
        }
    },

    actions: {
        openModalFormRegister(){
            this.modalFormRegister.show = true;
        },
        closeModalFormRegister(){
            this.modalFormRegister.show = false;
        },
        clearFormStore(){
            this.formTasksStore =  {
                title: "",
                description: "",
                starts_at: "",
                warn_me: false
            };
        },
        clearFormStoreError(){
            this.formTaskStoreError = {
                title: "",
                description: "",
                starts_at: ""
            }
        },

        openModalFormUpdate(){
            this.modalFormUpdate.show = true;
        },
        closeModalFormUpdate(){
            this.modalFormUpdate.show = false;
        },
        clearFormUpdate(){
            this.formTasksUpdate =  {
                title: "",
                description: "",
                starts_at: "",
                warn_me: false
            };
        },
        setFormUpdate(form){
            this.viewTasksUpdate = form;
            this.formTasksUpdate =  {...form, starts_at: form.starts_at.split(' ').join('T').slice(0,16)};
        },
        clearFormUpdateError(){
            this.formTaskUpdateError = {
                title: "",
                description: "",
                starts_at: ""
            }
        },

        clearListStore(){
            this.listStore = [];
        },
        
        clearTasks(){
            this.tasks = [];
        },

        restartPagination(){
            this.paginate = {
                total: 0,
                per_page: 9,
                current_page: 1
            }
        },
        setTasks(tasks){
            this.tasks = [...this.tasks, ...tasks];
        },
        setTotal(total){
            this.paginate.total = total;
        },
        setPerPage(per_page){
            this.paginate.per_page = per_page;
        },
        setCurrentPage(current_page){
            this.paginate.current_page = current_page;
        },
        nextPage(){
            this.paginate.current_page++;
            this.requestTasks();
        },
        async requestTasks() {
            this.loadingRequestList = true;
            try{

                const params = (new URLSearchParams({
                    page: this.paginate.current_page,
                    per_page: this.paginate.per_page,
                    term: this.term
                })).toString();

                const { 
                    data,
                    status
                } = await axios.get('/tasks?'+params);

                if( status === 200 ) {

                    const { 
                        data: tasks, 
                        meta 
                    } = data;
    
                    const { 
                        total, 
                        per_page, 
                        current_page
                    }  = meta;
                    
                    this.setTasks(tasks);
                    this.setTotal(total);
                    this.setPerPage(per_page);
                    this.setCurrentPage(current_page);
                    
                }

            }catch(error){
                console.error(error);
            }finally{
                this.loadingRequestList = false;
            }
        },

        async saveList(taskId){

            const { data,
                    status
            } = await axios.post(`/lists/group/${taskId}`, this.listStore);

            if( status === 201 ) {

            }
        },

        async requestSave(){

            this.loadingRequestSave = true;
            this.clearFormStoreError();

            axios.post('/tasks', this.formTasksStore)
                .then(async (response) => {
                    
                    const { 
                        data,
                        status,
                    } = response;
                    
                    if( status === 201 ) {
                        await this.saveList(data.data.id);
                        this.restartPagination();
                        this.clearTasks();
                        this.requestTasks();
                        this.clearFormStore();
                        this.clearListStore();
                    }
                })
                .catch((error) => {
                    const { 
                        data,
                        status,
                    } = error.response;

                    if( status === 422 ){

                        const { errors } = data;

                        Object.keys(errors).forEach((field) => {
                            this.formTaskStoreError[field] = errors[field].join('<br>');
                        });

                    }

                });


            this.loadingRequestSave = false;

        },

        async requestUpdate(){

            this.loadingRequestUpdate = true;
            this.clearFormUpdateError();

            axios.put('/tasks', this.formTasksUpdate)
                .then(async (response) => {
                    
                    const { 
                        data,
                        status,
                    } = response;
                    
                    if( status === 200 ) {
                        this.viewTasksUpdate = this.formTasksUpdate;
                    }
                })
                .catch((error) => {
                    const { 
                        data,
                        status,
                    } = error.response;

                    if( status === 422 ){

                        const { errors } = data;

                        Object.keys(errors).forEach((field) => {
                            this.formTaskStoreError[field] = errors[field].join('<br>');
                        });

                    }

                });


            this.loadingRequestUpdate = false;

        },

        async deleteTask(taskId){

            axios.delete(`/tasks/${taskId}`)
                .then(async (response) => {
                    
                    const { 
                        status,
                    } = response;
                    
                    if( status === 200 ) {
                        this.restartPagination();
                        this.clearTasks();
                        this.requestTasks();
                    }
                });

        }
    }
})