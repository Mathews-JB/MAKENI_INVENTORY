# Inventory Management System (IVM) CHANGED TO SCHOOL MANAGEMENT

## Overview
A professional, multi-company Inventory Management System built for **Zambian businesses**. Specifically designed for **Maxim Operations** (Mining/Construction), **Milo Techs** (ICT Services), and **Mukurich** (General Supply/Printing), but adaptable to any multi-branch organization.

## Key Features

### ✅ Multi-Company & Branch Management
- **Centralized Control**: specific data isolation per branch.
- **Hierarchy**: Company -> Branch -> Users/Inventory.
- **Branding**: Dynamic receipts and documents branded with the specific company's details (Logo, Address, Contact).

### ✅ Secure User Invitation System
- **Super Admin Invitation**: Invite new administrators, managers, and staff via email.
- **Secure Tokens**: Uses cryptographically secure 48-hour expiration tokens.
- **Role Assignment**: Pre-assign roles (Super Admin, Admin, Manager, Staff) and branches.
- **Invitation Repository**: Track, revoke, and manage sent invitations.
- **Email Capability**: Fully HTML-styled email templates with SMTP configuration support.

### ✅ Inventory & Supply Chain
- **Product Catalog**: Support for Goods, Services, and Fixed Assets.
- **Stock Control**: Real-time stock levels, low stock alerts, and reorder points.
- **Transfers**: Transfer stock between branches with audit trails.
- **Purchase Orders**: Create POs, manage suppliers, and **Print Professional Receipts**.

### ✅ Sales & Point of Sale
- **Sales Orders**: Record sales, manage customers, and track payments.
- **Professional Receipts**: Auto-generated, company-branded sales receipts.
- **History**: Full transaction logs for auditing.

### ✅ Modern Dashboard & UI
- **Real-time Analytics**: Sales overview, stock alerts, and recent activities.
- **Responsive Design**: Mobile-friendly interface built with TailwindCSS.
- **Interactive Elements**: Glassmorphism effects, smooth transitions, and dynamic tables.

---

## Installation & Setup

### Prerequisites
- **XAMPP** (Apache + MySQL + PHP 7.4+)
- **Git** (Optional)

### Step 1: Files
1.  Copy the `IVM` folder to your XAMPP `htdocs` directory: `C:\xampp\htdocs\IVM`

### Step 2: Database
1.  Open phpMyAdmin (`http://localhost/phpmyadmin`).
2.  Create a database named `ivm_system`.
3.  Import `database.sql` to set up tables.
4.  (Optional) Import `sample_data.sql` for demo data.

### Step 3: Configuration
1.  Open `app/config/config.php`.
2.  Verify DB settings:
    ```php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'ivm_system');
    ```

### Step 4: Email Configuration (Important)
To send real invitation emails from localhost:
1.  Follow the guide in `docs/EMAIL_SETUP.md`.
2.  It requires configuring `C:\xampp\php\php.ini` and `C:\xampp\sendmail\sendmail.ini` with your SMTP details (e.g., Gmail App Password).

---

## Usage Guide

### 1. User Management
- **Invite Users**: Go to Users > Invite User. Enter email and role.
- **Accept Invite**: User receives email -> Clicks link -> Sets password.
- **Repository**: View history at Users > History Icon.

### 2. Purchasing
- **Create PO**: Purchases > Add New. Select supplier and items.
- **Receipts**: Click the "Receipt" icon on any PO to view/print a branded document.

### 3. Sales
- **Record Sale**: Sales > Add New. Select customer and products.
- **Print**: Generated receipts include specific branch/company headers.

---

## default Accounts (For Testing)

| Role | Username | Password | Context |
|------|----------|----------|---------|
| **Super Admin** | `admin` | `admin123` | Full Access |
| **Manager** | `manager_maxim` | `admin123` | Maxim Operations |
| **Staff** | `staff_milo` | `admin123` | Milo Techs |

> **Security Warning**: Change these passwords immediately in a production environment.

---

## Project Structure

```
IVM/
├── app/
│   ├── controllers/     # Logic (Auth, Users, Sales, Purchases)
│   ├── models/          # Data Access (User, Invitation, Order)
│   ├── views/           # UI Templates (Layouts, Receipts, Forms)
│   └── config/          # Global Settings
├── public/              # Web Root (CSS, JS, Images)
├── docs/                # Documentation (Email Setup, etc.)
└── vendor/              # Third-party libraries
```

## Future Roadmap
- [ ] Barcode/QR Code scanning integration.
- [ ] Advanced graphical reports and charts.
- [ ] SMS Notifications via API.
- [ ] Offline-first mobile application.

---

**Developed for Zambian Enterprise**
*Professional • Scalable • Secure*
