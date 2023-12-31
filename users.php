<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
?>

<?php

if (isset($_GET['query'])) {
  $var = trim($_GET['query']);
}
?>

<?php
session_start();



$user_id = $_SESSION['user_id'];

$host = 'localhost';
$fname = 'admin';
$lname = 'Person';
$email = 'admin@project2.com';
$password = 'password123';
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
//$role = 'Admin';
$dbname = 'dolphin_crm';


//echo("Fetched");
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $fname, $password);
$tmt = $pdo->prepare("SELECT role FROM users WHERE id = :user_id");
$tmt->bindParam(':user_id', $user_id);
$tmt->execute();
$result = $tmt->fetch(PDO::FETCH_ASSOC);

$role = isset($result['role']) ? $result['role'] : null;

if ($role == 'Admin') {
     //echo "Admin ";
     $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $fname, $password);
     $stmt = $conn->query("SELECT * FROM users");
     $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
     ?>
<div id="flex_div">
            <h1>Users</h1>
            <a href="new_user.html">
                <button id="add_user"><i class="fa-solid fa-plus"></i> Add User </button>
            </a>
            
        </div>
        <div id = "resultscontain">
<table>
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($results as $row): ?>
            
            <tr>
                <td class = "name"><?= $row['firstname']; ?></td>
                <td class = "email"><?= $row['email']; ?></td>
                <td class = "role"><?= $row['role']; ?></td>
                <td class = "created_at"><?= $row['created_at']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
        </div>
    <?php
} else {
    
?>
    <div id = "resultscontainmember">
    <span> Only Admins can view all users and add users</span>
</div>
<?php } ?>




