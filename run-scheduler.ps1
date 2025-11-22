# Laravel Task Scheduler Script for Windows
# This script runs the Laravel scheduler command

# Change to the project directory
$projectPath = "C:\xampp\php\CommuniTAP"
Set-Location $projectPath

# Run the scheduler
php artisan schedule:run

