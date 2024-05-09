<?php
session_start();
$noNavBar = '';
$pageTitle = 'Login'; 
if (isset($_SESSION['user_name'])):
  header('Location: dashboard.php');
  exit();
endif;
include ('init.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST'):
  $username = $_POST['username'];
  $password = $_POST['password'];
  $hashedPass = sha1($password);

  $stmt = $connect->prepare(('SELECT user_id, user_name, pass FROM users WHERE user_name = ? AND pass = ? AND group_id = 1 LIMIT 1'));
  $stmt->execute(array($username, $hashedPass));
  $row = $stmt->fetch();
  $Count = $stmt->rowCount();


  if ($Count > 0):
    // print_r($row);

    $_SESSION['user_name'] = $username;  // register session name
    $_SESSION['user_id'] = $row['user_id'];     // register session id

    header('Location: dashboard.php');
    exit();
  endif;

endif;

?>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="login d-grid gap-2">
  <h3 class="text-center"><?php echo lang("ADMIN_LOGIN") ?></h3>
  <input class="form-control" id="username" type="text" name="username" placeholder="<?php echo lang("ADMIN_USER") ?>"
    autocomplete="off">
  <input class="form-control" id="password" type="password" name="password"
    placeholder="<?php echo lang("ADMIN_PASS") ?>" autocomplete=" new-password">
  <input class="btn btn-primary btn-block" type="submit" value="<?php echo lang('LOG_BTN') ?>" />
</form>
<?php include $temp . ('footer.php'); ?>