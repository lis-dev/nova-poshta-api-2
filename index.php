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
// $result = $np->documentsTracking('59000082032106');
// Get cities by name
// $result = $np->getCities(0, 'Андреевка');
// Get region by name
// $result = $np->getArea('Чернігівська', '');
// Get city by name and region
// $result = $np->getCity('Андреевка', 'Запорожье');

// Get result
var_export($result);
