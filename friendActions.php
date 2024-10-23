<?php

// File: friendActions.php
// Description: used for friendactions like adding new friends
// Author: Ndamulelo Rasendedza
// Created: 2024-10-22
// License: MIT License

require_once 'assets/Utils/DBUtils.php';
session_start();

if (!isset($_SESSION['userId'])) {
    header('Location: Login.php');
    exit();
}

$loggedInUserId = $_SESSION['userId'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $friendId = $_POST['friendId'];
    $action = $_POST['action'];

    try {
        // Get database connection
        $connection = DBUtils::getConnection();

        // Handle different actions: add, remove, accept, reject
        if ($action === 'add') {
            // Add a new friend request (status = pending)
            $sql = "INSERT INTO Friends (user_id, friend_id, status) VALUES (:loggedInUserId, :friendId, 'pending')";
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
            $stmt->bindParam(':friendId', $friendId, PDO::PARAM_INT);
            $stmt->execute();
        } elseif ($action === 'remove') {
            try {
                // Start a transaction for safety in case of multiple queries
                $connection->beginTransaction();
        
                // SQL to remove an existing friend from both directions
                $sql = "DELETE FROM Friends 
                        WHERE (user_id = :loggedInUserId AND friend_id = :friendId)
                        OR (user_id = :friendId AND friend_id = :loggedInUserId)";
        
                // Prepare the statement
                $stmt = $connection->prepare($sql);
        
                // Bind the logged-in user ID and friend ID to the query
                $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
                $stmt->bindParam(':friendId', $friendId, PDO::PARAM_INT);
        
                // Execute the query
                $stmt->execute();
        
                // Commit the transaction
                $connection->commit();
        
                // Provide a success message or redirect
                echo 'Friend removed successfully.';
            } catch (PDOException $e) {
                // If there's an error, roll back the transaction
                $connection->rollBack();
                echo 'Error removing friend: ' . $e->getMessage();
            }
        } elseif ($action === 'cancel') {
            // Cancel a friend request (status = pending)
            $sql = "DELETE FROM Friends WHERE user_id = :loggedInUserId AND friend_id = :friendId AND status = 'pending'";
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
            $stmt->bindParam(':friendId', $friendId, PDO::PARAM_INT);
            $stmt->execute();
        }if ($action === 'accept') {
            try {
                $connection->beginTransaction();
        
                // Step 1: Update the status of the existing friend request to 'accepted'
                $sql = "UPDATE Friends SET status = 'accepted' WHERE user_id = :friendId AND friend_id = :loggedInUserId AND status = 'pending'";
                $stmt = $connection->prepare($sql);
                $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
                $stmt->bindParam(':friendId', $friendId, PDO::PARAM_INT);
                $stmt->execute();
        
                // Step 2: Insert the reverse relationship (make the friendship bidirectional) i could only see from the requesting side
                $sql = "INSERT INTO Friends (user_id, friend_id, status) VALUES (:loggedInUserId, :friendId, 'accepted')";
                $stmt = $connection->prepare($sql);
                $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
                $stmt->bindParam(':friendId', $friendId, PDO::PARAM_INT);
                $stmt->execute();
        
                $connection->commit();
            } catch (PDOException $e) {
                $connection->rollBack(); // Roll back if something went wrong
                echo 'Error: ' . $e->getMessage();
            }
        } elseif ($action === 'reject') {
            // Reject a friend request (delete the request)
            $sql = "DELETE FROM Friends WHERE user_id = :friendId AND friend_id = :loggedInUserId AND status = 'pending'";
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
            $stmt->bindParam(':friendId', $friendId, PDO::PARAM_INT);
            $stmt->execute();
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

header('Location: /My-Friends'); 
