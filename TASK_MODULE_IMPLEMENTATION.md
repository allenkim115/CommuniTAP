# Task Management Module Implementation

## Overview
This document outlines the comprehensive task management module that has been implemented for the Communitap application. The module supports three categories of tasks with different permission levels for users and administrators.

## Task Categories

### 1. Daily Tasks
- **Created by**: Admin only
- **Description**: Recurring tasks that appear daily
- **Status Flow**: Created → Approved → Published → Assigned → Submitted → Completed

### 2. One-Time Tasks (Weekly/Monthly)
- **Created by**: Admin only
- **Description**: Tasks that appear weekly or monthly
- **Status Flow**: Created → Approved → Published → Assigned → Submitted → Completed

### 3. User-Uploaded Tasks
- **Created by**: Regular users
- **Description**: Tasks proposed by users that require admin approval
- **Status Flow**: Created (Pending) → Approved/Rejected → Published → Assigned → Submitted → Completed

## User Permissions Matrix

| Action | User | Admin |
|--------|------|-------|
| Browse Tasks | ✓ | ✓ |
| Join Task | ✓ | ✓ |
| Create Task | ✓ (User-Uploaded only) | ✓ (Daily & One-Time) |
| Create Task Proposal | ✓ (User-Uploaded only) | ✓ (Daily & One-Time) |
| Approve/Reject Task Proposal | ✗ | ✓ |
| Edit Task | ✓ (Own User-Uploaded only) | ✓ (All) |
| Delete Task | ✓ (Own User-Uploaded only) | ✓ (All) |

## Technical Implementation

### Controllers
1. **TaskController** (`app/Http/Controllers/TaskController.php`)
   - Handles user-side task operations
   - CRUD operations for user-uploaded tasks
   - Task joining and submission functionality

2. **AdminTaskController** (`app/Http/Controllers/Admin/TaskController.php`)
   - Handles admin-side task operations
   - Full CRUD operations for all task types
   - Task approval, rejection, publishing, and completion

### Models
1. **Task Model** (`app/Models/Task.php`)
   - Enhanced with new scopes and helper methods
   - Permission checking methods
   - Status management

2. **User Model** (`app/Models/User.php`)
   - Added `isAdmin()` method
   - Added `name` accessor for easier display

### Views
1. **User Views** (`resources/views/tasks/`)
   - `index.blade.php` - Task listing with "My Tasks" and "Available Tasks"
   - `create.blade.php` - Task proposal creation form
   - `edit.blade.php` - Task proposal editing form
   - `show.blade.php` - Task details view

2. **Admin Views** (`resources/views/admin/tasks/`)
   - `index.blade.php` - Admin task management with statistics and filtering
   - `create.blade.php` - Admin task creation form
   - `edit.blade.php` - Admin task editing form
   - `show.blade.php` - Admin task details with action buttons

### Routes
- **User Routes**: `/tasks/*` - All user task operations
- **Admin Routes**: `/admin/tasks/*` - All admin task operations
- **Additional Routes**: Join, submit, approve, reject, publish, complete

## Task Status Flow

```
User-Uploaded Tasks:
Pending → Approved/Rejected → Published → Assigned → Submitted → Completed

Admin-Created Tasks:
Created → Approved → Published → Assigned → Submitted → Completed
```

## Key Features

### For Users
- Browse available tasks
- Join published tasks
- Create task proposals (User-Uploaded only)
- Edit own pending task proposals
- Submit completed tasks for review
- View task details and status

### For Admins
- Create Daily and One-Time tasks
- Approve/reject user task proposals
- Publish approved tasks
- Assign tasks to specific users
- Complete submitted tasks
- Filter tasks by status and type
- View comprehensive task statistics
- Full CRUD operations on all tasks

### Security Features
- Role-based access control
- Task ownership validation
- Status-based action restrictions
- CSRF protection on all forms

## Database Schema
The existing `tasks` table supports all required functionality:
- `taskId` (Primary Key)
- `FK1_userId` (Foreign Key to users)
- `title`, `description`
- `task_type` (daily, one_time, user_uploaded)
- `points_awarded`
- `status` (pending, approved, published, assigned, submitted, completed, rejected)
- `creation_date`, `approval_date`, `due_date`, `published_date`

## Navigation
- Updated navigation to include "Tasks" link for all users
- Admin navigation includes "Tasks" in admin section
- Responsive navigation support

## Points System
- Tasks award points upon completion
- Points are automatically added to user accounts
- Admin can set custom point values for each task

This implementation provides a complete task management system that follows the requirements specified in the task matrix, with proper separation of concerns between user and admin functionality.
