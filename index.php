<?php
require 'vendor/autoload.php';

$jsonFile = \JsonMachine\JsonMachine::fromFile('./file.json');
$csv = fopen('file.csv', 'w');

$currentHeader = [];

foreach ($jsonFile as $index => $data) {
  try {
    if(count($currentHeader) === 0 || $currentHeader !== array_keys($data)) {
      $currentHeader = array_keys($data);
      fputcsv($csv, $currentHeader);
    }

    foreach($data as $name => $item) {
      if(is_array($item)) {
        if($name != '_revisions' || $name != 'audit') {
          unset($data[$name]);
        } else {
          $data[$name] = implode(',', $item);
        }
      }
    }

    fputcsv($csv, $data);
    echo "{$index} row imported \n";
  } catch(Exception $e) {
    echo "\n Error {$e->getMessage()}";
  }
}

fclose($csv);