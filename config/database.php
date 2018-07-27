<?php
return [
  'fetch' => PDO::FETCH_CLASS,
  'default' => 'mysql',
  'connections' => [
    'mysql' => [
      'driver'    => 'mysql',
      'host'      => env('MARIADB_HOST', 'mariadb'),
      'port'      => env('MARIADB_PORT', 3306),
      'database'  => env('MARIADB_DATABASE', 'lagoon'),
      'username'  => env('MARIADB_USER', 'lagoon'),
      'password'  => env('MARIADB_PASSWORD', 'lagoon'),
      'charset'   => env('DB_CHARSET', 'utf8'),
      'collation' => env('DB_COLLATION', 'utf8_unicode_ci'),
      'prefix'    => env('DB_PREFIX', ''),
      'timezone'  => env('DB_TIMEZONE', '+00:00'),
      'strict'    => env('DB_STRICT_MODE', false),
    ],
  ],
  'migrations' => 'migrations',
];
