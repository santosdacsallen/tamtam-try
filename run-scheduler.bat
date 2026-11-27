@echo off
echo ============================================
echo TamTam POS - Scheduler Runner
echo ============================================
echo.
echo Running Laravel Scheduler...
echo This will check if any scheduled tasks need to run.
echo.
php artisan schedule:run
echo.
echo Done! Check above for any executed tasks.
echo.
pause
