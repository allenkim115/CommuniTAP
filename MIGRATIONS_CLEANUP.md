# Migration Files Cleanup

## Removed Migration Files

The following migration files have been removed as they create tables/columns that are being dropped:

### 1. `0001_01_01_000008_create_task_assignment_table.php`
- **Reason**: Creates `task_assignment` table which is being dropped
- **Replaced by**: `task_assignments` table (created in `2025_09_06_142246_create_task_assignments_table.php`)

### 2. `0001_01_01_000010_create_reports_table.php`
- **Reason**: Creates `reports` table which is being dropped
- **Replaced by**: `user_incident_reports` table (created in `2025_10_03_100305_create_user_incident_reports_table.php`)

### 3. `0001_01_01_000004_create_roles_table.php`
- **Reason**: Creates `roles` table which is being dropped
- **Replaced by**: ENUM column in `users` table

### 4. `0001_01_01_000005_create_user_roles_table.php`
- **Reason**: Creates `user_roles` table which is being dropped
- **Replaced by**: ENUM column in `users` table

### 5. `2025_10_06_000000_add_image_url_to_rewards_table.php`
- **Reason**: Adds `image_url` column which is being removed
- **Replaced by**: `image_path` column only (added in `2025_10_06_000001_add_image_path_to_rewards_table.php`)

### 6. `2025_09_06_143740_add_photos_to_task_assignments.php`
- **Reason**: Empty migration file (does nothing)
- **Replaced by**: `2025_09_06_143750_add_photos_to_task_assignments.php` (actual implementation)

## Important Notes

⚠️ **If these migrations have already been run in your database**, you may need to manually remove their entries from the `migrations` table:

```sql
DELETE FROM migrations WHERE migration IN (
    '0001_01_01_000008_create_task_assignment_table',
    '0001_01_01_000010_create_reports_table',
    '0001_01_01_000004_create_roles_table',
    '0001_01_01_000005_create_user_roles_table',
    '2025_10_06_000000_add_image_url_to_rewards_table',
    '2025_09_06_143740_add_photos_to_task_assignments'
);
```

However, this is **only necessary if**:
- You've already run these migrations in production
- You want to clean up the migrations table
- You're doing a fresh database setup

For new installations, these files can simply be deleted (as done).

## Result

Your migration folder is now cleaner and only contains migrations for tables/columns that are actually used in the application.

