<?php

// File: index.php
// Description: used for displaying the event
// Author: Ndamulelo Rasendedza
// Created: 2024-10-23
// License: MIT License


session_start();
require_once '../assets/Utils/DBUtils.php';
require_once '../assets/Utils/UserUtils.php';
require_once '../assets/Utils/Utils.php';


// Fetch the post ID from the request
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: /404.php");
    exit();
}

$upcomingEvents = getFutureEvents(); // List of upcoming events
$event_view = getEventById($_GET['id']); // The event being viewed
$pastEvents = getPastEvents(); // List of past events

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
    <title>View Event - The • Central • N</title>
    <style>
        .main-content {
            gap: 1rem;
            justify-content: space-between;
        }
        .event-view {
            flex: 2;
        }
        .event-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .event-header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        .event-meta {
            display: flex;
            justify-content: space-around;
            font-size: 1rem;
            color: #FF6934;
        }
        .event-body {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .event-image {
            width: 100%;
            max-width: 600px;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        .event-description {
            font-size: 1.2rem;
            line-height: 1.5;
            text-align: center;
        }
        .location img {
            object-fit: cover;
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
            <div class="right-info ">
                <div class="message-icon icon">
                    <img src="/assets/icons/message icon.png" alt="">
                </div>
                <div class="notification-icon icon">
                    <img src="/assets/icons/notification none.png" alt="">
                </div>
                <div class="profile">
                    <?php
                    if (isset($_SESSION['userId'])) {
                        echo UserUtils::createUserIcon((int)$_SESSION['userId']);
                        echo '<span>' . UserUtils::getUsernameFromId((int)$_SESSION['userId']) . '</span>';
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
    <aside class="container">
        <div class="events-container">
            <h2>Upcoming Events</h2>
            <?php
            if (isset($upcomingEvents) && is_array($upcomingEvents)) {
                foreach ($upcomingEvents as $event) {
                    ?>
                    <a class="event" href="/event?id=<?php echo $event['event_id']; ?>" style="text-decoration: none">
                        <div class="date">
                            <div class="month"><?php echo date('m', strtotime($event['date'])); ?></div>
                            <div class="day"><?php echo date('d', strtotime($event['date'])); ?></div>
                        </div>
                        <div class="event-info">
                            <h3><?php echo $event['title']; ?></h3>
                            <div class="location">
                                <img src="https://sagea.org.za/wp-content/uploads/2021/07/SAGEA_affiliate-logos_Eduvos.png" alt="" width="10" height="10" style="border-radius: 50%;">
                                <span><?php echo $event['location']; ?></span>
                            </div>
                            <div class="tags">
                                <div class="tag">#<?php echo strtolower($event['title']); ?></div>
                            </div>
                        </div>
                    </a>
                    <?php
                }
            }
            ?>
        </div>
    </aside>
    <div class="container" style="width: 65%;">
        <div class="event-view">
            <div class="event-header">
                <h1><?php echo isset($event_view) ? $event_view['title'] : 'Event Not Found'; ?></h1>
                <div class="event-meta">
                    <span>Date: <?php echo isset($event_view) ? $event_view['date'] : ''; ?></span>
                    <span>Time: <?php echo isset($event_view) ? $event_view['time'] : ''; ?></span>
                    <span>Location: <?php echo isset($event_view) ? $event_view['location'] : ''; ?></span>
                </div>
            </div>
            <div class="event-body">
                <?php if (isset($event_view)): ?>
                    <img src="<?php echo $event_view['image']; ?>" alt="Event Image" class="event-image">
                    <div class="event-description">
                        <p><?php echo $event_view['event_info']; ?></p>
                    </div>
                <?php else: ?>
                    <p>No event selected.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <aside class="container">
        <div class="events-container">
            <h2>Past Events</h2>
            <?php
            if (isset($pastEvents) && is_array($pastEvents)) {
                foreach ($pastEvents as $event) {
                    ?>
                    <a class="event" href="/event?id=<?php echo $event['event_id']; ?>" style="text-decoration: none">
                        <div class="date">
                            <div class="month"><?php echo date('m', strtotime($event['date'])); ?></div>
                            <div class="day"><?php echo date('d', strtotime($event['date'])); ?></div>
                        </div>
                        <div class="event-info">
                            <h3><?php echo $event['title']; ?></h3>
                            <div class="location">
                                <img src="https://sagea.org.za/wp-content/uploads/2021/07/SAGEA_affiliate-logos_Eduvos.png" alt="" width="10" height="10" style="border-radius: 50%;">
                                <span><?php echo $event['location']; ?></span>
                            </div>
                            <div class="tags">
                                <div class="tag">#<?php echo strtolower($event['title']); ?></div>
                            </div>
                        </div>
                    </a>
                    <?php
                }
            }
            ?>
        </div>
    </aside>
</main>

</body>
</html>
