## Docker Configuration
The docker configuration consists of 3 containers:
- Laravel PHP7 container
- Nginx container
- Mysql Container

### Building the containers
* Ensure that docker is installed
* Ensure that you have run a composer install on the project document
* To build docker environment: __docker-compose up -d__

### Other useful commands
* List running containers: __docker ps__
* List available containers: __docker ps -a -q__
* Command shell: __docker exec -it <container name> bash__
* Stop running docker containers: __docker stop <container name>__
* Stop all running docker containers: __docker stop $(docker ps -a -q)__
* Destroy a docker container: __docker rm <container name>__
* Destroy all running containers: __docker rm $(docker ps -a -q)__
* Force destruction of all running containers: __docker rm -f $(docker ps -a -q)__

### Outstanding
- Add Angular container
- Add RabbitMQ container
- Include __"composer install"__ as part of the configuration

### References
- https://kyleferg.com/laravel-development-with-docker/
