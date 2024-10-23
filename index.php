<?php
// File: index.php
// Description: used to display homepage
// Author: Ndamulelo Rasendedza
// Created: 2024-10-23
// License: MIT License


include_once 'assets/Utils/DBUtils.php';
include_once 'assets/Utils/UserUtils.php';

session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="/assets/fonts/vodafone/stylesheet.css">
    <link rel="stylesheet" href="/assets/fonts/source-sans-pro/stylesheet.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200..1000&display=swap" rel="stylesheet">
    
    <title>The • Central • N</title>
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <img src="/assets/images/Logo.png" alt="Logo">
            </div>
            <main>
                <div class="search">
                    <form action="/posts" method="GET">
                        <input type="text" name="searchterm" placeholder="Type here to search..." spellcheck="false" required>
                        <input type="hidden" name="type" value="search">
                        <button class="icon">
                            <img src="/assets/icons/Search Icon.png" alt="Search">
                        </button>
                    </form>
                </div>
                <div class="right-info">
                    <div class="message-icon icon">
                        <img src="/assets/icons/message icon.png" alt="Messages">
                    </div>
                    <div class="notification-icon icon">
                        <img src="/assets/icons/notification none.png" alt="Notifications">
                    </div>
                    <div class="profile">
                        <?php
                        if (isset($_SESSION['userId'])) {
                            echo UserUtils::createUserIcon($_SESSION['userId']);
                        ?>
                            <span>
                                <?= UserUtils::getUsernameFromId($_SESSION['userId']) ?>
                            </span>
                        <?php } else { ?>
                            <a href="Login" class="btn">Sign Up</a>
                        <?php } ?>
                    </div>
                </div>
            </main>
        </nav>
    </header>

    <main class="main-content">
        <aside class="sidebar main-container">
        <div class="sidebar-tag container">
            <div class="posts-group">
                <div class="icon">
                    <img src="/assets/icons/new icon.png" alt="">
                </div>
                <div class="title">
                    <h3>General</h3>
                    <span>Talk about anything Eduvos-related</span>
                </div>
            </div>
            <div class="posts-group">
                <div class="icon">
                    <img src="/assets/icons/popular icon.png" alt="">
                </div>
                <div class="title">
                    <h3>Course Help</h3>
                    <span>Get help with your coursework</span>
                </div>
            </div>
            <a href="/users" class="posts-group">
                <div class="icon">
                    <img src="/assets/icons/following.png" alt="">
                </div>
                <div class="title">
                    <h3>All Users</h3>
                    <span>View all users</span>
                </div>
                
            </a>
            <a href="/My-friends" class="posts-group">
                <div class="icon">
                    <img src="/assets/icons/following.png" alt="">
                </div>
                <div class="title">
                    <h3>My Friends</h3>
                    <span>View all your friends</span>
                </div>
            </a>
        </div>
        <div class="popular-tags container">
            <h2>Popular Tags</h2>
            <a href="/posts?searchterm=coding&type=tags" class="popular-tag">
                <div class="icon" style="background-color: #5A4F43;">
                    <img src="/assets/icons/close-code.png" alt="">
                </div>
                <div class="title">
                    <h3>#coding</h3>
                    <span>Share code snippets and projects</span>
                </div>
            </a>
            <a href="/posts?searchterm=mathematics&type=tags" class="popular-tag">
                <div class="icon" style="background-color: #473E3B;">
                    <img src="/assets/icons/dollar-sign.png" alt="">
                </div>
                <div class="title">
                    <h3>#mathematics</h3>
                    <span>Get help with math problems</span>
                </div>
            </a>
            <a href="/posts?searchterm=design&type=tags" class="popular-tag">
                <div class="icon" style="background-color: #444F5F;">
                    <img src="/assets/icons/design icon.png" alt="">
                </div>
                <div class="title">
                    <h3>#design</h3>
                    <span>Discuss creative designs and projects</span>
                </div>
            </a>
            <a href="/posts?searchterm=innovation&type=tags" class="popular-tag">
                <div class="icon" style="background-color: #574D42;">
                    <img src="/assets/icons/pen-writing.png" alt="">
                </div>
                <div class="title">
                    <h3>#innovation</h3>
                    <span>Explore new ideas and breakthroughs</span>
                </div>
            </a>
            <a href="/posts?searchterm=tutorial&type=tags" class="popular-tag">
                <div class="icon" style="background-color: #335248;">
                    <img src="/assets/icons/tutorial.png" alt="">
                </div>
                <div class="title">
                    <h3>#tutorials</h3>
                    <span>Find and share educational resources</span>
                </div>
            </a>
            <a href="/posts?searchterm=business&type=tags" class="popular-tag">
                <div class="icon" style="background-color: #46475B;">
                    <img src="/assets/icons/business.png" alt="">
                </div>
                <div class="title">
                    <h3>#business</h3>
                    <span>Discuss business ideas and strategies</span>
                </div>
            </a>
        </div>
        
        </aside>

        <main class="main-container">
            <div class="create-post container">
                <?php
                if (isset($_SESSION['name'])) {
                    $name = $_SESSION['name'];
                ?>
                    <div class="user-icon icon">
                        <h1 style="color: #1E252B"><?= strtoupper($name[0]) ?></h1>
                    </div>
                <?php } else { ?>
                    <a href="/Login" class="btn">Sign Up</a>
                <?php } ?>
                <form id="create">
                    <input type="text" name="title" placeholder="Let's share what's going on in your mind...">
                    <button>Create Post</button>
                </form>
            </div>

            <div class="posts">
                <?php
                $connection = null;
                try {
                    $connection = DBUtils::getConnection();
                    $resultSet = $connection->query("SELECT * FROM Posts ORDER BY created_at DESC");

                    while ($post = $resultSet->fetch(PDO::FETCH_ASSOC)) {
                        $postId = $post['post_id'];
                        $hasLiked = false;

                        // Check for like status
                        if (isset($_SESSION['userId'])) {
                            $userId = $_SESSION['userId'];
                            $checkSql = "SELECT COUNT(*) FROM Likes WHERE post_id = ? AND user_id = ?";
                            $checkStmt = $connection->prepare($checkSql);
                            $checkStmt->execute([$postId, $userId]);
                            $hasLiked = $checkStmt->fetchColumn() > 0;
                        }

                        $likeButtonImage = $hasLiked ? "/assets/icons/liked.png" : "/assets/icons/Heart.png";
                        $likeButtonText = $hasLiked ? "Unlike" : "Like";

                        echo "<div class='post container'>";
                        echo "<a href='view-post?id={$post['post_id']}'> <img src='{$post['image']}' class='post-img' alt='Post Image'></a>";
                        echo "<div class='post-info'>";
                        echo "<div class='post-heading'>";
                        echo "<h2><a href='view-post?id={$post['post_id']}'>{$post['title']}</a></h2>";
                        echo "<form class='icon' action='/assets/Utils/posting.php' method='post'>";
                        echo "<input type='hidden' name='action' value='likePost'>";
                        echo "<input type='hidden' name='post_id' value='{$post['post_id']}'>";
                        echo "<button type='submit'><img src='{$likeButtonImage}' alt='Like Icon'></button>";
                        echo "</form>";
                        echo "</div>";
                        echo "<div class='tags'>";
                        $tags = explode(",", $post['tags']);
                        foreach ($tags as $tag) {
                            echo "<div class='tag'>#" . trim($tag) . "</div>";
                        }
                        echo "</div>";

                        // Fetch user info for the post
                        $preps = $connection->prepare("SELECT * FROM Users WHERE user_id = ?");
                        $preps->execute([$post['user_id']]);
                        $user = $preps->fetch();

                        // Format the post date
                        $formattedDate = date("F d, Y \a\\t H:i", strtotime($post['created_at']));
                        echo "<div class='post-user'>";
                        echo "<div class='profile'>";
                        echo "<div class='user-icon icon'> <h2>" . strtoupper($user['name'][0]) . "</h2></div>";
                        echo "<div class='post-meta'>";
                        echo "<h2>{$user['name']}</h2>";
                        echo "<span>{$formattedDate}</span>";
                        echo "</div></div>";
                        echo "<p class='views'>{$post['views']} Views</p>";
                        echo "<p class='likes'>{$post['likes']} Likes</p>";
                        echo "<p class='comments'>{$post['comments_count']} Comments</p>";
                        echo "</div></div></div>";
                    }
                } catch (PDOException $e) {
                    echo "<p>Error retrieving posts: " . $e->getMessage() . "</p>";
                } finally {
                    if ($connection) {
                        $connection = null;
                    }
                }
                ?>
            </div>
        </main>

        <aside class="right-bar main-container">
            <div class="events container">
                <h2>Upcoming Events</h2>
                <?php
                try {
                    $connection = DBUtils::getConnection();
                    $eventResultSet = $connection->query("SELECT * FROM Events WHERE date >= CURDATE() ORDER BY date ASC");

                    while ($event = $eventResultSet->fetch(PDO::FETCH_ASSOC)) {
                        $formattedMonth = strtoupper(date("M", strtotime($event['date'])));
                        $formattedDay = date("d", strtotime($event['date']));

                        echo "<a class='event' href='event?id={$event['event_id']}' style='text-decoration: none'>";
                        echo "<div class='date'><div class='month'>{$formattedMonth}</div><div class='day'>{$formattedDay}</div></div>";
                        echo "<div class='event-info'>";
                        echo "<h3>{$event['title']}</h3>";
                        echo "<div class='location'><img src='/assets/images/Rectangle 32.png' alt='Event Image'><span>{$event['location']}</span></div>";
                        echo "<div class='tags'><div class='tag'>#{$event['title']}</div></div>";
                        echo "</div></a>";
                    }
                } catch (PDOException $e) {
                    echo "<p>Error retrieving events: " . $e->getMessage() . "</p>";
                } finally {
                    if ($connection) {
                        $connection = null;
                    }
                }
                ?>
            </div>
        </aside>
    </main>

    <script src="/script.js"></script>
</body>
</html>
