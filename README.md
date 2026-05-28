# Task Management API

A RESTful API built with Laravel for managing projects and tasks efficiently.
This project provides authentication, project management, task workflows, and relational data handling using Laravel Sanctum.

## Features

* User Authentication using Laravel Sanctum
* Project Management CRUD
* Task Management CRUD
* Protected API Routes
* Relational Data Handling
* Request Validation
* RESTful API Architecture
* Filtering & Structured Responses

---

## Tech Stack

* Laravel
* PHP
* MySQL
* Laravel Sanctum
* REST API
* Postman

---

## API Modules

### Authentication

* Register
* Login
* Logout
* Get Authenticated User

### Projects

* Get Projects
* Create Project
* Update Project
* Delete Project

### Tasks

* Get Tasks
* Create Task
* Update Task
* Delete Task

---

## Authentication

This API uses Laravel Sanctum for token-based authentication.

Example Authorization Header:

```http
Authorization: Bearer your_token
```

---

## Installation

Clone the repository:

```bash
git clone https://github.com/your-username/task-management-api.git
```

Install dependencies:

```bash
composer install
```

Copy environment file:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Run migrations:

```bash
php artisan migrate
```

Start the development server:

```bash
php artisan serve
```

---

## API Testing

This project was tested using Postman.

Example Base URL:

```http
http://127.0.0.1:8000/api
```

---

## What I Learned

During the development of this project, I learned:

* Building RESTful APIs using Laravel
* Implementing authentication with Laravel Sanctum
* Managing protected routes and middleware
* Designing relational database structures
* Handling CRUD operations efficiently
* Creating structured API responses
* Implementing validation and error handling
* Managing project and task workflows
* Testing APIs using Postman
* Organizing backend architecture and routes

---

## Author

Yuandi Vick Halim
