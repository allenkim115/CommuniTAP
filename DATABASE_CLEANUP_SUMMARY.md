# Database Cleanup Summary

This document summarizes the database refactoring performed to clean up the ERD and remove unnecessary tables and columns.

## Latest Update: Roles Simplification

### Tables Removed (Latest)

### 3. `roles` (Dropped)
- **Reason**: Replaced by simple ENUM in users table
- **Status**: Unused in codebase - application uses direct role column
- **Migration**: `2025_11_28_232450_remove_roles_tables_and_convert_role_to_enum.php`

### 4. `user_roles` (Dropped)
- **Reason**: No longer needed with simplified role system
- **Status**: Junction table for many-to-many relationship that was never used
- **Migration**: `2025_11_28_232450_remove_roles_tables_and_convert_role_to_enum.php`

### Columns Modified (Latest)

### 2. `users.role` (VARCHAR → ENUM)
- **Before**: `VARCHAR(30)` with default 'user'
- **After**: `ENUM('user', 'admin')` with default 'user'
- **Reason**: Simplifies role management, ensures data integrity
- **Migration**: `2025_11_28_232450_remove_roles_tables_and_convert_role_to_enum.php`

## Tables Removed

### 1. `task_assignment` (Dropped)
- **Reason**: Replaced by the enhanced `task_assignments` table
- **Status**: Unused in codebase - all references use `task_assignments`
- **Migration**: `2025_11_28_232205_cleanup_unused_tables_and_columns.php`

### 2. `reports` (Dropped)
- **Reason**: Replaced by `user_incident_reports` table which provides more specific functionality
- **Status**: Unused in codebase - all incident reporting uses `user_incident_reports`
- **Migration**: `2025_11_28_232205_cleanup_unused_tables_and_columns.php`

## Columns Removed

### 1. `rewards.image_url` (Dropped)
- **Reason**: Redundant - `image_path` column is sufficient for storing image references
- **Status**: Removed to simplify schema
- **Migration**: `2025_11_28_232205_cleanup_unused_tables_and_columns.php`

## Final Clean Database Schema

### Core Business Tables (for ERD)

1. **USERS** - User accounts (with `role` as ENUM('user', 'admin'))
2. **TASKS** - Task definitions
3. **TASK_ASSIGNMENTS** - Task assignments (enhanced version)
4. **TASK_SUBMISSION** - Task submission records
5. **FEEDBACK** - User feedback on tasks
6. **TAP_NOMINATIONS** - Tap & Pass nominations
7. **REWARDS** - Available rewards (without `image_url`)
8. **REWARD_REDEMPTION** - Reward redemption records
9. **NOTIFICATIONS** - User notifications
10. **POINTS_HISTORY** - Points transaction history
11. **USER_INCIDENT_REPORTS** - Incident reporting system

**Note**: Roles are now simplified to an ENUM column in the USERS table, removing the need for separate ROLES and USER_ROLES tables.

### Laravel System Tables (Not in ERD)
These are framework tables and should not appear in the ERD:
- `cache`
- `cache_locks`
- `failed_jobs`
- `jobs`
- `job_batches`
- `migrations`
- `password_reset_tokens`
- `personal_access_tokens`
- `sessions`

## Running the Migration

To apply these changes to your database:

```bash
php artisan migrate
```

To rollback if needed:

```bash
php artisan migrate:rollback
```

## ERD Updates Required

After this cleanup, your ERD should:

1. **Remove**:
   - `TASK_ASSIGNMENT` entity (old version)
   - `REPORTS` entity (generic version)
   - `ROLES` entity (replaced by ENUM in USERS)
   - `USER_ROLES` entity (junction table no longer needed)

2. **Keep/Add**:
   - `TASK_ASSIGNMENTS` entity (enhanced version with progress tracking)
   - `USER_INCIDENT_REPORTS` entity (specific incident reporting)

3. **Update**:
   - `REWARDS` entity: Remove `image_url` attribute, keep only `image_path`
   - `USERS` entity: Change `role` attribute from VARCHAR(30) to ENUM('user', 'admin')

## Notes

- All foreign key constraints are preserved
- No data loss for active tables
- The cleanup only removes unused/redundant structures
- Laravel timestamps (`created_at`, `updated_at`) are kept as they are standard practice

