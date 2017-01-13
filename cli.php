<?php

error_reporting(E_ALL);

require_once('DbDiff.php');
require('config.php');

$DbDiff = new DbDiff;
$schema1 = array();
$schema2 = array();

if (isset($argv[1]) && isset($argv[2])) {
    foreach ($dbs_config as $key => $config) {
        if ($config['name'] == $argv[1]) {
            $schema1 = $config;
        }
        if ($config['name'] == $argv[2]) {
            $schema2 = $config;
        }
    }
    if (count($schema1) < 1) {
        echo "Database: $argv[1] not found\n";
        exit;
    }
    if (count($schema2) < 1) {
        echo "Database: $argv[2] not found\n";
        exit;
    }
} else {
    echo "Usage: \n";
    echo "php " . $_SERVER['SCRIPT_NAME'] . " database1 database2\n";
    echo "where database1 and database2 is the name of database in config.php \n";
    exit;
}

$result_schema1 = $DbDiff->export($schema1['config'], $schema1['name']);
$result_schema2 = $DbDiff->export($schema2['config'], $schema2['name']);

$result = $DbDiff->compare($result_schema1, $result_schema2);
foreach ($result as $table => $diffs) {
    echo "\nTable \033[31m" . $table . "\033[0m\n";
    foreach ($diffs as $diff) {
        $diff = str_replace("<code>", "\033[31m", $diff);
        $diff = str_replace("</code>", "\033[0m", $diff);
        $diff = str_replace("<em>", "", $diff);
        $diff = str_replace("</em>", "", $diff);
        echo $diff . "\n";
    }
}
