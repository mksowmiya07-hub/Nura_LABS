
<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb://127.0.0.1:27017");

$logs = $mongoClient->selectCollection(
    "Nura_AI_LABS",
    "login_logs"
);

$redis = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => '127.0.0.1',
    'port'   => 6379
]);

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

// Receive login details
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$attemptKey = "login_attempts:" . $email;

// Check login attempt limit
$attempts = $redis->get($attemptKey);

if ($attempts !== null && $attempts >= 5) {
    echo json_encode([
        "status" => "error",
        "message" => "Too many failed attempts. Try again after 15 minutes."
    ]);
    exit;
}

// Check empty fields
if (empty($email) || empty($password)) {
    echo json_encode([
        "status" => "error",
        "message" => "Please enter email and password"
    ]);
    exit;
}

// Find user by email
$stmt = $conn->prepare(
    "SELECT id, full_name, email, password FROM users WHERE email = ?"
);

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

// Check whether user exists
if ($result->num_rows === 1) {

    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password'])) {
    $redis->del($attemptKey);
    $logs->insertOne([
    "user_id" => $user['id'],
    "email" => $user['email'],
    "status" => "success",
    "login_time" => new MongoDB\BSON\UTCDateTime()
    ]);

        $_SESSION['user_id'] = $user['id'];

        echo json_encode([
            "status" => "success",
            "message" => "Login successful!"
        ]);

    }
    else {
        $attempts = $redis->incr($attemptKey);

        if ($attempts === 1) {
     $redis->expire($attemptKey, 900);
    }
        $logs->insertOne([
    "user_id" => $user['id'],
    "email" => $user['email'],
    "status" => "failed",
    "reason" => "Incorrect password",
    "login_time" => new MongoDB\BSON\UTCDateTime()
    ]);

        echo json_encode([
            "status" => "error",
            "message" => "Incorrect password"
        ]);
    }

} else {
    $logs->insertOne([
    "email" => $email,
    "status" => "failed",
    "reason" => "Email not registered",
    "login_time" => new MongoDB\BSON\UTCDateTime()
    ]);

    echo json_encode([
        "status" => "error",
        "message" => "Email not registered"
    ]);
}

$stmt->close();
$conn->close();

?>
