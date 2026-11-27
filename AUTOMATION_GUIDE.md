# 🤖 Monthly Report Automation Guide

## ✅ What's Been Set Up

Your system now has **automated monthly report emails** with two modes:

### 🧪 **TEST MODE** (Currently Active)
- Sends report **every 2 minutes**
- Uses **last 7 days** of data
- For testing the automation

### 🚀 **PRODUCTION MODE** (Ready to activate)
- Sends report **1st of every month at 00:00 (midnight)**
- Uses **previous calendar month** (e.g., Nov 1-30 sent on Dec 1st)
- For actual monthly reports

---

## 📋 **Testing the Automation (Do This Now)**

### **Method 1: Run Scheduler Manually** ⭐ Easiest

1. **Double-click this file:**
   ```
   c:\Projectstrial\tamtam-api\run-scheduler.bat
   ```

2. **What you'll see:**
   - If a task ran: "Running scheduled command: report:send-monthly --test"
   - If nothing to run: "No scheduled commands are ready to run."

3. **Check your email:**
   - Wait 1-2 minutes
   - Check: `devmaycry2000@gmail.com`
   - Should receive: "Sales Report - [dates]"

4. **Repeat every 2 minutes:**
   - Run the batch file again
   - You should get a new email every 2 minutes

---

### **Method 2: Windows Task Scheduler** (For Continuous Testing)

#### **Setup (5 minutes):**

1. **Open Task Scheduler:**
   - Press `Windows Key`
   - Type: `Task Scheduler`
   - Press Enter

2. **Create New Task:**
   - Click "Create Basic Task..." (right sidebar)
   - Name: `TamTam Report Scheduler`
   - Description: `Runs Laravel scheduler every minute`
   - Click "Next"

3. **Trigger:**
   - Select: "When I log on"
   - Click "Next"

4. **Action:**
   - Select: "Start a program"
   - Click "Next"

5. **Program Settings:**
   - **Program/script:**
     ```
     C:\php\php.exe
     ```
     (Adjust path if your PHP is elsewhere. Find it with: `where php` in PowerShell)
   
   - **Add arguments:**
     ```
     artisan schedule:run
     ```
   
   - **Start in:**
     ```
     C:\Projectstrial\tamtam-api
     ```

6. **Finish:**
   - Check "Open Properties when I click Finish"
   - Click "Finish"

7. **In Properties Window:**
   - Go to **"Triggers"** tab
   - Click **"Edit"**
   - Check **"Repeat task every"**: `1 minute`
   - Duration: `Indefinitely`
   - Click "OK"
   - Click "OK" again

8. **Test It:**
   - Right-click the task
   - Click "Run"
   - Check logs: `c:\Projectstrial\tamtam-api\storage\logs\monthly-report.log`

---

## 📧 **What You'll Receive**

**Email Subject:**
```
Sales Report - 2026-11-20 to 2026-11-26
```

**Email Content:**
- Summary (Sales, Orders, Average)
- Note: "This is a TEST report (last 7 days)"

**PDF Attachment:**
- Complete sales report
- Top sellers
- Daily breakdown

---

## 🔄 **Switching from TEST to PRODUCTION Mode**

Once you've confirmed emails are arriving every 2 minutes:

### **Step 1: Edit Schedule File**

Open: `c:\Projectstrial\tamtam-api\routes\console.php`

**Find these lines:**
```php
// TEST MODE: Send report every 2 minutes (for testing)
Schedule::command('report:send-monthly --test')
    ->everyTwoMinutes()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/monthly-report.log'));

// PRODUCTION MODE: Send monthly report on 1st of every month at 00:00
// Schedule::command('report:send-monthly')
//     ->monthlyOn(1, '00:00')
//     ->timezone('Asia/Manila')
//     ->withoutOverlapping()
//     ->appendOutputTo(storage_path('logs/monthly-report.log'));
```

**Change to:**
```php
// TEST MODE: Send report every 2 minutes (for testing)
// Schedule::command('report:send-monthly --test')
//     ->everyTwoMinutes()
//     ->withoutOverlapping()
//     ->appendOutputTo(storage_path('logs/monthly-report.log'));

// PRODUCTION MODE: Send monthly report on 1st of every month at 00:00
Schedule::command('report:send-monthly')
    ->monthlyOn(1, '00:00')
    ->timezone('Asia/Manila')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/monthly-report.log'));
```

**What Changed:**
- Added `//` to comment out TEST mode (lines 2-5)
- Removed `//` from PRODUCTION mode (lines 8-12)

### **Step 2: Save the File**

That's it! Now it will run on the 1st of every month at midnight.

---

## 🌐 **For Production (Computer Not Always On)**

Since your computer isn't always running, you'll need an **external cron service**:

### **Option A: cron-job.org** (Recommended - Free)

1. **Sign up:** https://cron-job.org/en/
2. **Create new cron job:**
   - Title: `TamTam Monthly Report`
   - URL: `https://your-domain.com/api/v1/cron/run-scheduler`
   - Schedule: `0 0 1 * *` (every 1st at midnight)

3. **Protect the endpoint** (we'll add this):
   - Add secret token verification
   - Only allow requests from cron-job.org

### **Option B: Deploy to Cloud Server**

Deploy to:
- **AWS EC2** (always running)
- **DigitalOcean** ($5/month)
- **Railway.app** (free tier)
- **Heroku** (free tier with scheduler)

Then set up cron there.

---

## 🎯 **Manual Commands**

You can always send reports manually:

### **Send Test Report (Last 7 Days):**
```bash
php artisan report:send-monthly --test
```

### **Send Monthly Report (Previous Month):**
```bash
php artisan report:send-monthly
```

### **Send to Different Email:**
Edit `.env`:
```env
REPORT_EMAIL=different-email@example.com
```

Then run the command.

---

## 📊 **Schedule Options (Future)**

You can add more automated reports by adding to `console.php`:

### **Weekly Report (Every Monday at 9 AM):**
```php
Schedule::command('report:send-weekly')
    ->weeklyOn(1, '09:00')
    ->timezone('Asia/Manila');
```

### **Daily Report (Every Night at 11 PM):**
```php
Schedule::command('report:send-daily')
    ->dailyAt('23:00')
    ->timezone('Asia/Manila');
```

### **End of Day (Midnight):**
```php
Schedule::command('report:send-eod')
    ->dailyAt('00:00')
    ->timezone('Asia/Manila');
```

---

## 📁 **Log Files**

Check automation logs:

**Monthly Report Log:**
```
c:\Projectstrial\tamtam-api\storage\logs\monthly-report.log
```

**Laravel General Log:**
```
c:\Projectstrial\tamtam-api\storage\logs\laravel.log
```

---

## 🐛 **Troubleshooting**

### **"No scheduled commands are ready to run"**
- Normal! Means nothing is scheduled for this minute
- In TEST mode, run again after 2 minutes
- In PRODUCTION mode, will only run on 1st of month

### **Task runs but no email**
- Check: `storage/logs/monthly-report.log`
- Check: `storage/logs/laravel.log`
- Verify: `.env` has `REPORT_EMAIL` set
- Test manually: `php artisan report:send-monthly --test`

### **"Command not found"**
- Run: `php artisan list` to see all commands
- Should see: `report:send-monthly`
- If not, check: `app/Console/Commands/SendMonthlyReport.php` exists

### **Windows Task Scheduler not working**
- Check task is "Enabled"
- Check trigger is set to repeat every 1 minute
- Check "Last Run Result" should be "0x0" (success)
- Check "Start in" path is correct

---

## 📞 **Need Help?**

1. Check log files first (see above)
2. Run manual command to test
3. Verify email configuration in `.env`
4. Check if PHP is in your system PATH

---

## ✅ **Quick Checklist**

- [ ] Run `run-scheduler.bat` and get email within 2 minutes
- [ ] Verify email arrives at `devmaycry2000@gmail.com`
- [ ] Check PDF attachment opens correctly
- [ ] Review data in PDF matches your POS data
- [ ] Set up Windows Task Scheduler (or external cron)
- [ ] Switch to PRODUCTION mode when ready
- [ ] Plan for cloud deployment for 24/7 automation

---

**Current Status:** 🧪 TEST MODE (every 2 minutes)  
**Next Step:** Test by running `run-scheduler.bat`  
**After Confirmation:** Switch to PRODUCTION mode (see above)
