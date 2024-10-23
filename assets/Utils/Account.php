<?php

// File: Account.php
// Description: Handles user account operations such as registration, login, and profile updates.
// Author: Ndamulelo Rasendedza
// Created: 2024-10-23
// License: MIT License


session_start(); // Start the session
require 'DBUtils.php'; // Include your database utility file
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// Function to connect to the database
function getConnection() {
    return new mysqli('localhost', 'root', '', 'centralN'); 
}

// Handle different actions based on the submitted form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $conn = getConnection();

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    switch ($action) {
        case 'signup':
            handleSignup($conn);
            break;

        case 'login':
            handleLogin($conn);
            break;

        case 'forgotPassword':
            handleForgotPassword($conn);
            break;
        case 'editProfile':
        handleEditProfile($_POST);
            break;

        case 'logout':
        handleLogout();
            break;

        default:
            echo "Invalid action!";
            break;
    }

    $conn->close(); // Close the database connection
}


function generateSecurePassword($length = 12) {
    // Ensure the password meets the required conditions
    if ($length < 8) {
        throw new InvalidArgumentException('Password length must be at least 8 characters.');
    }

    // Characters to choose from
    $lowercase = 'abcdefghijklmnopqrstuvwxyz';
    $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';
    $specialChars = '!@#$%^&*()-_=+[]{};:,.<>?';

    // Ensure at least one character from each category is included
    $password = '';
    $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
    $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
    $password .= $numbers[random_int(0, strlen($numbers) - 1)];
    $password .= $specialChars[random_int(0, strlen($specialChars) - 1)];

    // Fill the rest of the password length with random characters from all categories
    $allCharacters = $lowercase . $uppercase . $numbers . $specialChars;
    for ($i = 4; $i < $length; $i++) {
        $password .= $allCharacters[random_int(0, strlen($allCharacters) - 1)];
    }

    // Shuffle the password to avoid predictable patterns
    return str_shuffle($password);
}
function handleSignup($conn) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $password = generateSecurePassword(9); // Randomly generate a password
    $hashedPassword = $password; // No hashing for this demo


    // Check if the user already exists
    $checkSql = "SELECT * FROM Users WHERE email = ? OR contact = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ss", $email, $contact);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // User already exists
        writeResponse("Signup failed! A user with this email or contact number already exists.");
        $checkStmt->close();
        return; // Stop execution
    }

    // Photo upload handling
    $photo = $_FILES['photo'];
    $targetDir = "../uploads/";
    $photoName = time() . "_" . basename($photo["name"]);
    $targetFilePath = $targetDir . $photoName;
    move_uploaded_file($photo["tmp_name"], $targetFilePath);
    $photourl = "/assets/uploads/".$photoName;
    $sql = "INSERT INTO Users (name, surname, gender, dob, email, contact, photo, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $name, $surname, $gender, $dob, $email, $contact,$photourl , $hashedPassword);

    if ($stmt->execute()) {
        // Send email with the randomly generated password

        $mail = new PHPMailer(true);
            // Server settings
            $mail->isSMTP();
            $mail->Host = "mail.alurase.co.za";
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "ssl";
            $mail->Port = 465;
            $mail->Username = "website@alurase.co.za";
            $mail->Password = "Ndamu@23";

            $mail->setFrom('website@alurase.co.za','CentralN');
            $mail->addAddress($email);
            $mail->addAddress('ndamutm23@gmail.com');                         

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Your Account Details';
            $mail->Body    = "Your password is: <strong>$password</strong>";
            $mail->AltBody = "Your password is: $password";      // Plain text for non-HTML mail clients

            // Send the email
            $mail->send();


        writeResponse("Signup successful! An email has been sent with your login credentials.");
    } else {
        writeResponse("Signup failed! Please try again.");
    }

    $stmt->close();
}


function handleLogin($conn) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultSet = $stmt->get_result();

    if ($resultSet->num_rows > 0) {
        $user = $resultSet->fetch_assoc();
        if ($password ==$user['password']) {
            // Store user information in the session
            $_SESSION['userId'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];

            writeResponse("Login successful! Welcome back, " . htmlspecialchars($user['name']) . "!");
        } else {
            writeResponse("Invalid email or password. Redirecting...");
        }
    } else {
        writeResponse("No user found with that email.");
    }

    $stmt->close();
}
function handleEditProfile($request) {
    $name = $request['name'];
    $surname = $request['surname'];
    $gender = $request['gender'];
    $dob = $request['dob'];
    $email = $request['email'];
    $contact = $request['contact'];
    $password = $request['password'];

    // File upload logic for photo if changed
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo = $_FILES['photo']['name'];
        $photoTmpName = $_FILES['photo']['tmp_name'];
        $photoFolder = '../uploads/' . basename($photo);
        move_uploaded_file($photoTmpName, $photoFolder);
    } else {
        $photoFolder = null; // Leave unchanged
    }

    // Set the content type
    header("Content-Type: text/html");

    // Retrieve user ID from session
    $userId = $_SESSION['userId'];

    try {
        $connection = DBUtils::getConnection();
        if ($photoFolder) {
            $updateQuery = "UPDATE Users SET name = ?, surname = ?, gender = ?, dob = ?, email = ?, contact = ?, photo = ?, password = ? WHERE user_id = ?";
            $preparedStatement = $connection->prepare($updateQuery);
            $preparedStatement->execute([$name, $surname, $gender, $dob, $email, $contact, $photoFolder, $password, $userId]);
        } else {
            $updateQuery = "UPDATE Users SET name = ?, surname = ?, gender = ?, dob = ?, email = ?, contact = ?, password = ? WHERE user_id = ?";
            $preparedStatement = $connection->prepare($updateQuery);
            $preparedStatement->execute([$name, $surname, $gender, $dob, $email, $contact, $password, $userId]);
        }

        if ($preparedStatement->rowCount() > 0) {
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;

            writeResponse("Profile updated successfully! Redirecting...");
        } else {
            writeResponse("Failed to update profile! Redirecting...");
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        writeResponse("Error updating profile! Redirecting...");
    } finally {
        if ($preparedStatement) {
            $preparedStatement = null;
        }
        if ($connection) {
            $connection = null;
        }
    }
}


function handleForgotPassword($conn) {
    $email = $_POST['email'];

    writeResponse("A password reset link has been sent to your email if it exists in our records.");
}


function handleLogout() {
    // Check if the session exists
    if (session_status() === PHP_SESSION_ACTIVE) {
        // Destroy the session
        session_destroy();
    }

    // Send a response indicating a successful logout
    writeResponse("Successfully logged out");
}


function writeResponse($message) {
    $loca = "/";
    if (in_array($message, ["Invalid email or password. Redirecting...", "No user found with that email.", "Signup failed! Please try again."])) {
        $loca = "/Login";
    } else if ($message === "Failed to update profile! Redirecting...") {
        $loca = "/Profile/editProfile.php"; 
    }
    echo "<!DOCTYPE html>
    <html lang=\"en\">
    <head>
      <meta charset=\"UTF-8\">
      <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
      <link rel=\"stylesheet\" href=\"assets/CSS/style.css\">
      <title>Response</title>
    </head>
    <body>
      <div style=\"display: flex; align-items: center; justify-content: center; height: 100vh; padding: 0; margin: 0; font-size: 2rem; font-family: 'Poppins', sans-serif;\">
        <div>
          <p>" . htmlspecialchars($message) . "</p>
        </div>
      </div>
      <script>
        setTimeout(function() {
          window.location.href = '" . htmlspecialchars($loca) . "';
        }, 3000);
      </script>
    </body>
    </html>";
}
?>
