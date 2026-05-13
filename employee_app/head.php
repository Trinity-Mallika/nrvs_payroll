<?php

if (isset($_SESSION['empid'])) {
  $empid = $_SESSION['empid'];
} else {
  $empid = "";
}
?>
<header id="header" class="header-innerpage "
  style="background:  #015c3d;  background: linear-gradient(to right, #15295f, #1b2b56);">
  <div class="nav-wrapper container">
    <div class="header-menu-button">
      <a href="#" data-activates="nav-mobile-category" class="button-collapse" id="button-collapse-category">
        <div class="cst-btn-menu" style="color:white;">
          <i class="fas fa-align-left"></i>
        </div>
      </a>
    </div>
    <div class="header-logo">
      <a href="dashboard.php" class="nav-logo" style="color:white;">
        Employee Attendance
      </a>
    </div>
    <div class="header-icon-menu">
      <a href="logout.php" class="button-collapse"><i class="fas fa-power-off" style="color:white;"></i></a>

    </div>
  </div>
</header>