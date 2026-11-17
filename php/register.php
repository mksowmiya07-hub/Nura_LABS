<?php

header("Content-Type: application/json");

// Database connection
$conn = new mysqli(
    "sql113.infinityfree.com",
    "if0_43464728",
    "Sowmiya2003",
    "if0_43464728_Nura_AI_LABS",
    3306
);

if ($conn->connect_error) {
    echo json_encode([
        "status" => "error",
        "message" => "Database connection failed"
    ]);
    exit;
}

// Receive registration details
error_log(print_r($_POST,true));
$fullname = $_POST['fullname'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$cpassword = $_POST['cpassword'] ?? '';

// Check empty fields
if (empty($fullname) || empty($email) || empty($password) || empty($cpassword)) {
    echo json_encode([
        "status" => "error",
        "message" => "Please fill all fields"
    ]);
    exit;
}

// Check password match
if ($password !== $cpassword) {
    echo json_encode([
        "status" => "error",
        "message" => "Passwords do not match"
    ]);
    exit;
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert user into database
$stmt = $conn->prepare(
    "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)"
);

$stmt->bind_param("sss", $fullname, $email, $hashedPassword);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Registration successful!"
    ]);
} else {
    if ($stmt->errno == 1062) {
        echo json_encode([
            "status" => "error",
            "message" => "Email already registered"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Registration failed"
        ]);
    }
}

$stmt->close();
$conn->close();

?>