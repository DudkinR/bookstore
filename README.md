# Test for Upwork  Books Store

## Overview

This repository is a Laravel 10 project with a custom module. The module is structured as a separate package within the Laravel application.

## Module Structure

The module is defined in the `composer` file as a private type module. The following is a snippet of the `composer.json` file:

## Prerequisites

PHP 8.1

Laravel 10

Node.js and NPM (for Vite)

## Installation
Clone this repository.

Run composer install to install PHP dependencies.
: composer install


Run npm install to install Node.js dependencies.

: npm install

## Configuration
Copy the .env.example file to .env and configure your environment settings.
Run artisan to generate an application key.

: php artisan key:generate 

# Usage
## Development
Run the following commands for development:
    npm run dev

## Production
Run the following commands for production:
    npm run build

## Contributing
Feel free to contribute to this project. Follow the contribution guidelines for more details.

## DB
Main worksheet of the 'books' Where only the name of the book is stored there are also auxiliary tables - these are the 'authors' and 'publishers' who are connected to the main table by a many-to-many relationship.

: php artisan migrate

You can generate test data by running the command:

: php artisan db:seed --class=BooksTableSeeder

## start server

: php artisan serve



## CRUD
System for recording, editing, reading and deleting, (CRUD) organized using standard laravel functions. 
Recording editing and deleting books is only possible for authorized users.

## javascript part
However, according to the task, a books page was organized that works according to a javascript scheme, receives an array of data of all books, organizes pagination through its own functions, sorts and displays books by author and publicist.

Control functions for JavaScript pagination sorting and displaying data located in the file:

public\js\components\pagination.js








