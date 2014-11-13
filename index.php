<?php
// Header information
header('Content-Type: text/html; charset=utf-8');
// Require class file
require_once './src/NovaPoshtaApi2.php';
// Set key
$key = '';
// Create instance
$np = new NovaPoshtaApi2($key);
// Get Track Info
$result = $np->documentsTracking('59000082032106');
// Get result
var_dump($result);