<?php

return array (
  'office_latitude' => 22.6654,
  'office_longitude' => 88.3845,
  'office_address' => '1st Floor, Yamuna Building, 86, Golaghata Rd, Dakshindari, Kolkata, South Dumdum, West Bengal 700048',
  'max_distance_meters' => 1000,
  'allowed_ip_ranges' => 
  array (
  ),
  'grace_period' => 15,
  'late_fee_amount' => 0.00,
  'half_day_threshold' => 4,
  'half_day_deduction' => 50,
  'monthly_absence_limit' => 2,
  'absence_penalty' => 0.00,
  'auto_absent_threshold' => 2,
  'shift' => 
  array (
    'login_time' => '19:30',
    'logout_time' => '04:00',
  ),
  'notifications' => 
  array (
    'email_notifications' => true,
    'bell_notifications' => true,
    'reminder_minutes_before' => 10,
  ),
  'night_shift' => 
  array (
    'start' => '19:00',
    'end' => '04:00',
    'grace_period_minutes' => 15,
    'auto_logout_minutes' => 30,
  ),
  'location_verification' => 
  array (
    'require_gps' => true,
    'require_ip_check' => false,
    'allow_manual_override' => false,
    'store_location_history' => true,
  ),
  'geolocation' => 
  array (
    'timeout_seconds' => 10,
    'high_accuracy' => true,
    'maximum_age_seconds' => 60,
  ),
);
