<?php

// File: 
// Description: 
// Author: Ndamulelo Rasendedza
// Created: 2024-10-22
// License: MIT License

require_once '../assets/Utils/DBUtils.php';
require_once '../assets/Utils/UserUtils.php';
require_once '../assets/Utils/Utils.php';
session_start();

// Fetch the post ID from the request
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: /404.php");
    exit();
}

$postId = (int)$_GET['id'];

$post = getPostById($postId);
$userId = $post['user_id'];
$user = getUserById($userId);
// Update post views
try {
    $conn = DBUtils::getConnection();
    $sql = "UPDATE Posts SET views = views + 1 WHERE post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$postId]);
} catch (PDOException $e) {
    // Handle database error
    echo "Error updating views: " . $e->getMessage();
}

// Format the date
$formattedDate = '';
if (isset($post['date'])) {
    $dateObj = $post['date'];
    try {
        $inputFormat = new DateTime($dateObj);
        $formattedDate = $inputFormat->format('F d, Y \a\t H:i');
    } catch (Exception $e) {
        echo "Error formatting date: " . $e->getMessage();
    }
}





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="/assets/fonts/vodafone/stylesheet.css">
    <link rel="stylesheet" href="/assets/fonts/source-sans-pro/stylesheet.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200..1000&display=swap" rel="stylesheet">
    <title><?= $post['title'] ?> - The • Central • N</title>
</head>
<body class="post-view">
<header>
    <nav>
        <a href="/" class="logo">
             <img src="/assets/images/Logo.png" alt="">
        </a>
        <main>
            <div class="search">
                <form action="/posts.php" method="GET">
                    <input type="text" name="searchterm" placeholder="Type here to search..." spellcheck="false" required>
                    <button class="icon">
                        <img src="/assets/icons/Search Icon.png" alt="Search">
                    </button>
                </form>
            </div>
            <div class="right-info ">
                <div class="message-icon icon">
                    <img src="/assets/icons/message icon.png" alt="">
                </div>
                <div class="notification-icon icon">
                    <img src="/assets/icons/notification none.png" alt="">
                </div>
                <div class="profile">
                    <?php if (isset($_SESSION['userId'])): ?>
                        <?= UserUtils::createUserIcon($_SESSION['userId']) ?>
                        <span><?= UserUtils::getUsernameFromId($_SESSION['userId']) ?></span>
                    <?php else: ?>
                        <a href="/Login.php" class="btn">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </nav>
</header>

<main class="main-content">
    <aside class="sidebar main-container">
        <div class="post-meta-details container">
            <h3>About Post:</h3>
            <img src="<?= $post['image'] ?>" class="post-img" alt="Eduvos Forum Post">
            <div class="meta">
                <h3>Author: <?= $user['name'] ?></h3>
                <p>Posted: <?= $formattedDate ?></p>
                <p>Views: <?= $post['views'] ?></p>
                <p>Likes: <?= $post['likes'] ?></p>
                <p>Comments: <?= $post['comments_count'] ?></p>
            </div>
            <div class="tags">
                <?php
                $tags = explode(",", $post['tags']);
                foreach ($tags as $tag) {
                    echo "<div class='tag'>#" . trim($tag) . "</div>";
                }
                ?>
            </div>
        </div>
    </aside>

    <main class="post-content main-container">
        <div class="post container">
            <div class="post-info">
                <div class="post-heading">
                    <h2><?= $post['title'] ?></h2>
                </div>
                <div class="post-body">
                    <?= $post['content'] ?>
                </div>
            </div>
        </div>
    </main>

    <aside class="right-bar main-container">
        <div class="comments container">
            <h2>Comments</h2>
            <div class="comment-thread">
                <?php
                try {
                    $sql = "SELECT * FROM comments WHERE post_id = ? AND parent_comment_id IS NULL";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$postId]);
                    while ($comment = $stmt->fetch()) {
                        $commentId = $comment['comment_id'];
                        $content = $comment['content'];
                        $userId = $comment['user_id'];
                        $username = UserUtils::getUsernameFromId($userId);
                        ?>
                        <div class="comment" data-comment-id="<?= $commentId ?>">
                            <?= UserUtils::createUserIcon($userId) ?>
                            <div class="comment-details">
                                <h3><?= $username ?></h3>
                                <p><?= $content ?></p>
                                <span class="reply" data-comment-id="<?= $commentId ?>">Reply</span>
                                <div class="replies">
                                    <?php
                                    // Fetch replies
                                    $replySql = "SELECT * FROM comments WHERE parent_comment_id = ?";
                                    $replyStmt = $conn->prepare($replySql);
                                    $replyStmt->execute([$commentId]);
                                    while ($reply = $replyStmt->fetch()) {
                                        $replyContent = $reply['content'];
                                        $replyUserId = $reply['user_id'];
                                        $replyUsername = UserUtils::getUsernameFromId($replyUserId);
                                        ?>
                                        <div class="comment reply-comment">
                                            <?= UserUtils::createUserIcon($replyUserId) ?>
                                            <div class="comment-details">
                                                <h3><?= $replyUsername ?></h3>
                                                <p><?= $replyContent ?></p>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } catch (PDOException $e) {
                    echo "Error fetching comments: " . $e->getMessage();
                }
                ?>
                <form action="/assets/Utils/posting.php" method="post" class="add-comment">
                    <input type="hidden" name="action" value="addComment">
                    <input class="comment-input" type="text" placeholder="Type your reply here..." name="comment">
                    <input type="hidden" name="parent_comment_id" value="">
                    <input type="hidden" name="post_id" value="<?= $postId ?>">
                    <button class="btn">Submit</button>
                </form>
            </div>
        </div>
    </aside>
</main>
<script src="/view-post/script.js"></script>
</body>
</html>
