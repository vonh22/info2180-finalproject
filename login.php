<?php 
session_start(); 

$host = 'localhost';
$username = 'admin';
$password = 'password123';
$dbname = 'dolphin_crm';
$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);


if (isset($_POST['email']) && isset($_POST['password'])) {

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
	$pass =  filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    
    $statement = $conn->prepare("SELECT email, id, password FROM users WHERE email = :email");
    $statement->bindParam(':email', $email);
    $statement->execute();
    
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: dashboard.html'); 
        exit();
    } else {
       $_SESSION['message'] = "Invalid email or password";
    }
    }
    ?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <title>Dolphin CRM</title>
</head>
<body>
    <header>
        <i class="fas fa-fish"></i>      
        <h1 class="heading">Dolphin CRM</h1>
    </header>
    <main>
        <?php if (isset($_SESSION['message'])) : ?>
            <p style="color: red;">
                <?php echo $_SESSION['message']; ?>
            </p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        <div class="container">
            <form method="post" id="login" action="login.php">
                <h2>Login</h2>
                <label for="email"></label>
                <input type="email" name="email" id="emailbox" placeholder="Email address" required>
    
                <label for="password"></label>
                <input type="password" name="password" id="passwordbox" placeholder="Password" required>

                
                <button class="btn" type="submit" name="login" id="loginbtn">
                    <i class="fa-solid fa-lock"></i>
                    <p class="btntext">Login</p>
                </button>

                <div id="result"></div>
            </form>

        </div>
    </main>

    <footer>
        <h4>Copyright &copy; 2022 Dolphin CRM</h4>
    </footer>
    

</body>
</html>
