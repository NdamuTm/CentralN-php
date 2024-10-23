<?php

// File: Userutils.php
// Description: used for all things related to the user
// Author: Ndamulelo Rasendedza
// Created: 2024-10-23
// License: MIT License

class UserUtils {
    public static function getUsernameFromId($userId) {
        try {
            $connection = DBUtils::getConnection();
            $stmt = $connection->prepare("SELECT name FROM Users WHERE user_id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            return $user['name'] ?? 'User';
        } catch (PDOException $e) {
            return 'Error retrieving username';
        }
    }

    public static function createUserIcon($userId) {
        try {
            $connection = DBUtils::getConnection();
            $stmt = $connection->prepare("SELECT name FROM Users WHERE user_id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            $initial = strtoupper($user['name'][0]);
            return "<a href='/Profile' class='user-icon icon'><h1>{$initial}</h1></a>";
        } catch (PDOException $e) {
            return "<div class='user-icon icon'><h1>?</h1></div>";
        }
    }
}
