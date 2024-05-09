<?php

session_start();
$pageTitle = 'Items';


if (isset($_SESSION['user_name'])) {
  include('init.php');
  $action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

  if ($action == 'Manage') { // manage page
  } elseif ($action == 'add') {  // add page  
?>
    <h1 class="text-center">Add New Item</h1>
    <div class="container">
      <form class="form-horizontal" action="?action=insert" method="POST">
        <!-- Start Name Field -->
        <div class="form-group form-group-lg">
          <label class="col-sm-2 control-label">Name</label>
          <div class="col-sm-10 col-md-6">
            <input type="text" name="name" class="form-control" placeholder="Name of Item">
          </div>
        </div>
        <!-- End Name Field -->
        <!-- Start Description Field -->
        <div class="form-group form-group-lg">
          <label class="col-sm-2 control-label">Description</label>
          <div class="col-sm-10 col-md-6">
            <input type="text" name="description" class="form-control" placeholder="Description of Item">
          </div>
        </div>
        <!-- End Description Field -->
        <!-- Start Price Field -->
        <div class="form-group form-group-lg">
          <label class="col-sm-2 control-label">Price</label>
          <div class="col-sm-10 col-md-6">
            <input type="text" name="price" class="form-control" placeholder="Price of Item">
          </div>
        </div>
        <!-- End Price Field -->
        <!-- Start Country Made Field -->
        <div class="form-group form-group-lg">
          <label class="col-sm-2 control-label">Country Of Item</label>
          <div class="col-sm-10 col-md-6">
            <input type="text" name="country_made" class="form-control" placeholder="Country of Item">
          </div>
        </div>
        <!-- End Country Made Field -->
        <!-- Start Status Field -->
        <div class="form-group form-group-lg">
          <label class="col-sm-2 control-label">Status</label>
          <div class="col-sm-10 col-md-6">
            <select name="status" class="style">
              <option value="0">...</option>
              <option value="1">New</option>
              <option value="2">Like New</option>
              <option value="3">Used</option>
              <option value="4">Old</option>
            </select>
          </div>
        </div>
        <!-- End Status Field -->
        <!-- Start Members Field -->
        <div class="form-group form-group-lg">
          <label class="col-sm-2 control-label">Member</label>
          <div class="col-sm-10 col-md-6">
            <select name="member" class="style">
              <option value="0">...</option>
              <?php
              $stmt = $connect->prepare("SELECT * FROM users");
              $stmt->execute();
              $users = $stmt->fetchAll();
              foreach ($users as $user) {
                echo "<option value='" . $user['user_id'] . "'>" . $user['user_name'] . "</option>";
              }
              ?>
            </select>
          </div>
        </div>
        <!-- End Members Field -->
        <!-- Start Categories Field -->
        <div class="form-group form-group-lg">
          <label class="col-sm-2 control-label">Category</label>
          <div class="col-sm-10 col-md-6">
            <select name="category" class="style">
              <option value="0">...</option>
              <?php
              $stmt2 = $connect->prepare("SELECT * FROM categories");
              $stmt2->execute();
              $cats = $stmt2->fetchAll();
              foreach ($cats as $cat) {
                echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
              }
              ?>
            </select>
          </div>
        </div>
        <!-- End Categories Field -->
        <!-- Start Submit Field -->
        <div class="form-group form-group-lg">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-plus"> </i> Add Item</button>
          </div>
        </div>
        <!-- End Submit Field -->
      </form>
    </div>

  <?php } elseif ($action == 'insert') {
    // insert page
    // echo 'Welcome to Insert Item Page';
  ?>
    <h1 class="text-center">Insert Item</h1>
    <div class="container">

      <?php
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get Variables From The Form
        $name = $_POST['name'];
        $desc = $_POST['description'];
        $price = $_POST['price'];
        $country = $_POST['country_made'];
        $status = $_POST['status'];
        $member = $_POST['member'];
        $cat = $_POST['category'];
        // Validate The Form
        $formErrors = array();
        if (empty($name)) {
          $formErrors[] = '<div> Name Can\'t Be <strong>Empty</strong></div>';
        }
        if (empty($desc)) {
          $formErrors[] = '<div> Description Can\'t Be <strong>Empty</strong> </div>';
        }
        if (empty($price)) {
          $formErrors[] = '<div> Price Can\'t Be <strong>Empty</strong> </div>';
        }
        if (empty($country)) {
          $formErrors[] = '<div> Country Can\'t Be <strong>Empty</strong> </div>';
        }
        if ($status == 0) {
          $formErrors[] = '<div> You Must Choose The <strong>Status</strong> </div>';
        }
        if ($member == 0) {
          $formErrors[] = '<div> You Must Choose The <strong>Member</strong> </div>';
        }
        if ($cat == 0) {
          $formErrors[] = '<div> You Must Choose The <strong>Category</strong> </div>';
        }
        foreach ($formErrors as $error) {
          echo '<div class="alert bg-warning">' . $error . '</div>';
        }


        if (empty($formErrors)) {
          // check if the item exists in the database
          $check = checkItem('Name', 'items', $name, $connect);
          if ($check == 1) {
            $theMsg = '<div class="alert bg-danger">Sorry This Item Is Exist</div>';
            redirectHome($theMsg, 'back', 4);
          } else {
            $stmt = $connect->prepare("INSERT INTO items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID) VALUES(:the_name, :the_desc, :the_price, :the_country, :the_status, now(), :the_cat, :the_member)");
            $stmt->execute(
              array(
                'the_name' => $name,
                'the_desc' => $desc,
                'the_price' => $price,
                'the_country' => $country,
                'the_status' => $status,
                'the_cat' => $cat,
                'the_member' => $member
              )
            );
            echo '<div class="alert bg-success">' . $stmt->rowCount() . ' Record Inserted</div>';
            redirectHomeSuccess('', 'items.php', 4, 'Items');
          }
        } else {
          echo '<div class="alert bg-warning">There was an error adding the item.</div>';
          redirectHome('', 'items.php?action=add', 4, 'Add items');
        }
      } else {
        echo '<div class="alert bg-warning"> Sorry You Can\'t Browse This Page Directly </div>';
        redirectHome($theMsg, 'dashboard.php', 4);
      }
      ?>
    </div>
<?php
  } elseif ($action == 'edit') { // edit page
    echo 'Welcome to Edit Item Page';
  } elseif ($action == 'update') { // update page
    echo 'Welcome to Update Item Page';
  } elseif ($action == 'delete') { // delete page
    echo 'Welcome to Delete Item Page';
  } elseif ($action == 'approve') { // approve page
    echo 'Welcome to Approve Item Page';
  }
  include $temp . 'footer.php'; // footer
} else {
  header('Location: index.php');  // redirect to the dashboard page
  exit();
}
