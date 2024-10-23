<?php

// File: index.php
// Description: used for searching
// Author: Ndamulelo Rasendedza
// Created: 2024-10-22
// License: MIT License

require_once '../assets/Utils/DBUtils.php';
require_once '../assets/Utils/UserUtils.php';
require_once '../assets/Utils/Utils.php';


if ((!isset($_GET['searchterm']) || empty($_GET['searchterm']))&&(!isset($_GET['type']) || empty($_GET['type'])) ) {
  header("Location: /404.php");
  exit();
}else{
  $searchterm = $_GET['searchterm'];
  $type = $_GET['type'];
}

if($type == 'tags'){
  $posts = getPostsByTags($searchterm);
}else{
  $posts = getPostsBySearchTerm($searchterm);
}

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
  <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
  <title>The • Central • N</title>
</head>
<body>
<header>
  <nav>
    <div class="logo">
      <img src="/assets/images/Logo.png" alt="">
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
          <img src="/assets/icons/message icon.png" alt="">
        </div>
        <div class="notification-icon icon">
          <img src="/assets/icons/notification none.png" alt="">
        </div>
        <div class="profile">
          <?php
            if (isset($_SESSION['userId'])) {
              echo UserUtils::createUserIcon($_SESSION['userId']);
              echo '<span>' . UserUtils::getUsernameFromId($_SESSION['userId']) . '</span>';
            } else {
              echo '<a href="Login" class="btn">Sign Up</a>';
            }
          ?>
        </div>
      </div>
    </main>
  </nav>
</header>

<main class="main-content" style="justify-content: center; align-items: center; flex-direction: column;">
  <h1 style="margin-block: 2rem;">Search Term: <?= $searchterm ?></h1>
  <div class="main-container" style="align-items: center;">
    <div class="create-post container">
      <?php
        if (isset($_SESSION['name'])) {
          $name = $_SESSION['name'];
          echo '<div class="user-icon icon"><h1 style="color: #1E252B">' . strtoupper($name[0]) . '</h1></div>';
        } else {
          echo '<a href="Login" class="btn">Sign Up</a>';
        }
      ?>
      <form id="create">
        <input type="text" name="title" placeholder="Let's share what going on your mind...">
        <button>Create Post</button>
      </form>
    </div>

    <div class="posts">
      <?php

        if (!empty($posts)) {
          foreach ($posts as $post) {
            ?>
            <div class="post container">
              <img src="<?php echo $post['image']; ?>" class="post-img" alt="Eduvos Forum Post">
              <div class="post-info">
                <div class="post-heading">
                  <h2><a href="/view-post/?id=<?php echo $post['post_id']; ?>"><?php echo $post['title']; ?></a></h2>
                  <?php
                    $postId = $post['post_id'];
                    $connection = DBUtils::getConnection();
                    $hasLiked = false;

                    // Check database for like status
                    if (isset($_SESSION['userId'])) {
                      $userId = $_SESSION['userId'];
                      $checkSql = "SELECT COUNT(*) FROM Likes WHERE post_id = ? AND user_id = ?";
                      $checkStatement = $connection->prepare($checkSql);
                      $checkStatement->bindParam(1, $postId, PDO::PARAM_INT);
                      $checkStatement->bindParam(2, $userId, PDO::PARAM_INT);
                      $checkStatement->execute();
                      $hasLiked = $checkStatement->fetchColumn() > 0;
                    }

                    $likeButtonImage = $hasLiked ? "liked.png" : "Heart.png";
                    ?>
                  <form class="icon" action="/assets/Utils/posting.php" method="post">
                    <input type="hidden" name="action" value="likePost">
                    <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
                    <button type="submit">
                      <img src="/assets/icons/<?php echo $likeButtonImage; ?>" alt="Like Icon">
                    </button>
                  </form>
                </div>
                <div class="tags">
                  <?php
                    $tags = explode(",", $post['tags']);
                    foreach ($tags as $tag) {
                      echo '<div class="tag">#' . $tag . '</div>';
                    }
                  ?>
                </div>
                <div class="post-user">
                  <div class="profile">
                    <?php
                      echo UserUtils::createUserIcon($post['user_id']);
                    ?>
                    <div class="post-meta">
                      <h2><?php echo UserUtils::getUsernameFromId($post['user_id']); ?></h2>
                      <span>Posted at <?php echo $post['created_at']; ?></span>
                    </div>
                  </div>
                  <p class="views"><?php echo $post['views']; ?> Views</p>
                  <p class="likes"><?php echo $post['likes']; ?> Likes</p>
                  <p class="comments"><?php echo $post['comments_count']; ?> Comments</p>
                </div>
              </div>
            </div>
            <?php
          }
        } else {
          echo '<p>No posts found.</p>';
        }
      ?>
    </div>
  </div>
</main>

<footer style="display: flex; justify-content: center;">
  <p>&copy; 2024 Eduvos Forum. All rights reserved.</p>
</footer>
</body>
</html>
