<?php

ob_start(); // Output Buffering Start
session_start();
$pageTitle = 'Categories';

if (isset($_SESSION['user_name'])) {

  include('init.php');
  $action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

  if ($action == 'Manage') { // Manage Page 
    // Sort Categories
    $sort = 'ASC'; // Default to ascending order

    // Check if the user has selected a different sorting option
    if (isset($_GET['sort'])) {
      $sort = $_GET['sort']; // Use the user's selection
    }

    // Determine the column to sort by. This is a simplified example.
    // In a real application, you should validate this input to prevent SQL injection.
    $sortColumn = 'ID'; // Default to sorting by ID
    if (isset($_GET['sortColumn'])) {
      $sortColumn = $_GET['sortColumn']; // Use the user's selection
    }

    // Prepare the SQL query with the ORDER BY clause
    $stmt = $connect->prepare("SELECT * FROM categories ORDER BY $sortColumn $sort");
    $stmt->execute();
    $cats = $stmt->fetchAll();

    // Display the categories
    if (!empty($cats)) {
?>
      <h1 class="text-center">Manage Categories</h1>
      <div class="container categories">
        <div class="panel panel-default">
          <div class="panel-heading">Manage Categories
            <div class="ordering pull-right">
              Ordering:
              <a class="<?php if ($sort == 'ASC') {
                          echo 'active';
                        } ?>" href="?sort=ASC&sortColumn=<?php echo $sortColumn; ?>">ASC</a> |
              <a class="<?php if ($sort == 'DESC') {
                          echo 'active';
                        } ?>" href="?sort=DESC&sortColumn=<?php echo $sortColumn; ?>">DESC</a>
            </div>
          </div>
          <div class="panel-body">
            <?php foreach ($cats as $cat) : ?>
              <div class="cat">
                <div class="hidden-buttons">
                  <a href="?action=edit&catid=<?= $cat['ID']; ?>" class="btn btn-xs btn-success"><i class="fa fa-edit"></i> Edit</a>
                  <a href="?action=delete&catid=<?= $cat['ID']; ?>" class="confirm btn btn-xs btn-danger" onclick="return confirm('Are you sure you want to delete this category?')"><i class="fa fa-close"></i> Delete</a>
                </div>
                <input type="checkbox" id="toggle-<?= $cat['ID']; ?>" class="toggle-checkbox">
                <label for="toggle-<?= $cat['ID']; ?>" class="category-name"><?= $cat['Name']; ?></label>
                <div class="full-view">
                  <?php if (!empty($cat['Description'])) : ?>
                    <p><?= $cat['Description']; ?></p>
                  <?php endif; ?>
                  <?php if ($cat['Visibility'] == 1) : ?>
                    <span class="visibility"><i class="fa fa-eye"></i> Hidden</span>
                  <?php endif; ?>
                  <?php if ($cat['Allow_Comment'] == 1) : ?>
                    <span class="commenting"><i class="fa fa-close"></i> Comment Disabled</span>
                  <?php endif; ?>
                  <?php if ($cat['Allow_Ads'] == 1) : ?>
                    <span class="ads"><i class="fa fa-close"></i> Ads Disabled</span>
                  <?php endif; ?>
                </div>
              </div>
              <hr>
            <?php endforeach; ?>

          </div>
          <a href="?action=add" class="btn btn-primary add-category"><i class="fa fa-plus "></i> Add New Category</a>
        </div>
      <?php
    } else {
      echo '<div class="container">';
      echo '<div class="alert bg-warning">There\'s No Categories To Show</div>';
      redirectHome('', 'categories.php?action=add', 3, 'Add New Category');
      echo '</div>';
    } ?>
      </div>
      </div>  
    <?php

  } elseif ($action == 'add') { // Add Page 
      ?>
      <h1 class="text-center">Add New Category</h1>
      <div class="container">
        <form class="form-horizontal" action="?action=insert" method="POST">
          <!-- Start Name Field -->
          <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Name</label>
            <div class="col-sm-10 col-md-6">
              <input type="text" name="name" class="form-control" required="required" placeholder="Name Of The Category">
            </div>
          </div>
          <!-- End Name Field -->
          <!-- Start Description Field -->
          <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Description</label>
            <div class="col-sm-10 col-md-6">
              <input type="text" name="description" class="form-control" require="required" placeholder="Description Of The Category" >
            </div>
          </div>
          <!-- End Description Field -->
          <!-- Start Ordering Field -->
          <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Ordering</label>
            <div class="col-sm-10 col-md-6">
              <input type="number" name="ordering" class="form-control" placeholder="Number To Arrange The Categories">
            </div>
          </div>
          <!-- End Ordering Field -->
          <!-- Start Visibility Field -->
          <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Visible</label>
            <div class="col-sm-10 col-md-6 form-control" style="width: 50%; color: darkgreen; font-weight:bold;">
              <div>
                <input id="vis-yes" type="radio" name="visibility" value="0" checked>
                <label for="vis-yes">Yes</label>
              </div>
              <div>
                <input id="vis-no" type="radio" name="visibility" value="1">
                <label for="vis-no">No</label>
              </div>
            </div>
          </div>
          <!-- End Visibility Field -->
          <!-- Start Commenting Field -->
          <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Allow Commenting</label>
            <div class="col-sm-10 col-md-6 form-control" style="width: 50%; color: darkgreen; font-weight:bold;">
              <div>
                <input id="com-yes" type="radio" name="commenting" value="0" checked>
                <label for="com-yes">Yes</label>
              </div>
              <div>
                <input id="com-no" type="radio" name="commenting" value="1">
                <label for="com-no">No</label>
              </div>
            </div>
          </div>
          <!-- End Commenting Field -->
          <!-- Start Ads Field -->
          <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Allow Ads</label>
            <div class="col-sm-10 col-md-6 form-control" style="width: 50%; color: darkgreen; font-weight:bold;">
              <div>
                <input id="ads-yes" type="radio" name="ads" value="0" checked>
                <label for="ads-yes">Yes</label>
              </div>
              <div>
                <input id="ads-no" type="radio" name="ads" value="1">
                <label for="ads-no">No</label>
              </div>
            </div>
          </div>
          <!-- End Ads Field -->
          <!-- Start Submit Field -->
          <div class="form-group form-group-lg">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-primary btn-lg">
                <i class="fa fa-plus"></i> Add Category</button>
            </div>
          </div>
          <!-- End Submit Field -->
        </form>
      </div>
    <?php  } elseif ($action == 'insert') { // Insert Page 
    ?>
      <h1 class="text-center">Insert Category</h1>
      <div class="container">
        <?php


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          // get variables from the form
          $name = $_POST['name'];
          $desc = $_POST['description'];
          $order = $_POST['ordering'];
          $visible = $_POST['visibility'];
          $comment = $_POST['commenting'];
          $ads = $_POST['ads'];

          $formErrors = array();
          if (empty($name)) {
            $formErrors[] = 'Name Can\'t Be Empty';
          }
          if (empty($desc)) {
            $formErrors[] = 'Description Can\'t Be Empty';
          }
          foreach ($formErrors as $error) {
            echo '<div class="alert bg-warning">' . $error . '</div>';
          }
          
          if (empty($formErrors)) {
          // Check If Category Exist In Database
            $check = checkItem('Name', 'categories', $name, $connect);
            if ($check == 1) {
              $msg = '<div class="alert bg-warning">Sorry This Category Is Exist</div>';
              redirectHome($msg, 'categories.php?action=add', 3);
            } else {
            // Insert Category Info In Database
            $stmt = $connect->prepare("INSERT INTO categories(Name, Description, Ordering, Visibility, Allow_Comment, Allow_Ads) 
                    VALUES(:the_name, :the_desc, :the_order, :the_visible, :the_comment, :the_ads)");
            $stmt->execute(
              array(
                'the_name' => $name,
                'the_desc' => $desc,
                'the_order' => $order,
                'the_visible' => $visible,
                'the_comment' => $comment,
                'the_ads' => $ads
              )
            );
            $msg = '<div class="alert bg-success">' . $stmt->rowCount() . ' Record Inserted</div>';
            redirectHomeSuccess($msg, 'categories.php?action=Manage', 3, 'Categories');
          }
        } else {
          echo '<div class="alert bg-warning">There was an error adding category.</div>';
          redirectHome($theMsg, 'categories.php?action=add', 4, 'Add category Page');
        }
      }else {
          $msg = '<div class="alert bg-warning">Sorry You Cant Browse This Page Directly</div>';
          redirectHome($msg, 'dashboard.php', 3);
        }
        ?>
      </div>


      <?php
    } elseif ($action == 'edit') { // Edit Page
      // Check If Get Request Catid Is Numeric & Get The Integer Value Of It
      $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
      // Select All Data Depend On This ID
      $stmt = $connect->prepare("SELECT * FROM categories WHERE ID = ?");
      // Execute Query
      $stmt->execute(array($catid));
      // Fetch The Data
      $cat = $stmt->fetch();
      // The Row Count
      $count = $stmt->rowCount();
      // If There's Such ID Show The Form
      if ($count > 0) {
      ?>
        <h1 class="text-center">Edit Category</h1>
        <div class="container">
          <form class="form-horizontal" action="?action=update" method="POST">
            <input type="hidden" name="catid" value="<?php echo $catid ?>">
            <!-- Start Name Field -->
            <div class="form-group form-group-lg">
              <label class="col-sm-2 control-label">Name</label>
              <div class="col-sm-10 col-md-6">
                <input type="text" name="name" class="form-control" required="required" placeholder="Name Of The Category" value="<?php echo $cat['Name'] ?>">
              </div>
            </div>
            <!-- End Name Field -->
            <!-- Start Description Field -->
            <div class="form-group form-group-lg">
              <label class="col-sm-2 control-label">Description</label>
              <div class="col-sm-10 col-md-6">
                <input type="text" name="description" class="form-control" placeholder="Description Of The Category" value="<?php echo $cat['Description'] ?>">
              </div>
            </div>
            <!-- End Description Field -->
            <!-- Start Ordering Field -->
            <div class="form-group form-group-lg">
              <label class="col-sm-2 control-label">Ordering</label>
              <div class="col-sm-10 col-md-6">
                <input type="number" name="ordering" class="form-control" placeholder="Number To Arrange The Categories" value="<?php echo $cat['Ordering'] ?>">
              </div>
            </div>
            <!-- End Ordering Field -->
            <!-- Start Visibility Field -->
            <div class="form-group form-group-lg">
              <label class="col-sm-2 control-label">Visible</label>
              <div class="col-sm-10 col-md-6 form-control" style="width: 50%; color: darkgreen; font-weight:bold;">
                <div>
                  <input id="vis-yes" type="radio" name="visibility" value="0" <?php if ($cat['Visibility'] == 0) {
                                                                                  echo 'checked';
                                                                                } ?>>
                  <label for="vis-yes">Yes</label>
                </div>
                <div>
                  <input id="vis-no" type="radio" name="visibility" value="1" <?php if ($cat['Visibility'] == 1) {
                                                                                echo 'checked';
                                                                              } ?>>
                  <label for="vis-no">No</label>
                </div>
              </div>
            </div>
            <!-- End Visibility Field -->
            <!-- Start Commenting Field -->
            <div class="form-group form-group-lg">
              <label class="col-sm-2 control-label">Allow Commenting</label>
              <div class="col-sm-10 col-md-6 form-control" style="width: 50%; color: darkgreen; font-weight:bold;">
                <div>
                  <input id="com-yes" type="radio" name="commenting" value="0" <?php if ($cat['Allow_Comment'] == 0) {
                                                                                  echo 'checked';
                                                                                } ?>>
                  <label for="com-yes">Yes</label>
                </div>
                <div>
                  <input id="com-no" type="radio" name="commenting" value="1" <?php if ($cat['Allow_Comment'] == 1) {
                                                                                echo 'checked';
                                                                              } ?>>
                  <label for="com-no">No</label>
                </div>
              </div>
            </div>
            <!-- End Commenting Field -->
            <!-- Start Ads Field -->
            <div class="form-group form-group-lg">
              <label class="col-sm-2 control-label">Allow Ads</label>
              <div class="col-sm-10 col-md-6 form-control" style="width: 50%; color: darkgreen; font-weight:bold;">
                <div>
                  <input id="ads-yes" type="radio" name="ads" value="0" <?php if ($cat['Allow_Ads'] == 0) {
                                                                          echo 'checked';
                                                                        } ?>>
                  <label for="ads-yes">Yes</label>
                </div>
                <div>
                  <input id="ads-no" type="radio" name="ads" value="1" <?php if ($cat['Allow_Ads'] == 1) {
                                                                          echo 'checked';
                                                                        } ?>>
                  <label for="ads-no">No</label>
                </div>
              </div>
            </div>
            <!-- End Ads Field -->
            <!-- Start Submit Field -->
            <div class="form-group form-group-lg">
              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary btn-lg">
                  <i class="fa fa-save "></i> Save Category</button>
              </div>
            </div>
            <!-- End Submit Field -->
          </form>
        </div>
  <?php
      } else {
        $msg = '<div class="alert bg-warning">There Is No Such ID</div>';
        redirectHome($msg, 'categories.php', 3, 'Categories');
      }
    } elseif ($action == 'update') { // Update Page
      // Check If User Is Coming From A Request
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get Variables From The Form
        $id = $_POST['catid'];
        $name = $_POST['name'];
        $desc = $_POST['description'];
        $order = $_POST['ordering'];
        $visible = $_POST['visibility'];
        $comment = $_POST['commenting'];
        $ads = $_POST['ads'];
        // Update The Database With This Info
        $stmt = $connect->prepare("UPDATE categories SET Name = ?, Description = ?, Ordering = ?, Visibility = ?, Allow_Comment = ?, Allow_Ads = ? WHERE ID = ?");
        $stmt->execute(array($name, $desc, $order, $visible, $comment, $ads, $id));
        // Echo Success Message
        $msg = '<div class="alert bg-success">' . $stmt->rowCount() . ' Record Updated</div>';
        redirectHomeSuccess($msg, 'categories.php?action=Manage', 3, 'Categories');
      } else {
        $msg = '<div class="alert bg-warning">Sorry You Cant Browse This Page Directly</div>';
        redirectHome($msg, 'dashboard.php', 3);
      }
    } elseif ($action == 'delete') { // Delete Page
      // Check if the category ID is set and numeric
      $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
      if ($catid > 0) {
        // Prepare the SQL statement to delete the category
        $stmt = $connect->prepare("DELETE FROM categories WHERE ID = ?");
        // Execute the statement with the category ID
        $stmt->execute(array($catid));
        // Redirect to the categories page with a success message
        $msg = '<div class="alert bg-success">Category Deleted Successfully</div>';
        redirectHomeSuccess($msg, 'categories.php?action=Manage', 3, 'Categories');
      } else {
        // Redirect to the categories page with an error message if the category ID is not valid
        $msg = '<div class="alert bg-warning">Invalid Category ID</div>';
        redirectHome($msg, 'categories.php?action=Manage', 3, 'Categories');
      }
    }
    include $temp . 'footer.php';
  } else {
    header('Location: index.php');
    exit();
  }
