<?php
// dashboard.php

if (!defined('PDS_ENTRY')) {
    die('Access denied.');
}

// The dashboard action's primary role is to display the main navigation view.
// No complex data fetching is needed for this page at the moment.

render_view('dashboard_view');
