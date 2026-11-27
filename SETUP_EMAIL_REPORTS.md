# Email Reports Setup Instructions

## 1. Install Required Package

Run this command in your `tamtam-api` directory:

```bash
composer update
```

This will install the `barryvdh/laravel-dompdf` package for PDF generation.

## 2. Configure Gmail SMTP

### Option A: Using Gmail App Password (Recommended)

1. Go to your Google Account: https://myaccount.google.com/
2. Enable 2-Step Verification if not already enabled
3. Go to: https://myaccount.google.com/apppasswords
4. Create a new App Password for "Mail"
5. Copy the 16-character password

### Option B: Using Gmail with Less Secure Apps (Not Recommended)

If you can't use App Passwords, you'll need to enable "Less secure app access" (not recommended for security).

## 3. Update .env File

Replace the placeholder values in your `.env` file:

```env
# Replace these with your actual Gmail credentials
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com          # Your Gmail address
MAIL_PASSWORD=your-app-password              # Your App Password (16 chars, no spaces)
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-gmail@gmail.com       # Same as MAIL_USERNAME
MAIL_FROM_NAME="${APP_NAME}"

# Email for receiving reports
REPORT_EMAIL=chesteredizon@gmail.com         # Where reports will be sent
```

**Example:**
```env
MAIL_USERNAME=mybusiness@gmail.com
MAIL_PASSWORD=abcd efgh ijkl mnop             # App Password from Google
MAIL_FROM_ADDRESS=mybusiness@gmail.com
REPORT_EMAIL=chesteredizon@gmail.com
```

## 4. Clear Config Cache

After updating `.env`, run:

```bash
php artisan config:clear
```

## 5. Test Email Configuration

You can test if email is working by running this in `tinker`:

```bash
php artisan tinker
```

Then run:

```php
Mail::raw('Test email from TamTam POS', function($message) {
    $message->to('chesteredizon@gmail.com')
            ->subject('Test Email');
});
```

If no errors appear, check your inbox!

## 6. How to Use

### Manual Email Sending:

1. Go to the Reports page in your frontend
2. Select a date range (or use quick buttons: Today, 7 days, 30 days)
3. Click "Generate report"
4. Click "Send to Email" button (appears below "Download CSV")
5. Enter the email address when prompted
6. The system will send a PDF report to that email

### What Gets Sent:

- **Email Subject:** "Sales Report - [from date] to [to date]"
- **Email Body:** Summary with total sales, orders, average order value
- **PDF Attachment:** Complete report with:
  - Summary statistics
  - Top 8 sellers by quantity
  - Daily breakdown (if more than 1 day)

## 7. Future Automation Setup

After confirming the manual button works, we can set up:
- Daily/weekly/monthly automatic reports
- Scheduled Laravel commands
- Cron jobs to send reports automatically

## Troubleshooting

### "Failed to authenticate on SMTP server"
- Double-check your MAIL_USERNAME and MAIL_PASSWORD
- Make sure you're using an App Password, not your regular Gmail password
- Verify 2-Step Verification is enabled on your Google Account

### "Connection could not be established"
- Check your internet connection
- Verify MAIL_HOST=smtp.gmail.com and MAIL_PORT=587
- Try MAIL_PORT=465 with MAIL_ENCRYPTION=ssl

### "Failed to send email"
- Check Laravel logs: `storage/logs/laravel.log`
- Run `php artisan config:clear` after any .env changes
- Verify the PDF library installed correctly with `composer show barryvdh/laravel-dompdf`

### Email sent but not received
- Check spam/junk folder
- Verify MAIL_FROM_ADDRESS matches MAIL_USERNAME
- Try sending to a different email address

## Testing the Feature

1. Make sure your Laravel server is running: `php artisan serve`
2. Make sure your frontend is running
3. Navigate to Reports page
4. Generate a report for any date range
5. Click "Send to Email"
6. Enter your email address
7. Check your inbox (and spam folder)

## Security Notes

- **Never commit your .env file to Git** (it's already in .gitignore)
- App Passwords are safer than your main Gmail password
- Consider using a dedicated email account for sending (not your personal one)
- The REPORT_EMAIL in .env is just the default; users can send to any email via the prompt
