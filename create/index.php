<?php

// File: index.php
// Description: Used for creating a new post in the database
// Author: Ndamulelo Rasendedza
// Created: 2024-10-22
// License: MIT License

require('../assets/Utils/DBUtils.php');
require('../assets/Utils/UserUtils.php');
require('../assets/Utils/Utils.php');

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
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    <title>Create Post - The • Central • N</title>
    <style>
        .create-post-pop-up {
            display: flex;
            padding: 0;
            margin: 0;
            background: #1e252b8f;
        }
        main .container {
            height: 100%;
        }
        .post-details-info form, form .flex {
            gap: 1rem;
        }
        .ql-toolbar.ql-snow {
            background-color: #1E252B;
        }
        .ql-toolbar.ql-snow * {
            color: white;
        }
        form .flex {
            align-items: initial;
        }
    </style>
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
                    <img src="/assets/icons/message icon.png" alt="">
                </div>
                <div class="notification-icon icon">
                    <img src="/assets/icons/notification none.png" alt="">
                </div>
                <div class="profile">
                    <?php
                    if (isset($_SESSION['userId'])) {
                        $userId = $_SESSION['userId'];
                        echo UserUtils::createUserIcon($userId);
                        $username = UserUtils::getUsernameFromId($userId);
                        echo "<span>$username</span>";
                    } else {
                        echo '<a href="Login" class="btn">Sign Up</a>';
                    }
                    ?>
                </div>
            </div>
        </main>
    </nav>
</header>
<main class="main-content">
    <div class="create-post-pop-up" id="create-post-pop-up">
        <div class="post-details-info container">
            <form action="/assets/Utils/posting.php" method="post" enctype="multipart/form-data" id="post-form">
                <input type="hidden" name="action" value="createPost">
                <div class="flex">
                    <div class="post-details">
                        <div class="post-img">
                            <label for="myFile">
                                <img src="/assets/images/Frame 2 (1).png" alt="Upload image" id="imagePreview">
                            </label>
                        </div>
                        <input type="file" accept="image/*" name="myFile" id="myFile" style="display: none;">
                        <input type="text" name="title" placeholder="Post title..." class="post-title">
                        <div class="tags-input">
                            <input type="text" placeholder="Add tags (separate by comma)" class="post-tags" id="tag-input">
                            <input type="text" name="tags" id="tags" hidden="hidden">
                            <div id="tags-container" style="display: flex; width: 300px; flex-wrap: wrap;"></div>
                        </div>
                        <button type="submit">Share Post</button>
                    </div>
                    <div class="post-details">
                        <div id="editor-container" cols="4" placeholder="What's on your mind..."></div>
                        <input type="hidden" id="post-content" name="content">
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
<script src="script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.min.js"></script>
<script>
    const quill = new Quill('#editor-container', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'font': [] }, { 'size': [] }],
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                [{ 'color': [] }, { 'background': [] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'script': 'sub' }, { 'script': 'super' }],
                ['blockquote', 'code-block'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'indent': '-1' }, { 'indent': '+1' }],
                [{ 'direction': 'rtl' }, { 'align': [] }],
                ['link', 'image', 'video'],
                ['clean']
            ]
        }
    });

    const form = document.getElementById('post-form');
    form.onsubmit = function() {
        const content = document.querySelector('input[name=content]');
        content.value = quill.root.innerHTML;
        console.log("Submitted Content:", content.value);
        return true;
    };
</script>
</body>
</html>
