<?php

declare(strict_types=1);

function getTransactionFiles(string $dirPath): array
{
    $files = [];

    foreach (scandir($dirPath) as $file) {
        if (is_dir($file)) {
//            var_dump($file);
            continue;
        }
        $files[] = $dirPath . $file;
    }

    return $files;
}


function getTransactions(string $fileName, ?callable $transactionHandle = null): array
{

    if (!file_exists($fileName)) {
        trigger_error("File not found");
        return [];
    }

    $file = fopen($fileName, 'r');
    fgets($file);

    $transactions = [];

    while (($transaction = fgetcsv($file)) !== false) {
        if($transactionHandle !== null) {
            $transaction = $transactionHandle($transaction);
        }
        $transactions[] = extractTransactions($transaction);
    }
    fclose($file);

//    var_dump($transactions);
    return $transactions;
}


function extractTransactions(array $transactionRow): array
{

    [$date, $checkNumber, $description, $amount] = $transactionRow;

    $amount = (float)str_replace(['$', ','], '', $amount);

    return [
        'date' => $date,
        'checkNumber' => $checkNumber,
        'description' => $description,
        'amount' => $amount,
    ];
}

function calculateTotals(array $transactions): array
{
    $totals = ['netTotal' => 0, 'totalIncome' => 0, 'totalExpense' => 0];

    foreach ($transactions as $transaction) {
        $totals['netTotal'] += $transaction['amount'];

        if ($transaction['amount'] >= 0) {
            $totals['totalIncome'] += $transaction['amount'];
        }else{
            $totals['totalExpense'] += $transaction['amount'];
        }
    }

//    var_dump($totals);
    return $totals;
}