<?php

try {

  define('DB_HOST', 'mysql');
  define('DB_USER', 'test_user');
  define('DB_PASS', 'password');
  define('DB_NAME', 'test_db');
  
  // Create connection
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  
  // Check connection
  if (!$conn) {
    throw new Exception("Connection failed: " . $conn->connect_error);
  }

  $ps = $conn->prepare("INSERT INTO users(name, username, password) VALUE(?,?,?)");

  $name = 'test 1';
  $username = 'test_' . uniqid();
  $password = 'password';
  
  $ps->bind_param('sss', $name, $username, $password);
  $ps->execute();

  $data = [];
  $users = $conn->query("SELECT * FROM users");
  if($users->num_rows > 0) {
    while($row = $users->fetch_assoc()) {
      $data[] = $row;
    }
  }

  header("Content-Type:application/json");
  echo json_encode($data);
  
  $conn->close();
} catch(Throwable $e) {
  http_response_code(400);
  die($e->getMessage());
}

?>