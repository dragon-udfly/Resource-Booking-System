# Hall and Quarters Booking System - detailed System Overview

## 1. Introduction
This **Resource Booking System** for the District Secretariat, Vavuniya, is a web-based application designed to modernize and automate the management of official resources. It replaces manual processes with a secure, digital platform for booking halls and allocating government quarters, ensuring transparency, efficiency, and accountability.

**Project Name:** Resource Booking System for District Secretariat, Vavuniya
**Target User:** District Secretariat Staff & Administration

---

## 2. Technology Stack

*   **Backend Framework:** Laravel (PHP) - Providing robust MVC architecture, security, and ORM.
*   **Database:** MariaDB (MySQL) - Storing user data, resource inventories, and booking records.
*   **Frontend:** HTML5, CSS3, JavaScript - Responsive user interface.
*   **Server Environment:** Apache/Nginx (Typical for Laravel), running on Windows (User's Dev Environment).

---

## 3. Core Modules & Features

### 3.1 Authentication & Role-Based Access Control (RBAC)
The system employs a strict permission model to ensure users only access features relevant to their role.
*   **Roles:**
    *   **Admin:** Full system control, user management, resource configuration.
    *   **District Secretary / GA:** Final approval authority.
    *   **Branch Head:** Departmental approvals.
    *   **PA (Personal Assistant):** Intermediate processing.
    *   **Staff:** Standard access for booking requests.
*   **Features:**
    *   **Secure Login/Logout:** Standard session-based authentication.
    *   **User Management:** Admin can create, edit, delete, and view system users (`/officers`).
    *   **Profile Management:** Users can change passwords and update preferences.
    *   **Audit Logging:** Comprehensive tracking of user actions for security auditing (`/auditlog`).

### 3.2 Hall Booking Management
Facilitates the reservation of official halls (Auditorium, Conference Hall, Training Hall).
*   **Booking Workflow:**
    1.  **Availability Check:** Users view the schedule (`/hallschedule`) and check availability.
    2.  **Booking Request:** Submit a booking form with details (Event, Date, Time, Refreshments).
    3.  **Requester Verification:** Helper feature to verify external requester details.
    4.  **Approval Chain:** The request goes through a multi-step review process (Pending -> Reviewed -> Approved/Rejected).
*   **Features:**
    *   **Dashboard:** Centralized view for approvers to manage pending requests.
    *   **PDF Generation:** Download official booking confirmation letters.
    *   **Resource Management:** Admin can configure hall details (Capacity, A/C, Projector, etc.).
    *   **Booking History:** Archive of past bookings (`/history`).

### 3.3 Government Quarters Allocation
Manages the application and allocation process for government housing.
*   **Quarter Types:**
    *   **Family Quarters:** For long-term staff housing.
    *   **Scheduled Quarters:** For specific, time-bound allocations.
*   **Allocation Process:**
    1.  **Application:** Staff submits an application form (`/familyquarter` or `/scheduledquarter`).
    2.  **Eligibility Check:** Automatic verification against Grade and Salary thresholds (`/gradesalary`) and Service requirements.
    3.  **Scoring System:** Automated marking scheme (`/marking-scheme`) calculates applicant priority.
    4.  **Review & Allocation:** Admin/GA reviews the application (including "Special Reasons") and allocates or rejects.
*   **Features:**
    *   **Occupancy Management:** Track current occupants and vacancies (`/occupantdetails`).
    *   **Waitlist & History:** Manage rejected applications and view allocation history.

### 3.4 Internal Memo System
A built-in messaging system for official internal communication.
*   **Features:**
    *   **Inbox/Outbox:** Separate views for received and sent memos.
    *   **Real-time Updates:** Asynchronous fetching of new messages.
    *   **Response Handling:** Standardized responses (e.g., Yes/No) with status tracking.
    *   **Maintenance:** Options to clear read or sent history.

### 3.5 System Administration
Tools for maintaining system health and data integrity.
*   **Data Management:**
    *   **Backup & Restore:** Granular controls to backup/restore database tables (Halls, Quarters, Officers, etc.).
    *   **Bulk Clearing:** Utilities to clear audit logs, bookings, or user data during maintenance cycles.
*   **System Status:** Dashboard to view application health metrics.

---

## 4. Key Workflows

### Hall Booking Approval Path
`User Request` -> `System Validation (Availability)` -> `PA Review` -> `Branch Head Recommendation` -> `GA Final Approval` -> `Notification`

### Quarters Allocation Path
`Staff Application` -> `System Grading (Points Calculation)` -> `Eligibility Check (Salary/Grade)` -> `Admin Review` -> `Allocation` -> `Occupancy Record`

---

## 5. Security Measures
*   **CSRF Protection:** All forms are protected against Cross-Site Request Forgery.
*   **Middleware:** `auth` and `admin` middleware ensure unauthorized access is blocked (e.g., in `routes/web.php`).
*   **Input Validation:** Server-side validation for all form submissions.
