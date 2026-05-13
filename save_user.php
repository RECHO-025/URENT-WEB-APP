<?php
include('db_connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $username = $_POST['username'];
    $password = $_POST['password'];
    $type = $_POST['type'];

    // Check if username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND type != ?");
    $stmt->bind_param("si", $username, $type);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo 2; // Username already exists
        exit;
    }

    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_BCRYPT); // Use bcrypt for hashing
    }

    if (empty($username)) {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (name, username, password, type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $name, $username, $password, $type);
    } else {
        // Update existing user
        if (empty($password)) {
            $stmt = $conn->prepare("UPDATE users SET name = ?, username = ?, type = ? WHERE type = ?");
            $stmt->bind_param("ssii", $name, $username, $type);
        } else {
            $stmt = $conn->prepare("UPDATE users SET name = ?, username = ?, password = ? WHERE type = ?");
            $stmt->bind_param("sssii", $name, $username, $password, $type );
        }
    }

    if ($stmt->execute()) {
        echo 1; // Success
    } else {
        echo 0; // Error
    }
}
?>
