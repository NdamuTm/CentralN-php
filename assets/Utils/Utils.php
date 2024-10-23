<?php
// File: Utils.php
// Description: handle all utility functions
// Author: Ndamulelo Rasendedza
// Created: 2024-10-23
// License: MIT License

function getPostById($postId) {
    try {
        // Get the database connection
        $connection = DBUtils::getConnection();

        // Prepare the SQL statement
        $sql = "SELECT * FROM Posts WHERE post_id = :post_id";
        $stmt = $connection->prepare($sql);

        // Bind the post ID parameter
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Fetch the post data
        $post = $stmt->fetch();

        // Check if post exists
        if ($post) {
            return $post;
        } else {
            return null; // No post found
        }
    } catch (PDOException $e) {
        // Handle any database connection errors
        echo 'Database Error: ' . $e->getMessage();
        return null;
    }
}

function getUserById($userId) {
    try {
        // Get the database connection
        $connection = DBUtils::getConnection();

        // Prepare the SQL statement
        $sql = "SELECT * FROM Users WHERE user_id = :user_id";
        $stmt = $connection->prepare($sql);

        // Bind the user ID parameter
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Fetch the user data
        $user = $stmt->fetch();

        // Check if user exists
        if ($user) {
            return $user;
        } else {
            return null; // No user found
        }
    } catch (PDOException $e) {
        // Handle any database connection errors
        echo 'Database Error: ' . $e->getMessage();
        return null;
    }
}


function getPostsBySearchTerm($searchTerm) {
    try {
        // Get the database connection
        $connection = DBUtils::getConnection();

        // Prepare the SQL statement
        $sql = "SELECT * FROM Posts WHERE title LIKE :searchTerm OR content LIKE :searchTerm";
        $stmt = $connection->prepare($sql);

        // Bind the search term parameter with wildcards for partial matching
        $searchTermWithWildcards = '%' . $searchTerm . '%';
        $stmt->bindParam(':searchTerm', $searchTermWithWildcards, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Fetch all matching posts
        $posts = $stmt->fetchAll();

        return $posts; // Return the array of posts
    } catch (PDOException $e) {
        // Handle any database connection errors
        echo 'Database Error: ' . $e->getMessage();
        return [];
    }
}


function getPostsByTags($tags) {
    try {
        // Get the database connection
        $connection = DBUtils::getConnection();

        // Prepare the SQL statement
        $sql = "SELECT * FROM Posts WHERE tags LIKE :tag";
        $stmt = $connection->prepare($sql);

        // Bind the tag parameter with wildcards for partial matching
        $tagWithWildcards = '%' . $tags . '%';
        $stmt->bindParam(':tag', $tagWithWildcards, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Fetch all matching posts
        $posts = $stmt->fetchAll();

        return $posts; // Return the array of posts
    } catch (PDOException $e) {
        // Handle any database connection errors
        echo 'Database Error: ' . $e->getMessage();
        return [];
    }
}



function getEventById($eventId) {
    try {
        // Get the database connection
        $connection = DBUtils::getConnection();

        // Prepare the SQL statement
        $sql = "SELECT * FROM Events WHERE event_id = :eventId";
        $stmt = $connection->prepare($sql);

        // Bind the event ID parameter
        $stmt->bindParam(':eventId', $eventId, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Fetch the event
        $event = $stmt->fetch();

        return $event; // Return the event data
    } catch (PDOException $e) {
        // Handle any database connection errors
        echo 'Database Error: ' . $e->getMessage();
        return null;
    }
}



function getPastEvents() {
    try {
        // Get the database connection
        $connection = DBUtils::getConnection();

        // Prepare the SQL statement
        $sql = "SELECT * FROM Events WHERE date < NOW()";
        $stmt = $connection->prepare($sql);

        // Execute the statement
        $stmt->execute();

        // Fetch all past events
        $events = $stmt->fetchAll();

        return $events; // Return the array of events
    } catch (PDOException $e) {
        // Handle any database connection errors
        echo 'Database Error: ' . $e->getMessage();
        return [];
    }
}


function getFutureEvents() {
    try {
        // Get the database connection
        $connection = DBUtils::getConnection();

        // Prepare the SQL statement
        $sql = "SELECT * FROM Events WHERE date >= NOW()";
        $stmt = $connection->prepare($sql);

        // Execute the statement
        $stmt->execute();

        // Fetch all future events
        $events = $stmt->fetchAll();

        return $events; // Return the array of events
    } catch (PDOException $e) {
        // Handle any database connection errors
        echo 'Database Error: ' . $e->getMessage();
        return [];
    }
}



function getAllUsers() {
    try {
        // Get the database connection
        $connection = DBUtils::getConnection();

        // Prepare the SQL statement
        $sql = "SELECT * FROM Users";
        $stmt = $connection->prepare($sql);

        // Execute the statement
        $stmt->execute();

        // Fetch all users
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch as an associative array

        return $users; // Return the array of users
    } catch (PDOException $e) {
        // Handle any database connection errors
        echo 'Database Error: ' . $e->getMessage();
        return []; // Return an empty array in case of error
    }
}


// Get all friends for the logged-in user
function getFriends($loggedInUserId) {
    $connection = DBUtils::getConnection();
    // Ensure that the column names match your database schema
    $sql = "SELECT u.user_id, u.name FROM Users u
            JOIN Friends f ON (u.user_id = f.friend_id OR u.user_id = f.user_id)
            WHERE (f.user_id = :loggedInUserId OR f.friend_id = :loggedInUserId)
            AND f.status = 'accepted'
            AND u.user_id != :loggedInUserId"; // Exclude the logged-in user from the results
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function checkFriendshipStatus($loggedInUserId, $userId) {
    $connection = DBUtils::getConnection();
    $sql = "SELECT status FROM Friends WHERE (user_id = :loggedInUserId AND friend_id = :userId) OR (user_id = :userId AND friend_id = :loggedInUserId)";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function getPendingRequests($loggedInUserId) {
    $connection = DBUtils::getConnection();
    $sql = "SELECT user_id FROM Friends WHERE friend_id = :loggedInUserId AND status = 'pending'";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
