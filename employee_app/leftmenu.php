<ul id="nav-mobile-category" class="side-nav" style="background: linear-gradient(rgb(214 216 224), rgb(246, 247, 251), rgb(219 220 242));

">

  <li class="profile" style="padding: 10px 10px;border-bottom: 1px solid #cbd4ce;

    background-color: #15295f;">

    <div class="li-profile-info">



      <?php

      $username = $obj->getvalfield("employee_master", "first_name", "emp_id='$loginid'");

      $m_mobile = $obj->getvalfield("employee_master", "mobile_no", "emp_id='$loginid'");

      ?>

      <!-- <img src="image/favicon1.png" alt="profile"> -->

      <h5 style="font-size:1.5rem;text-transform:capitalize;margin-top:30px;"><?php echo $username; ?></h5>

      <h6 style="margin:0px;height:40px;line-height: inherit;">
        <!-- <i class="fa fa-phone fa-flip-horizontal" style="font-size:15px;"></i> <?php //echo $m_mobile;

                                                                                    ?> -->
      </h6>

    </div>

  </li>

  <li>

    <a class="waves-effect waves-blue" href="dashboard.php"><i class="fas fa-home"></i>Home</a>

  </li>





  <li>

    <a href="setting.php"><i class="fas fa-user"></i>My Profile</a>

  </li>

  <?php if (isset($_SESSION['type']) && $_SESSION['type'] == "admin") { ?>

    <li>

      <a href="employee_master.php"><i class="fas fa-user-plus"></i>Add Employee</a>

    </li>

  <?php } ?>







  <?php if (isset($_SESSION['type']) && $_SESSION['type'] == "admin") { ?>

    <!--  <li>

      <a href="attandance_list.php"><i class="fas fa-check"></i>Attendance Entry</a>

    </li> -->







  <?php } ?>



  <li>

    <a href="attandance_details.php"><i class="fas fa-edit"></i>Attendance Details</a>

  </li>

  <!-- <li>

    <a href="emp_attendance_count_monthwise.php"><i class="fas fa-edit"></i>Attendance-Monthly Summary</a>

  </li> -->

  <!-- <li>

    <a href="emp_latlong_att.php"><i class="fas fa-edit"></i>Employee Attendance</a>



  </li> -->

  <li>

    <a href="change-password.php"><i class="fas fa-book"></i>Change Password</a>

  </li>

  <li>

    <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Sign Out</a>

  </li>

</ul>