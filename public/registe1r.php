<?php
  require_once('./private/initialize.php');
  // Set default values for all variables the page needs.
  
  // if this is a POST request, process the form
  // Hint: Write these in private/functions.php
    $first_name = $last_name = $email = $username ="";
	$errors = [];
	$valid = false;
    if (is_post_request()) {

      $valid = true;
      // Confirm that POST values are present before accessing them.
      // Perform Validations
      // Hint: Write these in private/validation_functions.php
      //check that first name is not blank and correct length
      if (is_blank($_POST['first_name'])) {
        $errors[] = "First name cannot be blank.";
        $valid = false;
      } elseif (!has_length($_POST['first_name'], ['min' => 2, 'max' => 255])) {
        $errors[] = "First name should be between 2 to 255 characters.";
        $valid = false;
      } else {
        $first_name = trim($_POST['first_name']);
      }
      // check that late name is not blank and correct length
      if (is_blank($_POST['last_name'])) {
        $errors[] = "Last name cannot be blank.";
        $valid = false;
      } elseif (!has_length($_POST['last_name'], ['min' => 2, 'max' => 255])) {
        $errors[] = "Last name should be between 2 to 255 characters.";
        $valid = false;
      } else {
        $last_name = trim($_POST['last_name']);
      }
      // check that email is valid
      if (is_blank($_POST['email'])) {
        $errors[] = "Email cannot be blank.";
        $valid = false;
      } elseif (!has_length($_POST['email'], ['min' => 2, 'max' => 255])) {
        $errors[] = "Email should be between 2 and 255 characters.";
        $valid = false;
      } elseif (!has_valid_email_format($_POST['email'])){
        $errors[] = "Enter a valid email format.";
        $valid = false;
      } else {
        $email = trim($_POST['email']);
      }
      //check that username is valid
      if (is_blank($_POST['username'])) {
        $errors[] = "Username cannot be blank.";
        $valid = false;
      } elseif (!has_length($_POST['username'], ['min' => 8, 'max' => 255])) {
        $errors[] = "Username should be 8 characters or more.";
        $valid = false;
      } else {
        $username = trim($_POST['username']);
      }
    }
    
    // if the names are valid values check is there is already the same entry
    if ($valid && !is_blank($_POST['username'])){
      // Write SQL quary 
      $sql = sprintf ("SELECT * FROM users WHERE username = \"%s\"; ", $username);
      //common error is not using the quary correctly or not accessing the num_rows correctly
      $result = $db->query($sql);
      if($result->num_rows > 0){
        //ther username is already in use
        $errors[] = "The username is already in use.";
        $valid = false;
      } 
    }
    // if there were no errors, submit data to database
    if ($valid && is_post_request()){
      // Write SQL INSERT statement
      $sql = sprintf ("INSERT INTO users (first_name, last_name, email, username, created_at)
      VALUES ('%s','%s','%s','%s','%s'); ", $first_name, $last_name, $email, $username, date("Y-m-d H:i:s"));
      //For INSERT statments, $result is just true/false
      $result = db_query($db, $sql);
      if($result) {
        db_close($db);
        // redirect user to success page
        redirect_to("public/registration_success.php");
      } else {
         // The SQL INSERT statement failed.
         // Just show the error, not the form
         echo db_error($db);
         db_close($db);
         exit;
      }
    }
?>

<?php $page_title = 'Register'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>
<body align="center" style="width: 100%; height: 100%; background-color: green" >
<div id="main-content"  >
  <h1>Register</h1>
  <p>Register to become a Globitek Partner.</p>

  <?php
    // TODO: display any form errors here
    // Hint: private/functions.php can help
    echo display_errors($errors);
  ?>

  <!-- TODO: HTML form goes here -->
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

    First Name: 
    <br>
      <input type="text" name="first_name" value="<?php echo $first_name; ?>">
    </br>
    Last Name:
    <br>
      <input type="text" name="last_name" value="<?php echo $last_name; ?>">
    </br>
    Email:
    <br>
      <input type="text" name="email" value="<?php echo $email; ?>">
    </br>
    Username:
    <br>
      <input type="text" name="username" value="<?php echo $username; ?>">
    </br>
    <br/>
 
    <input type="submit" class="button" name="submit" value="Submit">
  </form>
  
</div>
</body>
<?php include(SHARED_PATH . '/footer.php'); ?>