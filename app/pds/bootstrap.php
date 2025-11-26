<?php
// bootstrap.php

if (!defined('PDS_ENTRY')) {
    die('Access denied');
}

// Environment Configuration
if (file_exists(__DIR__ . '/config_pds/env_pds.php')) {
    require_once __DIR__ . '/config_pds/env_pds.php';
} else {
    die('Configuration file env_pds.php not found. Please copy env_pds.sample.php to env_pds.php and fill in your database credentials.');
}

// Database Connection (Example using PDO)
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Session Start
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Load Libraries
require_once __DIR__ . '/lib/pds_lib.php';

// Global Constants
define('PDS_APP_PATH', __DIR__);
define('PDS_WEB_ROOT', '/dc_html/pds/'); // Example, adjust if necessary
