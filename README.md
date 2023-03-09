# Main README File

> :warning: Please read this __entire__ file before proceeding with any further steps.

 
# Setup and Design

This project is built using *__Laravel__* on top of *__Laravel sail__*. Primarily, it needs __docker__ do run on a machine.
Docker was used for ease of development, testing, and deployment. The Laravel ecosystem is rich enough with tools to develop __RESTful__ APIs, which this project demonstrates.

### Setup Steps:
> :warning: make sure you have no servers running on port `80` or having any docker containers running as you install the application as
this could conflict with port mapping or docker DNS resolution policies. 

You can use `docker stop $(docker ps -aq)` to stop all running containers. You can then run `docker container prune -f`
 to clear all containers from the docker deamon `dockerd`; leaving space for this project to be run smoothly.
 
1. make sure you have docker installed on your machine by running the following command:
2. clone the project from its github hub repository here
3. `cd` into the directory you cloned the project into.
4. run `php artisan sail:install`
5. run `sail:up` and wait for docker to pull all used images.
6. visit `localhost` and you should see a laravel landing page. This means the project has been setup correctly on top of docker.


