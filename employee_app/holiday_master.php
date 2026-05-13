<?php include("appsession.php");
//print_r($_SESSION);die;
$pagename = "holiday_master.php";
$tblname = "m_holiday";
$tblpkey = "holiday_id";
$btn_name = "Submit";
$keyvalue = 0;
if (isset($_GET['holiday_id'])) {
  $keyvalue = $obj->test_input($_GET['holiday_id']);
} else {
  $keyvalue = 0;
}
$holiday_date = date('Y-m-d');
$holiday_title = "";
if (isset($_POST['submit'])) {

  //print_r($_POST);die;
  $holiday_title = $obj->test_input($_POST['holiday_title']);
  $holiday_date = $_POST['holiday_date'];

  $form_data = array('holiday_title' => $holiday_title, 'holiday_date' => $holiday_date);

  if ($keyvalue == 0) {
    //insert
    $obj->insert_record("m_holiday", $form_data);
    $action = 1;
  } else {
    //update
    $where = array($tblpkey => $keyvalue);
    $obj->update_record($tblname, $where, $form_data);
    $action = 2;
  }

  echo "<script>location='$pagename'</script>";
}

if (isset($_GET[$tblpkey])) {

  $btn_name = "Update";
  $where = array($tblpkey => $keyvalue);
  $sqledit = $obj->select_record($tblname, $where);
  $holiday_title =  $sqledit['holiday_title'];
  $holiday_date =  $sqledit['holiday_date'];
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("topmenu.php"); ?>

  <style>
    .leave-date::before {
      content: "";
      position: absolute;
      height: 13px;
      width: 13px;
      background-color: #e8f1e8;
      border-radius: 50%;
      border: 3px solid #015c3d;
      filter: drop-shadow(0px 0px 3px #015c3d);
    }
  </style>

</head>

<body id="homepage" onload="show_application_data();">
  <!-- BEGIN PRELOADING -->
  <!-- END PRELOADING -->
  <!-- HEADER -->
  <?php include("headed.php"); ?>
  <!-- END HEADER -->
  <!-- SIDE NAV-->
  <nav>
    <!-- LEFT SIDENAV-->
    <?php include("leftmenu.php"); ?>
    <!-- END LEFT SIDENAV-->
  </nav>
  <!-- END SIDENAV-->


  <!-- CONTENT -->
  <div id="page-content">
    <div class="container">
      <div class="row ">
        <div class="col s12 m12 l12 ">
          <div class="section-title">
            <span class="theme-secondary-color black-text">Leave Application</span>
          </div>
        </div>
      </div>

      <form action="" method="post" style="margin-bottom: 30px;">

        <div class="row">
          <div class="input-field col s12 m12 l12 ">
            <input id="holiday_title" name="holiday_title" value="<?php echo $holiday_title; ?>" class="materialize-textarea" autocomplete='off'>
            <label for="holiday_title" class="active">Holiday Title</label>
          </div>
        </div>

        <div class="row">
          <div class="input-field col s12 m12 l12 ">
            <input id="holiday_date" name='holiday_date' value="<?php echo $holiday_date; ?>" type="date" class="validate">
            <label for="holiday_date" class="active">Holiday Date</label>
          </div>
        </div>


        <div class="row">
          <div class="col s12 m12 l12">
            <input type="submit" name="submit" class="btn" value="Submit" style="background-color: #015c3d;">
          </div>

        </div>
      </form>

      <?php
      $qry = $obj->executequery("select * from m_holiday order by holiday_id desc");
      foreach ($qry as $row) {
      ?>


        <div class="row">

          <div class="col s12">
            <div class="card-panel" style="padding: 0px;border-radius:20px;">
              <div class="card-image" style="padding: 8px 20px;background: #e8f1e8;border-radius: 10px;">
                <span class="card-title" style=" display: flex;justify-content: space-between;">
                  <h6><b><?php echo $row['holiday_title']; ?></b></h6>
                </span>
              </div>
              <div class="card-content" style="padding: 0px 20px 8px;">
                <div class="row border-left" style="margin-bottom: 0px;padding:10px;">
                  <div class="col s12">
                    <h6 class="leave-date"> &nbsp; &nbsp; &nbsp; Date : <span class="indigo-text darken-4" style="font-weight: 500;"><?php echo $obj->dateformatindia($row['holiday_date']); ?></span>&nbsp;</h6>
                  </div>
                  <div class="col s12">
                    <a href="holiday_master.php?holiday_id=<?php echo $row['holiday_id']; ?>" class="btn btn-floating green darken-4" style="float: right;margin-left:10px;"><i class="material-icons">mode_edit</i></a>
                    <button onclick="funDel(<?php echo $row['holiday_id']; ?>);" class="btn btn-floating red darken-4" style="float: right;"><i class="material-icons">delete</i></button>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>

      <?php } ?>
    </div>
  </div>
  <!-- END CONTENT -->
  <!-- SUBSCRIBE -->

  <!-- END SUBSCRIBE -->
  <!-- FOOTER  -->

  <!-- END FOOTER -->
  <!-- Script -->
  <script src="js/jquery.min.js"></script>
  <script src="js/materialize.min.js"></script>
  <!-- Owl carousel -->
  <script src="lib/owlcarousel/owl.carousel.min.js"></script>
  <!-- Magnific Popup core JS file -->
  <script src="lib/Magnific-Popup-master/dist/jquery.magnific-popup.js"></script>
  <!-- Slick JS -->
  <script src="lib/slick/slick/slick.min.js"></script>
  <!-- Custom script -->
  <script src="js/custom.js"></script>
  <script src="js/sweetalert.min.js"></script>

  <script>
    function funDel(id) {

      tblname = '<?php echo $tblname; ?>';
      tblpkey = '<?php echo $tblpkey; ?>';

      if (confirm("Are you sure! You want to delete this record.")) {
        jQuery.ajax({
          type: 'POST',
          url: 'ajax_delete_master.php',
          data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey,
          dataType: 'html',
          success: function(data) {
            //alert(data);
            location.reload();
          }
        }); //ajax close
      } //confirm close
    } //fun close
  </script>
</body>

</html>