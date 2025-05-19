# Tasks Management

This is a simple project to manager tasks with simple CRUD using Laravel 10, InertiaJS with Vue3.


## Start Project

**Step 1 Run:**
> docker compose up -d

**Step 2 Run:**
> docker exec -it tasks-management-workspace-1 /bin/bash setup.sh

***This command will run the setup.sh file that have the commands to configure the project, you can checkout these commands below***
> *File: setup.sh
cp .env.example .env && \
composer install && \
php artisan key:generate && \ 
php artisan migrate && \ 
php artisan db:seed && \ (Creating User)
php artisan db:seed --class=TaskSeeder* (Creating Initial Tasks)

**Step 3**
To run front-end correctly is necessary to run the followed commands outside of container at root project
>npm install && npm run dev

After that the project will be running

## Login
By default the user access will be
> Email: test@example.com
> Paswword: 123456987