<?php

// File: index.php
// Description: used for showing the list of my friends
// Author: Ndamulelo Rasendedza
// Created: 2024-10-22
// License: MIT License

require_once '../assets/Utils/DBUtils.php';
require_once '../assets/Utils/UserUtils.php';
require_once '../assets/Utils/Utils.php';

session_start();

if (!isset($_SESSION['userId'])) {
    // Redirect or show an error if the user is not logged in
    header("Location: /Login");
    exit();
}

// Get the logged-in user's ID
$userId = $_SESSION['userId'];

// Get friends of the logged-in user
$friends = getFriends($userId);
$pendingRequests = getPendingRequests($userId);
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
  <h1 style="margin-block: 2rem;">My Friends</h1>
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

    <style>
      .user-icon{
        border: 2px solid #EA942C;
        width: 40px;
        height: 40px;
        background-color: #2C353D;
        border-radius: 7px;
        margin-inline: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .user{
        background: #262D34;
        padding: 1rem;
        border-radius: 1rem;
        margin-block: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
      }
    </style>
    
    <?php
      if (!empty($friends)) {
        foreach ($friends as $friend) {
          ?>
          <div class="user">
            <div class="profile">
              <?php
                echo UserUtils::createUserIcon($friend['user_id']);
              ?>
            </div>
            <h2><?php echo UserUtils::getUsernameFromId($friend['user_id']); ?></h2>
            <form action="/friendActions.php" method="POST">
                          <input type="hidden" name="friendId" value="<?php echo $user['user_id']; ?>">

                              <button type="submit" name="action" value="remove" class="btn">Remove Friend</button>
                      </form>
          
          </div>
          <?php
        }
      } else {
        echo '<p>No friends found.</p>';
      }
    ?>
    <hr>
    <!-- Pending Friend Requests Section -->
    <h2>Pending Requests</h2>
      <?php
      if (!empty($pendingRequests)) {
          foreach ($pendingRequests as $request) {
              $userId = $request['user_id'];
              $username = UserUtils::getUsernameFromId($userId);
              ?>
              <div class="user">
                  <h2><?php echo $username; ?></h2>
                  <form action="/friendActions.php" method="POST">
                      <input type="hidden" name="friendId" value="<?php echo $userId; ?>">
                      <button type="submit" name="action" value="accept" class="btn">Accept</button>
                      <button type="submit" name="action" value="reject" class="btn">Reject</button>
                  </form>
              </div>
              <?php
          }
      } else {
          echo '<p>No pending friend requests.</p>';
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
