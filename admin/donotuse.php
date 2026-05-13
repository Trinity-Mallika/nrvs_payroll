<?php include("../adminsession.php");
$title = "Month Wise Attendance Report";
$pagename = "month_wise_attendance_report.php";
$module = "Search Attendance";
$submodule = "Month Wise Attendance List";
$btn_name = "Search";
$keyvalue = 0;
$tblname = "attendance_entry";
$tblpkey = "attendance_id";
?>
<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
  <meta charset="utf-8" />
  <title><?php echo $title; ?></title>
  <?php include('inc/css.php') ?>

</head>
<style>
  .floating-scrollbar {
    position: fixed;
    bottom: 10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60%;
    max-width: 1000px;
    height: 16px;
    overflow-x: auto;
    overflow-y: hidden;
    background: #f8f9fa;
    border: 1px solid #ccc;
    border-radius: 8px;
    z-index: 9999;
  }

  .floating-scrollbar div {
    height: 1px;
  }


  .universal-scroll-table {
    min-width: 1500px;
  }
</style>

<body>
  <?php include('inc/header.php') ?>
  <?php include('inc/sidebar.php') ?>
  <!-- end auth-page-wrapper -->
  <div class="main-content">
    <div class="page-content">
      <div class="container-fluid">

        <div class="card">
          <div class="card-body">
            <!-- floating scrollbar -->
            <div class="floating-scrollbar">
              <div></div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle" id="buttons-datatables">
                <thead>
                  <tr>
                    <th>S.No.</th>
                    <th>Request No. &amp; Date </th>
                    <th>Store Section </th>
                    <th>Item Code </th>
                    <th>Item &amp;<br />
                      Req. for
                    </th>
                    <th>Remark</th>
                    <th>Requested Qty.</th>
                    <th>Sanctioned Qty.</th>
                    <th>Frwd.<br />
                      Site <br />
                      </td>
                    <th>Doc.</th>
                    <th>Sanction Status </th>

                    <th>Requested By<br />
                      Sanctioned By
                      </td>
                    <th>Action</th>
                    <th>Indent Qty.
                    </th>
                    <th width="59" align="center" class="sorter-false">Issue Qty.
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php for ($i = 0; $i < 100; $i++) {
                  ?>
                    <tr>
                      <td>demo</td>
                      <td>demo</td>
                      <td>demo</td>
                      <td>demo</td>
                      <td>demo</td>
                      <td>demo</td>
                      <td>demo</td>
                      <td>demo</td>
                      <td>demo</td>
                      <td>demo</td>
                      <td>demo</td>
                      <td>demo</td>
                      <td>demo</td>
                      <td>demo</td>
                      <td>demo</td>
                      <td>demo</td>
                      <td>demo</td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- script tag -->
  <?php include('inc/delete.php') ?>
  <?php include('inc/js.php') ?>
  <?php include('inc/footer.php') ?>


  <script>
    $(function() {
      let w = $(".table-responsive"),
        s = $(".floating-scrollbar"),
        t = $(".table");
      s.find("div").width(t.outerWidth());
      s.scroll(() => w.scrollLeft(s.scrollLeft()));
      w.scroll(() => s.scrollLeft(w.scrollLeft()));
      $(window).resize(() => s.find("div").width(t.outerWidth()));
    });
  </script>
</body>