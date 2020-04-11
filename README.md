# YNIT PHP Framework
A really tiny php framework to bootstrap simple projects

## Installing
Just download this repository and start working on your app.
Yeah, no composer requires here, you can use composer if you like to install dependencies although the idea of this framework is to be as tiny as possible. It already includes a PSR-4 loader which might be good enough for your needs.

## Structure
app: your app classes go here. Everything under this folder expects to use the "App" namespace.
app/Controller.php: thats a default controller with a couple of methods you can play with.
app/Console: commands expected to be executed from the console.
public/index.php: this is your front controller, and the place where you define the routes of the app.

## Routes
New routes can be added in index.php, all controllers and classes are loaded following PSR-4

## Docker
The provided Dockerfile will create a single image with php-fpm and nginx
running `docker-compose up` would start the container and make nginx accessible from http://localhost:8080

## Console
Any class under Console folder can be executed from the command line as ./console MyCommand
