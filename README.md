## BASIC SOCIAL NETWORK SITE USING LARAVEL

# setup project
1. After cloning, Pull all project dependencies. 
``` 
    docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs 
```
2. instantiate docker containers to run application
```
./vendor/bin/sail up
```
3. copy .env.example to a new .env file
```
cp .env.example .env
```
4. add `DEFAULT_USER_EMAIL` and `DEFAULT_USER_PASSWORD` to .env and .env.testing variables and assign any email or password to it. this is for seeding and testing purposes
5. generate new key for application
```
./vendor/bin/sail artisan key:generate
```
6. run migration files
```
./vendor/bin/sail artisan migrate
```
7. Seed a default user for you to use
```
./vendor/bin/sail artisan db:seed --class=DefaultUserSeeder
```
8. Install node modules
```
./vendor/bin/sail npm install
```
9. Open another terminal to run a watch on resources
```
./vendor/bin/sail run dev
```
10. visit http://localhost on browser

