

<?php session_start();
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

$host = 'localhost';
$fname = 'admin';
$lname = 'Person';
$email = 'admin@project2.com';
$password = 'password123';
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
$role = 'admin';
$dbname = 'dolphin_crm';
//("SELECT * FROM notes,contacts JOIN contacts on contacts.id=notes.contact_id WHERE contacts.firstname LIKE :var");
$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $fname, $password);
date_default_timezone_set('EST');
if (isset($_GET['id'])):
    $var = trim($_GET['id']);
//$stmt = $conn->query("SELECT * FROM contacts");
//$stmt = $conn->query("SELECT * FROM contacts WHERE contacts.firstname='$var'");
//echo("Test_php_server");
//$stmt->execute();
$stmt = $conn->prepare("SELECT * FROM contacts WHERE contacts.id LIKE :var");

$stmt->bindParam(':var', $var, PDO::PARAM_STR);

$stmt->execute();


$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$res = $results[0];
$contact_fname = $res['firstname'];
$contact_lname = $res['lastname'];

$_SESSION['contact_info'] = $res;

//echo("Fetched");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main&side.css">
    <link rel="stylesheet" href="view_contact.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <title>Dolphin CRM</title>
</head>
<body>
    <header>
        <i class="fas fa-fish"></i>       
        <p class="heading">Dolphin CRM</p>
    </header>
    <main>
  

        <div id="results">
            <div class="contact-container">
                <div class="personal-details">
                <div id = "div_in">
                <p class="contact-title" id="five"><i class="fa-solid fa-user"></i><?php echo $res['title'] . ". ". $contact_fname . " " . $contact_lname; ?></p>
              
                <?php 
                   $creator_id = $res['created_by'];
                   $creator_stmt = $conn->prepare("SELECT firstname, lastname FROM users WHERE id=:creator_id;");
                   $creator_stmt->bindParam(':creator_id', $creator_id);
                   $creator_stmt ->execute();
                   $creator_results = $creator_stmt->fetchAll();
                   $creator_fname = $creator_results[0]['firstname'];
                   $creator_lname = $creator_results[0]['lastname'];
                   ?>
                    <p class = "contact-creation" id="six"> Created on <?php echo date("F j, Y",strtotime($res['created_at'])) ?> by <?php echo $creator_fname . " " . $creator_lname;?></p>
                    <p class ="contated-updated" id="seven">Updated on <?php echo date("F j, Y",strtotime($res['updated_at'])) ?></p>
</div>
                   <diiv class = "div2">
                    <form class="buttons">
                    <button class="assign-button" id="b1" ><i class="fa-solid fa-hand"></i>Assign To Me</button>
                    <?php 
                        $contact_type = $res['type'];
                        if($contact_type == "Sales Lead"){
                            $other = "Support";
                            $type_button = "support-button";
                        } else{
                            $other  = "Sales Lead";
                            $type_button = "lead-button";
                        }
                    ?>
                    <button id="b2" class = <?php echo $type_button?> ><i class="fa-solid fa-down-left-and-up-right-to-center"></i>Switch to <?php echo $other?> </button>
                    </form>
                    </div>
                    
                
                
                    <div class="contact-details">
                        
                    <div class="view-email">
            
                    <p id="one"> Email </p>
                    <p><?php echo $res['email']?></p>
                </div>
                <div class="view-company">
                    
                    <p id="two"> Company  </p>
                    <p> <?php echo $res['company']?></p>
                </div>
                <div class="view-telephone">
                    <p id="three"> Telephone </p>
                    <p><?php echo $res['telephone']?></p>
                    </div>
                <?php
                        $as_id = $res['assigned_to'];
                        $assigned_stmt = $conn->prepare("SELECT firstname, lastname FROM users WHERE id=:assigned_id;");
                        $assigned_stmt->bindParam(':assigned_id', $as_id);
                        $assigned_stmt->execute();
                        $assigned_results = $assigned_stmt->fetchAll();
                        $assigned_f = $assigned_results[0]['firstname'];
                        $assigned_l = $assigned_results[0]['lastname'];

                ?>
                    <div class="view-assigned">
                
                    <p id="four">Assigned To </p>
                    <p id = "para"> <?php echo $assigned_f . " " . $assigned_l;?></p>
                </div>
                    
                    
                    </div>
                
                   
                
                
                <div class="notes-container" id="contact-notes">
                <div class = "paddedarea">
                <div class="notes-header">
                    <p><i class="fa-regular fa-note-sticky"></i>Notes</p>
                </div>

                <?php 
                $this_contact_id = $res['id'];
                $comments_stmt = $conn->prepare("SELECT * FROM notes WHERE contact_id = :this_contact_id;");
                $comments_stmt->bindParam(':this_contact_id', $this_contact_id);
                $comments_stmt->execute();
                $comments = $comments_stmt->fetchAll();
                foreach ($comments as $comment): 
                    $comment_creator_id = $comment['created_by'];
                    $comment_creator_stmt = $conn->prepare("SELECT firstname, lastname FROM users WHERE id=:creator_id;");
                    $comment_creator_stmt->bindParam(':creator_id', $comment_creator_id);
                    $comment_creator_stmt->execute();
                    $comment_res = $comment_creator_stmt->fetchAll();
                    $comment_creator_fname = $comment_res[0]['firstname'];
                    $comment_creator_lname = $comment_res[0]['lastname'];
                    $comment_content = $comment['comment'];
                ?>
                  

                    <div class="notes-details">
                    <p id="creator"><?php echo $comment_creator_fname . " " . $comment_creator_lname;?></p>
                    <p id="Comments"><?php echo $comment_content;?></p>
                    <p id="created"><?php echo  date("F j, Y",strtotime($comment['created_at']))?>  at <?php echo date("ga",strtotime($comment['created_at']))?></p>
              
                    </div>
            
                    <?php endforeach;?>
                    <div id="msg-results-notes"></div>
                    </div>
                    <div class="add-note">
                <div class="add-note-label">Add a note about <?php echo $contact_fname ?></div>
                <textarea name="note-text" id="note-text" cols="30" rows="10" placeholder="Enter details here "></textarea>
                <div class="save-btn-container">
                    <button type="submit" id="b3" class="save-note-btn">Add Note</button>
                </div>
            </div>
            
          </div>
                
                </div>

                
            
        </div>
    </main>
    <aside>
        <div class="aside-container">
            <ul>
                <li><i class="fa-solid fa-house"></i><p><a href="dashboard.html">Home</a></p></li>
                <li><i class="fa-solid fa-user"></i><p><a href="new_contact.html">New Contact</a></p></li>
                <li><i class="fa-solid fa-users"></i><p><a href="users.html">Users</a></p></li>
            </ul>
            <hr>
            <div class="logout"><i class="fa-solid fa-right-from-bracket"></i><p><a href="logout.php">Logout</a></p></div>
        </div>
    </aside>

    <script src="view_contact.js" defer></script>
</body>
</html>




<?php elseif (isset($_GET['noteinfo'])):

  $note_info = $_GET['noteinfo'];
  $contact_info = $_SESSION['contact_info'];
  $contact_id = $contact_info['id'];
  $logged_in_user_id = $_SESSION['user_id'];

  
  function filter_data($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
  $comment_info = filter_data($note_info); 
  
  $comment_stmt = $conn->prepare("INSERT INTO notes VALUES (DEFAULT, :contact_id, :note, :logged_in_user_id, DEFAULT);");
  $comment_stmt->bindParam(':contact_id', $contact_id);
  $comment_stmt->bindParam(':note', $comment_info);
  $comment_stmt->bindParam(':logged_in_user_id', $logged_in_user_id);
  $comment_stmt->execute();

  $update_contact_stmt = $conn->prepare("UPDATE contacts SET updated_at = CURRENT_TIMESTAMP WHERE id = :contact_id;");
  $update_contact_stmt->bindParam(':contact_id', $contact_id);
  $update_contact_stmt->execute();

    $user_stmt = $conn->prepare("SELECT firstname, lastname FROM users WHERE id=:user_id;");
    $user_stmt->bindParam(':user_id', $logged_in_user_id);
    $user_stmt ->execute();
    $user_results = $user_stmt->fetchAll();
    $user_fname = $user_results[0]['firstname'];
    $user_lname = $user_results[0]['lastname'];



?>

<div class="notes-details">
  <p id="creator"><?php echo $user_fname  . " " . $user_lname ;?></p>
  <p id="Comments"><?php echo $comment_info;?></p>
  <p id="created"><?php echo date("F j, Y");?> at <?php echo date("ga") ?></p></p>
</div>
 
 <?php elseif (isset($_GET['assigntoyou'])):
    $logged_in_user_id = $_SESSION['user_id'];
    $contact_info = $_SESSION['contact_info'];
    $contact_id = $contact_info['id'];

    $update_contacts_stmt = $conn->prepare("UPDATE contacts SET assigned_to = :logged_in_user_id WHERE id = :contact_id;");
    $update_contacts_stmt->bindParam(':logged_in_user_id', $logged_in_user_id);
    $update_contacts_stmt->bindParam(':contact_id', $contact_id);
    $update_contacts_stmt->execute();
    
    $update_contacts_time_stmt = $conn->prepare("UPDATE contacts SET updated_at = CURRENT_TIMESTAMP WHERE id = :contact_id;");
    $update_contacts_time_stmt->bindParam(':contact_id', $contact_id);
    $update_contacts_time_stmt->execute();

    $user_stmt = $conn->prepare("SELECT firstname, lastname FROM users WHERE id=:user_id;");
    $user_stmt->bindParam(':user_id', $logged_in_user_id);
    $user_stmt ->execute();
    $user_results = $user_stmt->fetchAll();
    $user_fname = $user_results[0]['firstname'];
    $user_lname = $user_results[0]['lastname'];

    echo $user_fname." ".$user_lname;

    elseif (isset($_GET['switchto'])):


        $contact_info = $_SESSION['contact_info'];
        $contact_id = $contact_info['id'];
        $switched = $_GET['switchto'];

        function filter_data($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;}

        $switched_to = filter_data($switched);
    
        $switcher_stmt = $conn->prepare("UPDATE contacts SET type = :switched_val WHERE id = :contact_id;");
        $switcher_stmt->bindParam(':switched_val', $switched_to);
        $switcher_stmt->bindParam(':contact_id', $contact_id);
        $switcher_stmt->execute();


        $switch_to_update_stmt = $conn->prepare("UPDATE contacts SET updated_at = CURRENT_TIMESTAMP WHERE id = :contact_id;");
        $switch_to_update_stmt->bindParam(':contact_id', $contact_id);
        $switch_to_update_stmt->execute();
        echo "<span class='resMsgSuccess'>Contact Successfully Switched To " . $switched_to  . "!</span><br>" ;


endif;?>

