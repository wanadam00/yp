---

# 📘 Laravel Class & Student Management System

This is a **Laravel + Inertia.js + Vue 3** web application for managing classes and student assignments with role-based access control.

---

## 📋 Requirements

Before you begin, make sure you have the following installed:

* **PHP** ≥ 8.1
* **Composer**
* **Node.js** ≥ 18
* **NPM**
* **MySQL**
* **Git**

---

## 🚀 Installation Guide

### 1️⃣ Clone the Repository

```bash
git clone <your-repository-url>
cd <project-folder>
```

---

### 2️⃣ Install Backend Dependencies

```bash
composer install
```

---

### 3️⃣ Install Frontend Dependencies

```bash
npm install
```

---

### 4️⃣ Environment Setup

Copy the example environment file:

```bash
cp .env.example .env
```

Update the `.env` file with your database credentials:

```env
DB_DATABASE=your_database_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

---

### 5️⃣ Generate Application Key

```bash
php artisan key:generate
```

---

### 6️⃣ Run Database Migration & Seeder

```bash
php artisan migrate --seed
```

> This will create required tables and default roles/users.

---

### 7️⃣ Build Frontend Assets

```bash
npm run dev
```

For production:

```bash
npm run build
```

---

### 8️⃣ Start the Application

```bash
php artisan serve
```

Open in browser:

```
http://127.0.0.1:8000
```

---

## 🔐 Default Login (If Seeded)

| Role     | Email                                         | Password |
| -------- | --------------------------------------------- | -------- |
| Lecturer | lecturer@example.com | password |
| Student  | student@example.com  | password |

All user seeder password are same which is **password**.

> Change credentials after first login.

---

## 👥 User Roles

* **Lecturer**

  * Manage classes
  * Assign students to classes
  * Manage subjects
  * Manage exams

* **Student**

  * View assigned classes
  * Access exams

---

## 🗂️ Project Structure (Important)

```
app/
 └── Models/
     └── ClassStudent.php

resources/
 └── js/
     └── Pages/
         └── ClassStudent/
```

---

## ⚠️ Common Issues & Fixes

### ❌ Page shows blank after clone

Run:

```bash
npm install
npm run dev
```

### ❌ Database error

Check `.env` database configuration and rerun:

```bash
php artisan migrate:fresh --seed
```

### ❌ Permission denied

Run:

```bash
php artisan optimize:clear
```

---

## 🧪 Development Notes

* Uses **Inertia shared props** for authentication data
* Sidebar navigation is role-based
* Backend routes are protected using role middleware

---

## 📄 License

This project is for **educational and internal use**.

---

## 🧑‍💻 Author

**Wan Adam**
Laravel & Vue Developer

---
