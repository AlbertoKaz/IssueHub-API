# 🚀 IssueHub API (Laravel + Sanctum)

A clean and structured REST API built with Laravel 13.
Designed to manage tickets and comments through a simple and scalable backend architecture.

---

![Laravel](https://img.shields.io/badge/Laravel-13-red)
![Sanctum](https://img.shields.io/badge/Auth-Sanctum-green)
![Pest](https://img.shields.io/badge/Testing-Pest-blue)

---

## ✨ Features

* 🔐 Token-based authentication (Laravel Sanctum)
* 📝 Create and manage tickets (CRUD)
* 💬 Comment system per ticket
* 🔍 Filtering and search (status, priority, title)
* 🧠 Authorization with Policies
* 📦 Clean API responses with Resources
* ✅ Validation with Form Requests
* 🧪 Feature tests with Pest

---

## 🔄 Core Functionality

### 🎫 Tickets

* Create tickets (title + description)
* Track status:

    * `open`
    * `in_progress`
    * `closed`
* Assign priority:

    * `low`
    * `medium`
    * `high`
* Update and delete tickets
* Filter by:

    * status
    * priority
    * search (title)

### 💬 Comments

* Add comments to tickets
* List comments per ticket
* Track comment author and timestamp

---

## 🛠️ Tech Stack

* **Laravel 13**
* **PHP 8.5**
* **Laravel Sanctum**
* **Pest**
* **MySQL / SQLite**

---

## 🧱 Architecture

* RESTful API design

* Clean separation of concerns:

    * **Controllers** → handle requests
    * **Form Requests** → validation
    * **Policies** → authorization
    * **Resources** → response formatting

* User-scoped data access (security layer)

* Query filtering with dynamic parameters

* Feature testing for core endpoints

---

## 📡 API Endpoints

### 🔐 Auth

* `POST /api/login`
* `POST /api/logout`
* `GET /api/user`

---

### 🎫 Tickets

* `GET /api/tickets`
* `POST /api/tickets`
* `GET /api/tickets/{id}`
* `PATCH /api/tickets/{id}`
* `DELETE /api/tickets/{id}`

#### Filters

* `/api/tickets?status=open`
* `/api/tickets?priority=high`
* `/api/tickets?search=login`

---

### 💬 Comments

* `GET /api/tickets/{ticket}/comments`
* `POST /api/tickets/{ticket}/comments`

---

## ⚙️ Installation

```bash
git clone git@github.com:AlbertoKaz/issuehub-api.git
cd issuehub-api

composer install

cp .env.example .env
php artisan key:generate

php artisan migrate

php artisan serve
```

---

## 🔑 Environment Setup

Configure your `.env` file:

```env
DB_DATABASE=issuehub
DB_USERNAME=root
DB_PASSWORD=
```

---

## 🧪 Testing

Run all tests:

```bash
php artisan test
```

---

## 📌 Roadmap

* Pagination metadata improvements
* Advanced filtering (date ranges, multiple params)
* Ticket status enums
* Rate limiting for auth endpoints
* API versioning (`/api/v1`)
* API documentation (OpenAPI / Postman collection)

---

## 🤝 About the Project

This project was built as a portfolio-ready backend application to demonstrate:

* REST API design with Laravel
* Authentication with Sanctum
* Authorization with Policies
* Clean architecture and separation of concerns
* Testing with Pest
* Real-world backend patterns

---

## 📬 Contact

Developed by **Alberto Mendoza**
Fullstack Laravel Developer

---

⭐ If you like this project, feel free to star the repository!
