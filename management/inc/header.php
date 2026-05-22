<?php include_once("../adminsession.php");
$header_unit_name = $obj->getvalfield("unit_master", "unit_name", "unit_id='$unitid'");

// echo $unitid;
// die;

?>
<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="index.php" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="assets/images/logo-sm.png" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="assets/images/logo-dark.png" alt="" height="17">
                        </span>
                    </a>

                    <a href="index.php" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="assets/images/logo-sm.png" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="assets/images/logo-light.png" alt="" height="17">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger material-shadow-none" id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
            </div>
            <div style="min-width: 600px;">


                <?php if ($pagename == 'dashboard.php') { ?>
                    <table class="table mb-0 table-borderless table-sm" style="vertical-align: middle;">
                        <tr>
                            <th class="text-end">Session</th>
                            <td> <select id="header_session_id" class="form-select form-select-sm w-lg " onchange="changeHeaderData()">
                                    <?php $header_session_data_res = $obj->executequery("Select * from m_session order by sessionid asc");
                                    foreach ($header_session_data_res as $session_data_res) { ?>
                                        <option value="<?= $session_data_res['sessionid']; ?>">
                                            <?= $session_data_res['session_name']; ?> </option>
                                    <?php } ?>
                                </select>
                                <script>
                                    document.getElementById('header_session_id').value =
                                        '<?= $header_session_id; ?>';
                                </script>
                            </td>
                            <th class="text-end">Unit Name</th>
                            <td> <select id="header_unit_id_check" class="form-select form-select-sm w-lg " onchange="changeHeaderData()">
                                    <?php $header_unit_data_res = $obj->executequery("Select * from unit_master order by unit_id desc");
                                    foreach ($header_unit_data_res as $unit_data_res) { ?>
                                        <option value="<?= $unit_data_res['unit_id']; ?>">
                                            <?= $unit_data_res['unit_name']; ?> </option>
                                    <?php } ?>
                                </select>
                                <script>
                                    document.getElementById('header_unit_id_check').value =
                                        '<?= $header_unit_id; ?>';
                                </script>
                            </td>
                        </tr>
                    </table>
                <?php } else { ?>
                    <h4 class="mt-3 nowrap"><?= $header_unit_name; ?> </h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?php } ?>
            </div>

            <div class="d-flex align-items-center">
                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user" src="assets/images/users/avatar-1.jpg" alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text"><?php echo ucfirst($obj->getvalfield("user", "username", "userid='$loginid'")); ?></span>
                                <span class="d-none d-xl-block ms-1 fs-12 user-name-sub-text"><?php echo ($obj->getvalfield("user", "usertype", "userid='$loginid'") == "management") ? "HR" : "Owner"; ?></span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <h6 class="dropdown-header">Welcome <?php echo $obj->getvalfield("user", "username", "userid='$loginid'"); ?>!</h6>
                        <a class="dropdown-item" href="logout.php"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Logout</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>