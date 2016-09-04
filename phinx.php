<?php

$db = array();

$ret = array(
    "paths" => array(
        "migrations" => "migrations"
    ),
    "environments" => array(
        "default_migration_table" => "phinxlog",
        "default_database" => "development"
    )
);

$app = require dirname(__FILE__).'/app/config/settings.php';
$s = $app['settings'];

if (isset($s['db'])) {
    $ret['environments']['development'] = array(
        "adapter" => "mysql",
        "host" => $s['db']['host'],
        "name" => $s['db']['dbname'],
        "user" => $s['db']['user'],
        "pass" => $s['db']['pass'],
        "port" => 3306,
        "collation" => 'utf8mb4_unicode_ci'
    );
}

return $ret;