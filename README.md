<div align="center">

# 🏛️ OneID Pension System

### Unified Elderly Citizen Identification & Digital Pension Management

**A Full-Stack Laravel Web Application**
Final Year Computer Science Project — College Submission

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat-square&logo=bootstrap)](https://getbootstrap.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat-square&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)

</div>

---

## 📋 Project Overview

The **OneID Pension System** is a comprehensive digital government portal that streamlines elderly citizen registration and pension management. Every registered citizen receives a unique **OneID** (e.g., `OID-2026-483921`) — a centralized identification number that prevents duplicate registrations and fraud.

### 🎯 Key Goals
- Digitize elderly citizen registration with Aadhaar-based verification
- Automate OneID generation for every citizen
- Enable online pension applications and approvals
- Provide transparent payment tracking with downloadable receipts
- Prevent identity fraud through centralized identification

---

## ✨ Features

### 👮 Admin Portal
| Feature | Description |
|---|---|
| Dashboard | Real-time stats, Chart.js graphs, recent activity |
| Citizen Management | View, search, and verify elderly citizens |
| OneID Search | Instantly look up any citizen by their OneID |
| Application Review | Approve or reject pension applications with remarks |
| Scheme Management | Create/edit/delete pension schemes (CRUD) |
| Payment Recording | Record monthly payments per approved application |
| Notification System | Automatic alerts sent to citizens on decisions |

### 👴 Citizen Portal
| Feature | Description |
|---|---|
| OneID Display | Prominent display of unique government ID |
| Profile Management | Submit and update personal, address & bank details |
| Pension Application | Browse eligible schemes and apply online |
| Payment History | Full ledger of payments received |
| PDF Receipt Download | Downloadable official pension receipt per payment |
| Notifications | In-app notifications for approvals, rejections & payments |

---

## 🗄️ Database Schema

### Entity-Relationship Overview

```
users (id, name, email, password, role, is_active)
   │
   └─── elderly_profiles (id, user_id, one_id, full_name, age, gender,
   │         address, phone, aadhaar_number, bank_account_number,
   │         bank_name, ifsc_code, profile_photo, government_id_photo,
   │         is_verified, verified_at, verified_by, verification_remarks)
   │            │
   │            └─── pension_applications (id, elderly_profile_id, scheme_id,
   │                      application_number, status, applied_at,
   │                      reviewed_at, reviewed_by, remarks)
   │                           │
   │                           └─── pension_payments (id, pension_application_id,
   │                                     receipt_number, amount, payment_date,
   │                                     month, year, status, transaction_ref)
   │
   └─── notifications (id, user_id, title, message, type, link, is_read)

pension_schemes (id, name, scheme_code, description,
      monthly_amount, eligibility_age, status)
```

### Table Relationships
- `users` → `elderly_profiles` : One-to-One
- `elderly_profiles` → `pension_applications` : One-to-Many
- `pension_applications` → `pension_payments` : One-to-Many
- `pension_schemes` → `pension_applications` : One-to-Many
- `users` → `notifications` : One-to-Many

---

## 🛠️ Tech Stack

| Layer | Technology | Purpose |
|---|---|---|
| Backend | Laravel 13.x | MVC framework |
| Language | PHP 8.3 | Server-side logic |
| Database | SQLite / MySQL | Data persistence |
| Frontend | Bootstrap 5.3 | Responsive UI |
| Icons | Font Awesome 6 | Icon library |
| Charts | Chart.js 4.4 | Admin visualizations |
| PDF | barryvdh/laravel-dompdf | Receipt generation |
| Auth | Laravel Breeze | Authentication scaffolding |
| Fonts | Inter (Google Fonts) | Typography |

---

## ⚙️ Setup Instructions

### Prerequisites
- PHP >= 8.3
- Composer >= 2.x
- Node.js >= 18.x (for asset compilation)

### Installation

```bash
# 1. Clone the repository
git clone <repo-url> laravel_project
cd laravel_project

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Create environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Run migrations and seed dummy data
php artisan migrate:fresh --seed

# 7. Create storage symlink
php artisan storage:link

# 8. Start the development server
php artisan serve
```

Open your browser at **http://127.0.0.1:8000**

---

## 🔑 Demo Credentials

| Role | Email | Password |
|---|---|---|
| **Admin** | admin@oneid.gov.in | Admin@1234 |
| **Citizen 1** | ramesh@example.com | User@1234 |
| **Citizen 2** | sunita@example.com | User@1234 |
| **Citizen 3** | mohan@example.com | User@1234 |

---

## 🗂️ Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/               # Login, Register, Password Reset
│   │   ├── Admin/              # DashboardController, UserController,
│   │   │                       # ApplicationController, SchemeController,
│   │   │                       # PaymentController
│   │   ├── User/               # DashboardController, ProfileController,
│   │   │                       # ApplicationController, PaymentController
│   │   └── NotificationController.php
│   └── Middleware/
│       ├── AdminMiddleware.php          # Restricts to admin role
│       └── EnsureProfileComplete.php   # Redirects to profile creation
├── Models/
│   ├── User.php                # Auth + role helpers
│   ├── ElderlyProfile.php      # OneID generation + scopes
│   ├── PensionScheme.php       # Scheme data + active scope
│   ├── PensionApplication.php  # Application lifecycle
│   ├── PensionPayment.php      # Payment records + receipt number
│   └── Notification.php        # In-app notification system

database/
├── migrations/                 # 9 migration files for all tables
└── seeders/
    ├── AdminSeeder.php          # Creates admin account
    ├── SchemeSeeder.php         # 4 pension schemes
    └── ElderlySeeder.php        # 5 elderly users with full data

resources/views/
├── layouts/
│   ├── admin.blade.php          # Admin portal layout (sidebar + topbar)
│   ├── user.blade.php           # Citizen portal layout
│   └── guest.blade.php          # Authentication pages layout
├── auth/                        # Login, Register views
├── admin/
│   ├── dashboard.blade.php
│   ├── users/                   # index, show, search
│   ├── applications/            # index, show
│   ├── schemes/                 # index, create, edit
│   └── payments/                # index, create
└── user/
    ├── dashboard.blade.php
    ├── profile/                 # index, create
    ├── applications/            # index, create
    ├── payments/                # index, receipt (PDF)
    └── notifications/           # index

public/css/app.css               # Custom government portal theme

routes/
├── web.php                      # All application routes (grouped by role)
└── auth.php                     # Authentication routes
```

---

## 🔒 Security Features

- **RBAC Middleware** — Admin and User routes are fully separated
- **Aadhaar Uniqueness** — Prevents duplicate citizen registrations
- **Profile Verification Gate** — Users cannot apply until admin verifies their profile
- **Payment Authorization** — Users can only download their own receipts
- **CSRF Protection** — All forms include CSRF tokens
- **Mass Assignment Protection** — All models use `$fillable`

---

## 📊 OneID Format

Every registered citizen gets a permanent, unique ID:

```
OID-{YEAR}-{6-DIGIT-RANDOM}
Example: OID-2026-483921
```

This ID is:
- Generated automatically on profile creation
- Stored permanently and never changes
- Searchable by admins for instant citizen lookup
- Displayed prominently in the citizen portal

---

## 🎓 Viva Talking Points

1. **Why Laravel?** — MVC architecture enforces clean separation of concerns; Eloquent ORM simplifies complex relationships
2. **Why OneID?** — Prevents duplicate pension claims; provides a single source of truth across departments
3. **RBAC Implementation** — Custom middleware checks `$user->role` on every request to admin/user routes
4. **PDF Generation** — `barryvdh/laravel-dompdf` renders a Blade view to PDF server-side, no client-side dependency
5. **Database Design** — Normalized to 3NF; foreign keys enforce referential integrity across all 6 tables

---

## 📄 License

This project is for academic use. Built with ❤️ for elderly welfare and digital governance.
