<?php
// FILE: /core/db_connect.php

// Enable error reporting for debugging. Should be turned off in production.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define global constants for the Supabase API connection.
define('SUPABASE_URL', 'https://vlnrqdljzlnuakuvwppy.supabase.co');
define('SUPABASE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InZsbnJxZGxqemxudWFrdXZ3cHB5Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc1NDE3Njk1MCwiZXhwIjoyMDY5NzUyOTUwfQ.aNLaoVaFXrf5km0j4P89ppsBNnN3S0vcklAYFeZ7ogk');

// Start the session if it hasn't been started already.
// This is crucial for keeping users logged in across different pages.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}