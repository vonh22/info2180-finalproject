<?php session_start();


    $host = 'localhost';
    $username = 'admin';
    $password = 'password123';
    $dbname = 'dolphin_crm';

    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    $stmt = $conn->query("SELECT * FROM users;");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $q = filter_input(INPUT_GET, 'load', FILTER_SANITIZE_STRING);
    
    if(isset($q)):
?>
    <label for="assigned-to">Assign To</label> 
    <select name="assigned-to" required> 
        <?php foreach ($results as $row): ?> 
            <option value="<?php echo $row['firstname']. " " . $row['lastname']?>"><?php echo $row['firstname'] . " " . $row['lastname']?></option>;
        <?php endforeach;?>
    </select>
    <?php else:?>
<?php
    
   $title= filter_input(INPUT_POST,"title",FILTER_SANITIZE_STRING); 
   $firstname= filter_input(INPUT_POST,"firstname",FILTER_SANITIZE_STRING); 
   $lastname= filter_input(INPUT_POST,"lastname",FILTER_SANITIZE_STRING); 
   $email= filter_input(INPUT_POST,"email",FILTER_SANITIZE_EMAIL); 
   $telephone= filter_input(INPUT_POST,"telephone",FILTER_SANITIZE_STRING); 
   $company= filter_input(INPUT_POST,"company",FILTER_SANITIZE_STRING); 
   $type= filter_input(INPUT_POST,"type",FILTER_SANITIZE_STRING); 
   $assigned_to= filter_input(INPUT_POST,"assigned-to",FILTER_SANITIZE_STRING); 

   function filter_data($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
   }

   function set_data_filtered(){
    global $title, $firstname, $lastname, $email, $telephone, $company, $type, $assigned_to, $conn;

    $existing_stmt = $conn->prepare("SELECT id FROM contacts WHERE email=:email");
    $existing_stmt->bindParam(':email', $email);
    $existing_stmt->execute();
    $existing_res = $existing_stmt->fetchAll();

    if(empty($firstname)){ 
        echo "<span class='resMsg'>Add contact failed: Enter contact's first name.</span>";
        return false;
    }     
    if(empty($lastname)){ 
        echo "<span class='resMsg'>Add contact failed: Enter contact's last name.</span>";
        return false;
    } 
    if(empty($email)){ 
        echo "<span class='resMsg'>Add contact failed: Enter contact's email.</span>";
        return false;
    } 
    if(empty($telephone)){ 
        echo "<span class='resMsg'>Add contact failed: Enter contact's phone number.</span>";
        return false;
    } 
    if(empty($company)){ 
        echo "<span class='resMsg'>Add contact failed: Enter contact's company.</span>";
        return false;
    } 
    if(!preg_match("/^[0-9]{4}-[0-9]{3}-[0-9]{4}$/", $telephone)){
        echo "<span class='resMsg'>Add contact failed: Phone number is not valid.</span>";
        return false;
    }
    if(!empty($existing_res)){
        echo "<span class='resMsg'>Add contact failed: Contact already exists. Please use a different email.</span>";
        return false;
    }
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        echo "<span class='resMsg'>Add contact failed: Email is not valid.</span>";
        return false;
    }    

    $title = filter_data($title);
    $firstname = filter_data($firstname);
    $lastname = filter_data($lastname);
    $telephone = filter_data($telephone);
    $company = filter_data($company);
    $type = filter_data($type);
    $assigned_to = filter_data($assigned_to);
    return true;
}


    if(set_data_filtered() == true){
        $assigned_name = explode(" ", $assigned_to);
        $assigned_fname = $assigned_name[0];
        $assigned_lname = $assigned_name[1];
    
        $assigned_stmt = $conn->prepare("SELECT id FROM users WHERE firstname = :assigned_fname AND lastname=:assigned_lname;");
        $assigned_stmt->bindParam(':assigned_fname', $assigned_fname);
        $assigned_stmt->bindParam(':assigned_lname', $assigned_lname);
        $assigned_stmt->execute();
        $assigned_results = $assigned_stmt->fetchAll();

        $assigned_id = $assigned_results[0]['id'];


        $logged_in_user_id = $_SESSION['user_id'];

        $sql_stmt = $conn->prepare("INSERT INTO contacts VALUES (DEFAULT, :title, :firstname, :lastname, :email, :telephone, :company, :ctype, :assigned_id, :logged_in_user_id, DEFAULT, CURRENT_TIMESTAMP);");
        $sql_stmt->bindParam(':title', $title);
        $sql_stmt->bindParam(':firstname', $firstname);
        $sql_stmt->bindParam(':lastname', $lastname);
        $sql_stmt->bindParam(':email', $email);
        $sql_stmt->bindParam(':telephone', $telephone);
        $sql_stmt->bindParam(':company', $company);
        $sql_stmt->bindParam(':ctype', $type);
        $sql_stmt->bindParam(':assigned_id', $assigned_id);
        $sql_stmt->bindParam(':logged_in_user_id', $logged_in_user_id);

        if($sql_stmt->execute()){
            echo "<span class='resMsgSuccess'>New contact successfully submitted!</span><br>";
    
        } else{
            echo  "<span class='resMsg'>Add contact failed: Error adding to database.</span><br>";
        }
    } 
endif;
?>