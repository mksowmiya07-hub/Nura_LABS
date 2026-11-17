
<?php

session_start();

header("Content-Type: application/json");

// Check whether user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Please login first"
    ]);
    exit;
}

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

// Get logged-in user's details
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare(
    "SELECT full_name, email, created_at FROM users WHERE id = ?"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {

    $user = $result->fetch_assoc();

    echo json_encode([
        "status" => "success",
        "user" => $user
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => "User not found"
    ]);
}

$stmt->close();
$conn->close();

?>
