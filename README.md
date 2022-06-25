### What is this (project)?
Sample how to refactor simple app to symfony app.

### How to run it?
Simply use docker to build containers with `docker compose up -d` and 
then run app with `docker exec -it app_smelly php bin/console app:rate-calc`

### Is this code tested?
Yes. It is using PHPUnit. To run it siply go with `docker exec -it app_smelly php bin/phpunit`

### What was used to build this?
Symfony framework only, no external sources were needed