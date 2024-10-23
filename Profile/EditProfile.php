<?php

// File: EditProfile.php
// Description: used to edit the profile
// Author: Ndamulelo Rasendedza
// Created: 2024-10-22
// License: MIT License

require('../assets/Utils/DBUtils.php');
require('../assets/Utils/UserUtils.php');
require('../assets/Utils/Utils.php');

// Start session to handle user login
session_start();

// Get the user's ID from the session

$userId = $_SESSION['userId'];

// Get user data from the database

$user = getUserById($userId);

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
    <title>Edit Profile • The Central N</title>
</head>
<body>
<header>
    <nav>
        <a href="/" class="logo">
             <img src="/assets/images/Logo.png" alt="">
        </a>
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
                    <img src="/assets/icons/message%20icon.png" alt="">
                </div>
                <div class="notification-icon icon">
                    <img src="/assets/icons/notification%20none.png" alt="">
                </div>
                <div class="profile">
                    <?php
                    if (isset($_SESSION['userId'])) {
                        $userId = $_SESSION['userId'];
                        echo UserUtils::createUserIcon($userId);
                        $username = UserUtils::getUsernameFromId($userId);
                        echo "<span>$username</span>";
                    } else {
                        echo '<a href="/Login" class="btn">Sign Up</a>';
                    }
                    ?>
                </div>
            </div>
        </main>
    </nav>
</header>
<main class="main-content">
    <aside class="sidebar main-container">

    </aside>
    <main class="main-container">
        <div class="edit-profile container">
            <h1>Edit Profile</h1>

            <?php


                if (isset($_SESSION['userId'])) {


                    if (isset($user)) {
                        
                        $name = $user['name'];
                        $surname = $user['surname'];
                        $gender = $user['gender'];
                        $dob = $user['dob'];
                        $email = $user['email'];
                        $contact = $user['contact'];
                        $photo = $user['photo'];
                        $password = $user['password'];
                        ?>
                        <form action="/assets/Utils/Account.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="editProfile"/>
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" value="<?= htmlspecialchars($name); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="surname">Surname:</label>
                                <input type="text" id="surname" name="surname" value="<?= htmlspecialchars($surname); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="text" id="email" name="email" value="<?= htmlspecialchars($email); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="text" id="password" name="password" value="<?= htmlspecialchars($password); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="gender">Gender:</label>
                                <select id="gender" name="gender" required>
                                    <option value="Male" <?= $gender == 'Male' ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= $gender == 'Female' ? 'selected' : '' ?>>Female</option>
                                    <option value="Other" <?= $gender == 'Other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dob">Date of Birth:</label>
                                <input type="date" id="dob" name="dob" value="<?= htmlspecialchars($dob); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="contact">Contact:</label>
                                <input type="text" id="contact" name="contact" value="<?= htmlspecialchars($contact); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="photo">Profile Photo:</label>
                                <input type="file" id="photo" name="photo" accept="image/*">
                            </div>
                            <button type="submit" class="btn">Save Changes</button>
                        </form>
                        <form action="/assets/Utils/Account.php" method="post">
                            <input type="hidden" name="action" value="logout"/>
                            <button type="submit" class="btn">Logout</button>
                        </form>
                        <?php
                    } else {
                        echo "<p>User not found.</p>";
                    }
                } else {
                    echo "<p>You are not logged in.</p>";
                }
        
            ?>
        </div>
    </main>
    <aside class="right-bar main-container">
    </aside>
</main>
<script src="/script.js"></script>
</body>
</html>
