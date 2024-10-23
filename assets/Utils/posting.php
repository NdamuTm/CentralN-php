<?php

// File: posting.php
// Description: used for handling posting and likes etc
// Author: Ndamulelo Rasendedza
// Created: 2024-10-23
// License: MIT License
session_start();
require_once 'DButils.php';
define('UPLOAD_DIRECTORY', '/uploads');
define('IMAGE_BASE_URL', 'http://localhost/uploads/');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    switch ($action) {
        case 'createPost':
            handleCreatePost($_POST, $_FILES['myFile']);
            break;
        case 'addComment':
            handleAddComment($_POST);
            break;
        case 'addReply':
            handleAddReply($_POST);
            break;
        case 'deleteComment':
            handleDeleteComment($_POST);
            break;
        case 'likePost':
            handleLikePost($_POST);
            break;
    }
}

function handleCreatePost($request, $file) {
    $title = $request['title'];
    $tags = $request['tags'];
    $content = $request['content'];
    $userId = $_SESSION['userId'];
    $imageUrl = uploadImage($file);

    $connection = DBUtils::getConnection();
    $sql = "INSERT INTO Posts (title, tags, content, image, user_id) VALUES (?, ?, ?, ?, ?)";
    $statement = $connection->prepare($sql);

    try {
        $statement->execute([$title, $tags, $content, $imageUrl, $userId]);
        header("Location: /"); // Redirect to the list of posts
    } catch (PDOException $e) {
        error_log($e->getMessage());
        header("Location: /error.php");
    }
}

function handleAddComment($request) {
    $postId = $request['post_id'];
    $userId = $_SESSION['userId'];
    $content = $request['comment'];

    $connection = DBUtils::getConnection();
    $sql = "INSERT INTO Comments (post_id, user_id, content) VALUES (?, ?, ?)";
    $statement = $connection->prepare($sql);

    try {
        $statement->execute([$postId, $userId, $content]);

        // Update comments_count in Posts table
        $sql2 = "UPDATE Posts SET comments_count = comments_count + 1 WHERE post_id = ?";
        $statement2 = $connection->prepare($sql2);
        $statement2->execute([$postId]);

        header("Location: /view-post?id=" . $postId); // Redirect back to the post page
    } catch (PDOException $e) {
        error_log($e->getMessage());
        header("Location: /error.php");
    }
}

function handleAddReply($request) {
    $postId = $request['post_id'];
    $userId = $_SESSION['userId'];
    $parentCommentId = $request['parent_comment_id'];
    $content = $request['comment'];

    $connection = DBUtils::getConnection();
    $sql = "INSERT INTO Comments (post_id, user_id, content, parent_comment_id) VALUES (?, ?, ?, ?)";
    $statement = $connection->prepare($sql);

    try {
        $statement->execute([$postId, $userId, $content, $parentCommentId]);
        header("Location: /view-post?id=" . $postId); // Redirect back to the post page
    } catch (PDOException $e) {
        error_log($e->getMessage());
        header("Location: /error.php");
    }
}

function handleDeleteComment($request) {
    $commentId = $request['commentId'];
    $postId = $request['post_id'];

    $connection = DBUtils::getConnection();
    $sql = "DELETE FROM Comments WHERE comment_id = ?";
    $statement = $connection->prepare($sql);

    try {
        $statement->execute([$commentId]);
        header("Location: /view-post?id=" . $postId);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        header("Location: /error.php");
    }
}

function handleLikePost($request) {
    $postId = $request['post_id'];
    $userId = $_SESSION['userId'];

    if (!$userId) {
        header("Location: /Login");
        return;
    }

    $connection = DBUtils::getConnection();

    // Check if user already liked the post
    $sqlCheck = "SELECT COUNT(*) FROM Likes WHERE post_id = ? AND user_id = ?";
    $statementCheck = $connection->prepare($sqlCheck);
    $statementCheck->execute([$postId, $userId]);
    $existingLikes = $statementCheck->fetchColumn();

    if ($existingLikes == 0) {
        // Like the post
        $sqlInsert = "INSERT INTO Likes (post_id, user_id) VALUES (?, ?)";
        $statementInsert = $connection->prepare($sqlInsert);
        $statementInsert->execute([$postId, $userId]);

        // Update likes count
        $sqlUpdate = "UPDATE Posts SET likes = likes + 1 WHERE post_id = ?";
        $statementUpdate = $connection->prepare($sqlUpdate);
        $statementUpdate->execute([$postId]);

        $_SESSION['hasLikedPost' . $postId] = true;
    } else {
        // Unlike the post
        $sqlDelete = "DELETE FROM Likes WHERE post_id = ? AND user_id = ?";
        $statementDelete = $connection->prepare($sqlDelete);
        $statementDelete->execute([$postId, $userId]);

        // Update likes count
        $sqlUpdate = "UPDATE Posts SET likes = likes - 1 WHERE post_id = ?";
        $statementUpdate = $connection->prepare($sqlUpdate);
        $statementUpdate->execute([$postId]);

        $_SESSION['hasLikedPost' . $postId] = false;
    }

    header("Location: /");
}

function uploadImage($file) {
    if ($file['error'] != UPLOAD_ERR_OK) {
        return null; 
    }

    $fileName = basename($file['name']);
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . UPLOAD_DIRECTORY;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filePath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;
    move_uploaded_file($file['tmp_name'], $filePath);

    return IMAGE_BASE_URL . $fileName;
}

