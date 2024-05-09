<nav class="navbar navbar-expand-lg bg-body">
 <div class="container">
    <a class="navbar-brand" href="dashboard.php"><?php echo lang("HOME"); ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
      aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="categories.php"><?php echo lang("Categories"); ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="items.php"><?php echo lang("ITEMS"); ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="members.php?action=Manage&userid=<?php echo $_SESSION['user_id']?>"><?php echo lang("MEMBERS"); ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><?php echo lang("STATISTICS"); ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><?php echo lang("LOGS"); ?></a>
        </li>
      </ul>
        <li class="nav-item dropdown navbar-nav user-dropdown">
          <a class="nav-link dropdown-toggle green" href="#" role="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <?php echo $_SESSION['user_name']; ?>
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="members.php?action=edit&userid=<?php echo $_SESSION['user_id']?>"><?php echo lang("EDIT_PROFILE"); ?></a></li>
            <li><a class="dropdown-item" href="#"><?php echo lang("SETTINGS"); ?></a></li>
            <li><a class="dropdown-item" href="logout.php"><?php echo lang("LOGOUT"); ?></a></li>
          </ul>
        </li>
    </div>
 </div>
</nav>