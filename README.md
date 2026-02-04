# 💻 Resource Booking System for District Secretariat, Vavuniya.

## Introduction

This is a **Web-Based Resource Management System (Hall and Quarters Booking System)** designed for the **District Secretariat, Vavuniya**, to modernize and streamline the process of booking official resources. Developed using the **Laravel** framework, this application replaces manual, paper-based forms with a secure, digital platform to enhance efficiency, enforce administrative policies, and eliminate double-booking conflicts.

---

## 🎯 Purpose and Scope

The system's primary goal is to ensure the transparent and efficient allocation of two critical resources:

1.  **Hall Bookings:** Manages the reservation of various halls (Auditorium, Conference, Training) via a **three-step approval workflow** (PA → AO/AGA/GA).
2.  **Quarters Allocation:** Facilitates applications for government quarters with an **automated eligibility check** that verifies staff **grade, salary, and service-based constraints** before final allocation by the Governement Agent.

## ⚙️ Technology Stack

| Component | Technology | Role |
| :--- | :--- | :--- |
| **Backend Framework** | **Laravel (PHP)** | Provides the robust MVC structure, routing, and business logic. |
| **Database** | **MariaDB (MySQL)** | Securely stores all user, resource, and transactional data. |
| **Frontend** | **HTML5, CSS3, JavaScript** | Delivers a responsive and interactive user interface. |
| **Key Libraries** | **DomPDF** | `barryvdh/laravel-dompdf` for generating official PDF letters. |
| | **Carbon** | `nesbot/carbon` for advanced date and time manipulation. |
| | **Laravel Mailer** | Native `Illuminate\Mail` facade for handling email notifications. |
| **Web Server** | **Nginx / XAMPP** | Production and local development server environments. |
| **Database Tools** | **MySQL Workbench** | GUI for database design, management, and administration. |
| **API Testing** | **Postman** | Testing and validating API endpoints and routes. |
| **Design & Planning** | **Draw.io** | Creating system architecture diagrams and flowcharts. |

---

## ✨ Comprehensive System Features

The system is packed with features designed to handle every aspect of resource management, from initial request to final approval and auditing.

### 🏢 Hall Booking Management
*   **Public Booking Portal**:
    *   **Availability Checker**: Interactive "Hall Overview" to verify open dates and view hall details.
    *   **Schedule Calendar**: Visual calendar to view upcoming events and bookings (`/hallschedule`).
    *   **Digital Application**: Streamlined form for ensuring all necessary applicant details are captured.
*   **Approval Workflow**:
    *   **Three-Tier Verification**: Mandatory review by **Administrative Officer (AO)**, **Additional Government Agent (AGA)**, and final approval by the **Government Agent (GA)**.
    *   **Conflict Resolution**: Automated checks to prevent double-booking.
*   **Post-Approval**:
    *   **Automated Email Notifications**: Applicants receive instant confirmation upon approval.
    *   **PDF Generation**: Auto-generated **Approval Letters** and **Booking Forms** for official record-keeping.

### 🏠 Government Quarters Management
*   **Family Quarters**:
    *   **Merit-Based Allocation**: Automated scoring system based on Applicant Grade, Salary Key, and Service Duration.
    *   **Eligibility Validation**: Strict checks against defined criteria before application acceptance.
    *   **Ranking System**: Applications are automatically sorted by "Total Marks" to prioritize the most deserving candidates.
*   **Scheduled Quarters**: Dedicated workflow for allocating quarters reserved for specific government posts.
*   **Occupancy Management**: Tracking of current occupants and vacancy status.

### � Internal Communication (Memo System)
*   **Internal Messaging**: Secure interface for staff to send and receive official memos within the system.
*   **Inbox/Outbox Management**: Organized views for received and sent communications.
*   **Action Tracking**: Status updates for memos (Pending, Approved/Agreed, Rejected/Disagreed).
*   **History Management**: Ability to clear read/resolved memos while protecting pending items.

### 🛡️ System Administration
*   **User Management**:
    *   **RBAC**: Granular role assignments (Approvers, Subject Clerks, System Admin).
    *   **Officer Management**: Add, edit, or deactive staff accounts.
*   **Resource Configuration**: Complete control to add or modify details for Halls and Quarters (Capacity, Type, Location).
*   **System Settings**:
    *   **Backup & Restore**: comprehensive tools to backup/restore the entire database or specific tables (Officers, Halls, Memos, etc.).
    *   **Email Configuration**: Tools to test and verify email integration settings.
*   **Audit Logging**:
    *   **Activity Tracking**: Detailed logs of every critical action taken within the system (Logins, Approvals, Edits).
    *   **Transparency**: Viewable by Admins to ensure accountability.

### 📊 Dashboard & Reporting
*   **Unified Dashboard**: Centralized view for Approvers to manage Hall, Family, and Scheduled applications side-by-side.
*   **Smart Navigation**: Fixed side-menu for quick access to different approval sections.
*   **Data Insights**: Row counters, applicant summaries, and status indicators for quick decision-making.

### 📚 Help & Documentation
*   **Multilingual User Manuals**: Comprehensive guides available in **Sinhala, Tamil, and English** to ensure accessibility for all staff members.
*   **System Documentation**: Technical documentation specifically designed for **System Administrators** to assist with maintenance and troubleshooting.