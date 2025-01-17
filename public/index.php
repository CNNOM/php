<?php

declare(strict_types = 1); // Строгая типизация

$root = dirname(__DIR__).DIRECTORY_SEPARATOR; // Корневая деректива

define('APP_PAH', $root.'app'.DIRECTORY_SEPARATOR);
define('FILES_PAH', $root.'transaction_files'.DIRECTORY_SEPARATOR);
define('VIEWS_PAH', $root.'views'.DIRECTORY_SEPARATOR);


require APP_PAH.'App.php';
require APP_PAH.'helpers.php';

$files = getTransactionFiles(FILES_PAH);

$transactions = [];
foreach ($files as $file) {
    $transactions = array_merge($transactions, getTransactions($file));
}
$totals = calculateTotals($transactions);

require VIEWS_PAH.'transactions.php';