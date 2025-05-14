import { defineStore } from 'pinia'

export const useTasksStore = defineStore('alerts', {
    state: () => {
        return {
            tasks: [],
            paginate: {
                total: 0,
                per_page: 9,
                current_page: 1
            },
            loadingRequest: false
        }
    },
    
    getters: {
        noMoreTasks(){
            return this.tasks.length >= this.paginate.total;
        }
    },

    actions: {
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
            this.loadingRequest = true;
            try{

                const params = (new URLSearchParams({
                    page: this.paginate.current_page,
                    per_page: this.paginate.per_page
                })).toString();

                const { 
                    data: tasks, 
                    total, 
                    per_page, 
                    current_page 
                } = await fetch('/tasks/get?'+params)
                            .then(res => res.json());                

                this.setTasks(tasks);
                this.setTotal(total);
                this.setPerPage(per_page);
                this.setCurrentPage(current_page);

            }catch(error){
                console.error(error);
            }finally{
                this.loadingRequest = false;
            }
        }
    }
})