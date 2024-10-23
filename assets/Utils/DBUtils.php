<?php

// File: DBUtils.php
// Description: used for connecting to database
// Author: Ndamulelo Rasendedza
// Created: 2024-10-23
// License: MIT License
class DBUtils {
    private static $dbHost = 'localhost';
    private static $dbName = 'centralN';
    private static $dbUser = 'root';
    private static $dbPass = '';

    public static function getConnection() {
        $dsn = 'mysql:host=' . self::$dbHost . ';dbname=' . self::$dbName;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        return new PDO($dsn, self::$dbUser, self::$dbPass, $options);
    }
}
