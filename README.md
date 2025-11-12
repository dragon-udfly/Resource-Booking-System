# 💻 Integrated Resource Management and Allocation System

## Introduction

This is a **Web-Based Resource Management System** designed for the **District Secretariat, Vavuniya**, to modernize and streamline the process of booking official resources. Developed using the **Laravel** framework, this application replaces manual, paper-based forms with a secure, digital platform to enhance efficiency, enforce administrative policies, and eliminate double-booking conflicts.

---

## 🎯 Purpose and Scope

The system's primary goal is to ensure the transparent and efficient allocation of two critical resources:

1.  **Hall Bookings:** Manages the reservation of various halls (Auditorium, Conference, Training) via a **three-step approval workflow** (PA → Branch Head → District Secretary).
2.  **Quarters Allocation:** Facilitates applications for government quarters with an **automated eligibility check** that verifies staff **grade, salary, and service-based constraints** before final allocation by the Admin.

---

## ✨ Key System Features

The core functionality is built around security, control, and automation:

| Feature | Description |
| :--- | :--- |
| **Role-Based Access Control (RBAC)** | Granular permissions are managed by the Admin IT, allowing for customized access (e.g., Staff, PA, Branch Head, District Secretary, Admin). |
| **Approval Dashboard** | Provides approvers (PA, Branch Head, GA) with a centralized view of pending forms, enabling **digital approval/rejection** with timestamped records and PDF download functionality. |
| **Resource Management** | Allows the Admin to set up and modify resource details for all **Halls** and **Quarters**, defining their physical characteristics and eligibility rules. |
| **Audit Logging** | A dedicated feature for the Admin to view and clear detailed records of all user actions for transparency and accountability. |

---

## ⚙️ Technology Stack

| Component | Technology | Role |
| :--- | :--- | :--- |
| **Backend Framework** | **Laravel (PHP)** | Provides the robust MVC structure, routing, and business logic. |
| **Database** | **MariaDB (MySQL)** | Securely stores all user, resource, and transactional data. |
| **Frontend** | **HTML, CSS, JavaScript** | Delivers the user interface and presentation layer. |