<?php
    ob_start(); // Output Buffering Start
    session_start();
    if (isset($_SESSION['user_name'])) {

      $pageTitle = 'Dashboard';
      include('init.php');
      // print_r($_SESSION);
      /* Start Dashboard Page */

      // Number Of Latest Users
      $latestUsers = 7;
      // Get Latest Users
      $theLatest = getLatest("*", "users", "user_id", $latestUsers, $connect);

    ?>
      <div class="home-stats">
        <div class="container home-stats text-center ">
          <h1 class="text-center">Dashboard</h1>
          <div class="row">
            <div class="col-md-3">
              <div class="stat st-members">
                Total Members
                <span> <a href="members.php"><?php echo countItems('user_id', 'users', $connect) ?></a> </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stat  st-pending">
                Pending Members
                <span><a href="members.php?action=Manage&page=pending"><?php echo checkItem('reg_status', 'users', 0, $connect); ?></a></span></a></span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stat st-items">
                Total Items
                <span>15</span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stat st-comments">
                Total Comments
                <span>3500</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- #region-->
      <div class="latest">
        <div class="container latest">
          <div class="row">
            <div class="col-sm-6">
              <div class="panel panel-default">

                <div class="panel-heading">
                  <i class="fa fa-users"></i> Latest <?php echo $latestUsers ?> Registered Users
                </div>
                <div class="panel-body">
                  <ul class="list-unstyled latest-users">
                      <?php
                      foreach ($theLatest as $user) {
                        echo '<li>';
                        echo $user['user_name'];
                        echo '<a href="members.php?action=edit&userid=' . $user['user_id'] . '">';
                        echo '<span class="btn btn-success pull-right">';
                        echo '<i class="fa fa-edit"></i> Edit';

                        if ($user['reg_status'] == 0) {
                          echo '<a href="members.php?action=activate&userid=' . $user['user_id'] . '" class="btn btn-info pull-right activate"><i class="fa fa-check"></i> Activate</a>';
                        }

                        echo '</span>';
                        echo '</a>';
                        echo '</li>';
                        }
                  ?>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <i class="fa fa-tag"></i> Latest Items
                </div>
                <div class="panel-body">
                  Test
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- #endregion-->


    <?php
      /* End Dashboard Page */
      include $temp . ('footer.php');
    } else {
      header('Location: index.php');
      exit();
    }
    ob_end_flush(); // Release The Output
?>