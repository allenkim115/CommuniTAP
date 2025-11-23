# Laravel Task Scheduler Setup for Windows

## Development (Recommended for Testing)

Run the scheduler in the background using:

```powershell
php artisan schedule:work
```

This will keep running and execute scheduled tasks automatically. Press `Ctrl+C` to stop it.

## Production Setup (Windows Task Scheduler)

### Option 1: Using Task Scheduler GUI

1. Open **Task Scheduler** (search for it in Windows)
2. Click **Create Basic Task**
3. Name it: "Laravel Task Scheduler"
4. Trigger: **Daily** (or choose your preference)
5. Start time: Set to current time or when you want it to start
6. Action: **Start a program**
7. Program/script: `C:\xampp\php\php.exe`
8. Add arguments: `artisan schedule:run`
9. Start in: `C:\xampp\php\CommuniTAP`
10. Check **Open the Properties dialog** and click Finish
11. In Properties:
    - **General tab**: Check "Run whether user is logged on or not"
    - **Triggers tab**: Edit the trigger and set it to **Repeat task every: 5 minutes** for a duration of **Indefinitely**
    - **Settings tab**: Check "Allow task to be run on demand" and "Run task as soon as possible after a scheduled start is missed"

### Option 2: Using PowerShell Script

1. Use the provided `run-scheduler.ps1` script
2. Create a scheduled task that runs this PowerShell script every 5 minutes

### Option 3: Manual Command (For Testing)

Run this command manually every 5 minutes, or set up a Windows scheduled task:

```powershell
cd C:\xampp\php\CommuniTAP
php artisan schedule:run
```

## Verify It's Working

To test if the scheduler is working:

1. Check the logs: `storage/logs/laravel.log`
2. Run manually: `php artisan tasks:process-deadlines`
3. Check the output for any errors

## Important Notes

- The scheduler needs to run **every 5 minutes** to check for tasks approaching deadlines
- Make sure PHP is in your system PATH, or use the full path to PHP
- For production, use Windows Task Scheduler to ensure it runs automatically
- The scheduler command (`tasks:process-deadlines`) will:
  - Send notifications 1 hour before task deadlines
  - Mark expired tasks as uncompleted

