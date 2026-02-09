# Developer Guide: Model Architecture & Relationships

This document outlines the Eloquent models used in the system, their primary roles, and how they relate to each other.

---

## 1. Quarter Application Ecosystem

The Quarter system is spread across several interconnected models to handle different application types and scoring.

### Core Models
- **`QuarterApplication`**: The central entry point for any quarter request.
  - *Primary Key*: `application_id` (String)
  - *Relationships*: 
    - `hasOne(QuarterAllocation)`: Tracks the current status of the application.
    - `hasOne(FamilyQuarterApplication)`: Contains family-specific details.
    - `hasOne(ScheduledQuarterApplication)`: Contains scheduled-specific details.

- **`QuarterAllocation`**: Manages the lifecycle of an application (Pending, Allocated, Rejected).
  - *Relationships*:
    - `belongsTo(QuarterApplication)`: Links back to the requester's data.
    - `belongsTo(Quarter)`: Links to the physical quarter if allocated.

- **`Quarter`**: Represents a physical housing unit.
  - *Primary Key*: `quarter_id` (String)

### Specialized Data Models
- **`FamilyQuarterApplication`**: Stores details like spouse and children information.
- **`MarkingFamilyQuarter`**: Stores the scoring criteria (distance, service years) for a family application.
- **`ScheduledQuarterApplication`**: Stores details like property ownership and priority reasons.

---

## 2. Hall Booking Ecosystem

A simpler two-model structure for managing event venues.

- **`Hall`**: Represents a physical hall or venue.
  - *Primary Key*: `hall_id` (String)
  - *Fields*: `capacity`, `hall_type`, `current_state`.

- **`HallBooking`**: Represents a reservation.
  - *Primary Key*: `booking_id` (String)
  - *Relationships*:
    - `belongsTo(Hall)`: The venue being booked.

---

## 3. User & Permissions

The system uses a custom role/permission layer built into the `User` model.

- **`User`**: The authentication model.
  - *Relationships*:
    - `hasOne(UserPermission)`: Links to a bitmask-style permissions table.
  - *Key Method*: `hasPermissionTo($permission)`: Used throughout controllers to verify if a user (e.g., AO, GA) can perform an action.

- **`UserPermission`**: A table where each column is a specific capability (e.g., `administrative_officer_approval`, `government_agent_approval`).

---

## 4. System Utilities

- **`AuditLog`**: Automatically tracking system actions.
  - *Usage*: Use `AuditLog::create([...])` in controllers after any data mutation.
- **`Memo`**: Internal messaging system enabling communication between different officers (Requester, AO, GA).
- **`GradeSalarySetting` / `MarkingScheme`**: Configuration models that store system-wide settings for scoring and salary grades.

---

## Developer Tips
- **Mass Assignment**: Most models have a `$fillable` array defined. Ensure any new database columns are added there.
- **Timestamps**: Some legacy models use `$timestamps = false` and manage `date_created` / `date_modified` manually. Check the model definition before relying on `created_at`.
- **Primary Keys**: Many models use non-incrementing string IDs (e.g., `application_id`). Always set `$incrementing = false` if adding similar models.
