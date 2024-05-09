<?php

ob_start(); // Output Buffering Start
session_start();
$pageTitle = 'Members';

// After the update operation
$_SESSION['update_success'] = true;


if (isset($_SESSION['user_name'])) {
  include 'init.php';
  $action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

  if ($action == 'Manage') {  // Manage page 
    // echo 'Welcome to the manage page';

    $query = '';
    if (isset($_GET['page']) && $_GET['page'] == 'pending') {
      $query = 'AND reg_status = 0';
    } else {
      $query = '';
    }
    // echo $query;
?>

    <div class="container">
      <h1 class="text-center">Manage Members</h1>
      <table class="table table-bordered table-responsive table-hover">
        <tr class=" main">
          <td>#ID</td>
          <td>Username</td>
          <td>Email</td>
          <td>Full Name</td>
          <td>Register Status</td>
          <td>Control</td>
        </tr>
        <?php
        // Get all users from the database except the admin
        $stmt = $connect->prepare('SELECT * FROM users WHERE group_id != 1 ' . $query);
        // Execute the statement
        $stmt->execute();
        // Fetch all the data from the database and store it in the $rows variable
        $rows = $stmt->fetchAll();

        // Loop through the $rows array and display the data in the table
        foreach ($rows as $row) {
          echo '<tr>';
          echo '<td>' . $row['user_id'] . '</td>';
          echo '<td>' . $row['user_name'] . '</td>';
          echo '<td>' . $row['email'] . '</td>';
          echo '<td>' . $row['full_name'] . '</td>';
          echo '<td>' . $row['Date'] . '</td>';
          echo '<td>
                    <a href="members.php?action=edit&userid=' . $row['user_id'] . '" class="btn btn-success"> <i class=" fa fa-edit "></i>  Edit</a>
                    <a href="members.php?action=delete&userid=' . $row['user_id'] . '" class="btn btn-danger confirm" onclick="return confirm(\'Are you sure you want to delete this category?\')"> <i class=" fa fa-trash "></i>  Delete</a>';

          if ($row['reg_status'] == 0) {
            echo '<a href="members.php?action=activate&userid=' . $row['user_id'] . '" class="btn btn-info"> <i class=" fa fa-check "></i>  Activate</a>';
          }
          echo '</td>';
          echo '</tr>';
        }
        ?>
      </table>
      <a href="?action=add" class="btn btn-primary"> <i class=" fa fa-plus "></i> Add New Member</a>
    </div>

  <?php } elseif ($action == 'edit') { // Edit page
    // Check if the user ID is valid and display the edit page if it is valid or show an error message if it is not valid
    $user = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
    // Select all data depend on this ID
    $stmt = $connect->prepare('SELECT * FROM users WHERE user_id = ? LIMIT 1');
    // Execute Query
    $stmt->execute(array($user));
    // Fetch the data
    $row = $stmt->fetch();
    // The row count
    $Count = $stmt->rowCount();

    // If there is such ID show the form
    if ($Count > 0) { ?>

      <h1 class="text-center">Edit Member</h1>
      <div class="container">
        <form class="form-horizontal" action="?action=update" method="POST">
          <input type="hidden" name="userid" value="<?php echo $user ?>">
          <!-- Start Username Field -->
          <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Username</label>
            <div class="col-sm-10 col-md-6">
              <input type="text" name="username" class="form-control required-field" value="<?php echo $row['user_name'] ?>" autocomplete="off" required="required">
            </div>
          </div>
          <!-- End Username Field -->
          <!-- Start Password Field -->
          <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Password</label>
            <div class="col-sm-10 col-md-6">
              <input type="hidden" name="oldpassword" value="<?php echo $row['pass'] ?>">
              <input type="password" name="newpassword" class="password form-control" autocomplete="new-password">

            </div>
          </div>
          <!-- End Password Field -->
          <!-- Start Email Field -->
          <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10 col-md-6">
              <input type="email" name="email" class="form-control required-field" value="<?php echo $row['email'] ?>" required="required">
            </div>
          </div>
          <!-- End Email Field -->
          <!-- Start Full Name Field -->
          <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Full Name</label>
            <div class="col-sm-10 col-md-6">
              <input type="text" name="full-name" class="form-control required-field" value="<?php echo $row['full_name'] ?>" required="required">
            </div>
          </div>
          <!-- End Full Name Field -->
          <!-- Start Submit Field -->
          <div class="form-group form-group-lg my-2">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" value="Save" class="btn btn-primary btn-lg"> 
                <i class=" fa fa-save "></i> Save Member</button>
            </div>
          </div>
          <!-- End Submit Field -->
        </form>
      </div>
    <?php
      // If there is no such ID show error message
    } else {
      echo  '<div class="alert bg-warning">There is no such ID</div>';
      redirectHome($theMsg, 'dashboard.php', 4);
    }
  } elseif ($action == 'update') { // Update page

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // echo 'Welcome to the update page';

      echo "<h1 class='text-center'>Update Member</h1>";
      // Get variables from the form
      $id = $_POST['userid'];
      $username = $_POST['username'];
      $email = $_POST['email'];
      $full_name = $_POST['full-name'];

      // echo $id . $username . $email . $full_name;

      // Update the database with this info
      // $stmt = $connect->prepare('UPDATE users SET user_name = ?, email = ?, full_name = ? WHERE user_id = ?');
      // $stmt->execute(array($username, $email, $full_name, $id));
      // Echo success message
      // echo $stmt->rowCount() . ' Record Updated';

      // Password trick to check if the user wants to change the password or not
      // check if the password is empty or not
      $password = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

      // Validate the form
      $formErrors = array();
      if (strlen($username) < 4) {
        $formErrors[] = '<div> Username Can\'t Be Less Than <strong> 4 Characters </strong></div>';
      }
      if (strlen($username) > 20) {
        $formErrors[] = '<div> Username Can\'t Be More Than <strong> 20 Characters </strong></div>';
      }

      if (empty($username)) {
        $formErrors[] = '<div> Username Can\'t  <strong> Be Empty </strong></div>';
      }
      if (empty($email)) {
        $formErrors[] = '<div> Email Can\'t  <strong> Be Empty </strong></div>';
      }
      if (empty($full_name)) {
        $formErrors[] = '<div> Full Name Can\'t  <strong> Be Empty </strong> </div> ';
      }
      // Loop into errors array and echo it
      foreach ($formErrors as $error) {
        echo '<div class="alert bg-warning">' . $error . '</div>';
      }
      // Check if there is no error proceed the update operation
      if (empty($formErrors)) {
        // Update the password in the database
        $stmt = $connect->prepare('UPDATE users SET user_name = ?, email = ?, full_name = ?, pass = ? WHERE user_id = ?');
        $stmt->execute(array($username, $email, $full_name, $password, $id));
        // Echo success message
        // echo $stmt->rowCount() . ' Record Updated';
        if (isset($_SESSION['update_success']) && $_SESSION['update_success'] && !$formErrors == true) {
          echo '<script>
              setTimeout(function() {
                  var alert = document.createElement("div");
                  alert.className = "alert bg-success text-center";
                  alert.setAttribute("role", "alert");
                  alert.innerHTML = "Update Successful!";
                  document.body.appendChild(alert);
                  setTimeout(function() {
                      document.body.removeChild(alert);
                  }, 2000); // Remove the alert after 2 seconds
              }, 100); // Wait for 100ms to ensure the DOM is ready
            </script>';
          unset($_SESSION['update_success']);
          redirectHomeSuccess($theMsg, 'members.php?action=Manage', 4, 'Members Page');
        }
      } else {
        echo '<div class="alert bg-warning">There was an error updating the user.</div>';
        redirectHome($theMsg, 'members.php?action=edit', 4, 'Edit Members Page');
      }
    } else {
      echo '<div class="alert bg-warning"> Sorry You Can\'t Browse This Page Directly </div>';
      redirectHome($theMsg, 'dashboard.php', 4);
    }
  } elseif ($action == 'add') { // add page 
    ?> 
    <div class="container">
      <h1 class="text-center">Add New Member</h1>
      <form class="form-horizontal" action="?action=insert" method="POST">
        <!-- Start Username Field -->
        <div class="form-group form-group-lg">
          <label class="col-sm-2 control-label">Username</label>
          <div class="col-sm-10 col-md-6">
            <input type="text" name="username" class="form-control" autocomplete="off" placeholder="username to login into shop" required="required">
          </div>
        </div>
        <!-- End Username Field -->
        <!-- Start Password Field -->
        <div class="form-group form-group-lg">
          <label class="col-sm-2 control-label">Password</label>
          <div class="col-sm-10 col-md-6 " style="position: relative;">
            <input type="password" name="password" class="password form-control" id="password" autocomplete="new-password" placeholder="password must be hard and coplex" required="required">
            <i class="show-pass fa fa-eye fa-2x"></i>
          </div>
        </div>
        <!-- End Password Field -->
        <!-- Start Email Field -->
        <div class="form-group form-group-lg">
          <label class="col-sm-2 control-label">Email</label>
          <div class="col-sm-10 col-md-6">
            <input type="email" name="email" class="form-control" placeholder="email must be valid" required="required">
          </div>
        </div>
        <!-- End Email Field -->
        <!-- Start Full Name Field -->
        <div class="form-group form-group-lg">
          <label class="col-sm-2 control-label">Full Name</label>
          <div class="col-sm-10 col-md-6">
            <input type="text" name="full-name" class="form-control" placeholder="full name appear in your profile page " required="required">
          </div>
        </div>
        <!-- End Full Name Field -->
        <!-- Start Submit Field -->
        <div class="form-group form-group-lg my-2">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary">
              <i class="fa fa-plus"> Add Member </i>
            </button>
          </div>
        </div>
        <!-- End Submit Field -->
      </form>
    </div>
  <?php
  } elseif ($action == 'insert') { // insert page

    // insert the new member into the database
    if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Check if the user is coming from a POST request
      echo "<h1 class='text-center'>Insert Member</h1>";

      // Get variables from the form
      $username = $_POST['username'];
      $password = $_POST['password'];
      $email = $_POST['email'];
      $full_name = $_POST['full-name'];
      $hashedPass = sha1($password);

      // Validate the form
      $formErrors = array();
      if (strlen($username) < 4) {
        $formErrors[] = '<div> Username Can\'t Be Less Than <strong> 4 Characters </strong></div>';
      }
      if (strlen($username) > 20) {
        $formErrors[] = '<div> Username Can\'t Be More Than <strong> 20 Characters </strong></div>';
      }

      if (empty($username)) {
        $formErrors[] = '<div> Username Can\'t  <strong> Be Empty </strong></div>';
      }
      if (empty($password)) {
        $formErrors[] = '<div> Password Can\'t  <strong> Be Empty </strong></div>';
      }
      if (empty($email)) {
        $formErrors[] = '<div> Email Can\'t  <strong> Be Empty </strong></div>';
      }
      if (empty($full_name)) {
        $formErrors[] = '<div> Full Name Can\'t  <strong> Be Empty </strong> </div> ';
      }
      
      // Loop into errors array and echo it
      foreach ($formErrors as $error) {
        echo '<div class="alert bg-warning">' . $error . '</div>';
      }
      // Check if there is no error proceed the update operation
      if (empty($formErrors)) {
        // Check if the user is already exist in the database
        $check = checkItem('user_name', 'users', $username, $connect);
        if ($check == 1) {
          $theMsg = '<div class="alert bg-warning">Sorry This User Is Exist</div>';
          redirectHome($theMsg, 'members.php?action=add', 4, 'Add Page');
        } else {
          // Insert the user info into the database
          $stmt = $connect->prepare('INSERT INTO users(user_name, pass, email, full_name, reg_status, Date) VALUES(:the_user, :the_pass, :the_mail, :the_name, 1, NOW())');
          $stmt->execute(array(
            'the_user' => $username,
            'the_pass' => $hashedPass,
            'the_mail' => $email,
            'the_name' => $full_name
          ));
          // Echo success message
          echo '<div class="alert bg-success">' . $stmt->rowCount() . ' User added successfully. ' . '</div>';
          redirectHomeSuccess($theMsg, 'members.php?action=Manage', 4, 'Members Page');
        }
      } else {
        echo '<div class="alert bg-warning">There was an error adding the user.</div>';
        redirectHome($theMsg, 'members.php?action=add', 4, 'Add Members Page');
      }
    } else {
      echo '<div class="alert bg-warning"> Sorry You Can\'t Browse This Page Directly </div>';
      redirectHome($theMsg, 'dashboard.php', 4);
    }
  } elseif ($action == 'delete') { // delete page
    ?>
    <h1 class="text-center">Delete Member</h1>
    <div class="container">
      <?php
      // Check if the user ID is valid and display the delete page if it is valid or show an error message if it is not valid
      $user = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
      // Select all data depend on this ID
      $check = checkItem('user_id', 'users', $user, $connect);
      // If there is such ID show the form
      if ($check > 0) {
        // Delete the user from the database
        $stmt = $connect->prepare('DELETE FROM users WHERE user_id = :user');
        $stmt->bindParam(':user', $user);
        $stmt->execute();
        echo '<div class="alert bg-success">' . $stmt->rowCount() . ' Record Deleted' . '</div>';
        redirectHomeSuccess($theMsg, 'members.php?action=Manage', 4, 'Members Page');
      } else {
        echo '<div class="alert bg-warning">This ID is not exist</div>';
        redirectHome($theMsg, 'dashboard.php', 4);
      }
      ?>
    </div>
    ?>
    </div>
  <?php } elseif ($action == 'activate') { // activate page
    ?>
    <h1 class="text-center">Activate Member</h1>
    <div class="container">
      <?php
      // Check if the user ID is valid and display the delete page if it is valid or show an error message if it is not valid
      $user = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
      // Select all data depend on this ID
      $check = checkItem('user_id', 'users', $user, $connect);
      // If there is such ID show the form
      if ($check > 0) {
        // Activate the user from the database
        $stmt = $connect->prepare('UPDATE users SET reg_status = 1 WHERE user_id = ?');
        $stmt->execute(array($user));
        echo '<div class="alert bg-success">' . $stmt->rowCount() . ' Record Activated' . '</div>';
        redirectHomeSuccess($theMsg, 'members.php?action=Manage', 4, 'Members Page');
      } else {
        echo '<div class="alert bg-warning">This ID is not exist</div>';
        redirectHome($theMsg, 'dashboard.php', 4);
      }
      ?>
    </div>
    ?>
    </div>
<?php
  }
  include $temp . 'footer.php';
} else {
  header('Location: index.php');
}
?>