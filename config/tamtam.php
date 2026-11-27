<?php

return [

    // "Today", "this week" and the day totals in reports follow the stall's clock,
    // not the server's. Orders themselves are always stored in UTC.
    'report_timezone' => env('REPORT_TIMEZONE', 'Asia/Manila'),

    // Email address to receive automated reports
    'report_email' => env('REPORT_EMAIL', 'chesteredizon@gmail.com'),

];
