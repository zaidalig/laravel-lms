# laravel-mysql-lms

Laravel MySQL learning management system (LMS) with a public course catalog, an admin/instructor backend, and a student learning portal with lesson-by-lesson progress tracking.

## Tech Stack

- PHP 8.5
- Laravel 13
- MySQL / MariaDB
- Blade + Bootstrap 5 + Font Awesome

## Two Areas

1. **Public catalog (LearnHub)** — landing page with featured courses, searchable catalog with category and level filters, and course detail pages with lesson lists and one-click enrollment.
2. **Authenticated backend (LearnHub LMS)** — role-aware dashboard, course/category/lesson management for owners and instructors, user management for owners, and a "My Learning" portal where students track progress and mark lessons complete.

## Features

- Public course catalog: search by title, filter by category and level
- Course detail pages with instructor, price, and lesson breakdown
- Enrollment with duplicate protection (one enrollment per student per course)
- Student learning page: lesson viewer, completed checkmarks, progress bars
- Enrollments auto-complete when all lessons are marked done
- Course CRUD with slug generation, draft/published/archived workflow
- Nested lesson management (add on course page, reorder by position)
- Category CRUD with course counts
- Role-based access: owner, instructor, student, viewer
- Search and filter controls on every backend list page
- Activity logs for categories, courses, lessons, and enrollments

## Roles

| Role | Access |
|------|--------|
| owner | Everything, including user management |
| instructor | Courses, categories, lessons, activity logs |
| student | Public catalog, enrollment, My Learning portal |
| viewer | Dashboard only (read-only account) |

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Create database:

```sql
CREATE DATABASE lms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Set `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lms
DB_USERNAME=root
DB_PASSWORD=
```

Run:

```bash
php artisan migrate --seed
php artisan serve
php artisan test
```

## Demo Login

| Email | Role | Password |
|-------|------|----------|
| owner@example.com | Owner | password |
| instructor@example.com | Instructor | password |
| student@example.com | Student | password |
| student2@example.com | Student | password |
| viewer@example.com | Viewer (inactive) | password |
