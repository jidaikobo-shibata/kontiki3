<?php
// index.php

// Define the path to the kontiki3.php
define('KONTIKI3_ENTRY', __DIR__ . '/kontiki3/kontiki3.php');

// Check if the kontiki3.php file exists before including
require_once KONTIKI3_ENTRY;
