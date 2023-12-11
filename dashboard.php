<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();



$host = 'localhost';
$dbname = 'dolphin_crm';
$username = 'admin'; 
$password = 'password123';     


try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}


$filter = isset($_GET['q']) ? $_GET['q'] : 'All';
$sql = "SELECT id, CONCAT(title, ' ', firstname, ' ', lastname) AS name, email, company, type FROM contacts";

if ($filter !== 'All') {
    if ($filter === 'Sales Leads') {
        $sql .= " WHERE type = 'Sales Lead'";
    } elseif ($filter === 'Support') {
        $sql .= " WHERE type = 'Support'";
    } elseif ($filter === 'Assigned to me') {
        $sql .= " WHERE assigned_to = :userId";
    }
}

$stmt = $conn->prepare($sql);


if ($filter === 'Assigned to me') {
    $stmt->bindParam(':userId', $_SESSION['user_id']);
}

$stmt->execute();
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($contacts);
?>
