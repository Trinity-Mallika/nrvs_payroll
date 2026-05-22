<?php include("appsession.php");
//print_r($_SESSION);die;
$usertype = $_SESSION['type'];

$crit = "where 1=1";
if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
   $from_date = $obj->test_input($_GET['from_date']);
   $to_date = $obj->test_input($_GET['to_date']);
} else {
   $from_date = date('Y-m-d');
   $to_date = date('Y-m-d');
   $empid = "";
}
$crit .= " and createdate between '$from_date' and '$to_date'";


if (isset($_GET['empid'])) {

   $empid = $_GET['empid'];
   if ($empid > 0)
      $crit .= " and empid = '$empid'";
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
   <?php include("topmenu.php"); ?>
   <style>
      .card-panel {
         padding: 15px;
         border-radius: 10px;
      }

      .tabs .tab a:hover,
      .tabs .tab a.active {
         background-color: transparent;
         color: #015c3d;
         font-weight: normal;
      }

      .tabs .tab a {
         color: black;
         font-weight: normal;
      }

      .tabs .indicator {
         position: absolute;
         bottom: 0;
         height: 3px;
         background-color: #015c3d;
         will-change: left, right;
      }

      .donut-inner {
         position: absolute;
         bottom: 25%;
         left: 28%;
         text-align: center;
      }

      .dropdown-content {
         max-height: 450px !important;
      }

      th {
         font-weight: 500;
      }

      td {
         font-weight: 400;
      }

      table tr a {
         color: blue;
      }

      table tr a:hover {
         color: #015c3d;
      }

      table,
      th,
      td {
         border: 1px solid #ebe2e2;
      }

      .excel-btn1 {
         background: #015c3d;
         padding: 7px;
         border-radius: 5px;
         border: 0px;
      }

      .excel-btn {
         background: #015c3d;
         padding: 5px;
         border-radius: 5px;
      }
   </style>
</head>
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

<body>

   <div class="section" style="margin-bottom: 50px;">
      <div class="container">
         <div class="row ">
            <div class="col s12 m12 l12 ">
               <div class="section-title">
                  <span class="theme-secondary-color" style="color: black;">Today Location</span> Report
               </div>
            </div>
         </div>
         <div class="row ">

            <div class="input-field col s12">
               <select name="empid" id="empid">
                  <option value="">--All--</option>
                  <?php
                  $sql = $obj->executequery("select * from m_employee order by emp_name asc");
                  foreach ($sql as $row) {
                  ?>
                     <option value="<?php echo $row['empid']; ?>"><?php echo ucwords($row['emp_name']); ?></option>
                  <?php } ?>
               </select>
               <label>Select Employee</label>
               <script>
                  document.getElementById('empid').value = '<?php echo $empid; ?>';
               </script>
            </div>

            <div class="input-field col s6">
               <input id="from_date" type="date" class="validate" value="<?php echo $from_date; ?>">
               <label for="from_date" class="active">From</label>
            </div>
            <div class="input-field col s6">
               <input id="to_date" type="date" class="validate" value="<?php echo $to_date; ?>">
               <label for="to_date" class="active"> Date</label>
            </div>
            <div class="col s12">
               <button class="excel-btn1 white-text waves-effect waves-light billing-deatail-form-text" style="width: 100%;" type="submit" name="action" onclick="get_detail();">Submit
               </button>
            </div>
            <a class="waves-effect waves-light excel-btn white-text right" onclick="exportTableToExcel('export')"> Export Excel</a>
            <div class="col s12">
               <div class="card" style="border-radius: 15px;">
                  <div class="card-content" style="padding: 10px;">
                     <div style="overflow: auto;">
                        <table id="export">
                           <thead class="teal darken-4 white-text">
                              <tr>
                                 <th>Sno.</th>
                                 <th>Employee Name</th>
                                 <th>Date In </th>
                                 <th>Time In </th>
                                 <th>Date Out</th>
                                 <th>Time Out</th>
                                 <th>In Location</th>
                                 <th>Out Location</th>
                              </tr>
                              <!--  <tr>
                                 <th></th>
                                 <th></th>
                                 <th>In</th>
                                 <th>Out</th>
                                  <th>Lat.</th>
                                 <th>Long.</th> 
                                  <th>Lat.</th>
                                 <th>Long.</th>
                                 <th></th> 
                              </tr> -->
                           </thead>
                           <tbody>

                              <?php
                              $slno = 1;

                              $sql = $obj->executequery("select * from attendance_entry $crit order by attendance_date asc");
                              foreach ($sql as $row) {
                                 $empid = $row['empid'];
                                 $latitude_in = $row['latitude_in'];
                                 $longitude_in = $row['longitude_in'];
                                 $latitude_out = $row['latitude_out'];
                                 $longitude_out = $row['longitude_out'];
                                 $in_address = $row['in_address'];
                                 $out_address = $row['out_address'];
                                 $in_date = $obj->dateformatindia($row['attendance_date']);
                                 $in_time = $row['attendance_in_time'];
                                 $out_date = $obj->dateformatindia($row['attendance_date']);
                                 $out_time = $row['attendance_out_time'];
                                 $emp_name = $obj->getvalfield("m_employee", "emp_name", "empid='$empid'");
                                 // $url_in = "https://www.google.com/maps/search/?q=$latitude_in,$longitude_in";
                                 $url_in = "https://www.google.com/maps/place/$latitude_in,$longitude_in";
                                 $url_out = "https://www.google.com/maps/place/$latitude_out,$longitude_out";



                              ?>
                                 <tr>
                                    <td><?php echo $slno++; ?>.</td>
                                    <td><?php echo $emp_name; ?></td>
                                    <td><?php echo $in_date; ?></td>
                                    <td><?php echo $in_time; ?></td>
                                    <td><?php echo $out_date; ?></td>
                                    <td><?php echo $out_time; ?></td>
                                    <td><a href='<?php echo $url_in; ?>'>View Location</a>
                                       <br>Address :
                                       <?php echo str_replace('#', ' ', $in_address); ?>


                                    </td>
                                    <td><a href='<?php echo $url_out; ?>'>View Location</a><br>Address :
                                       <?php echo str_replace('#', ' ', $out_address); ?></td>
                                    <!-- <td></td>
                                 <td></td>
                                 <td></td> -->
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
   </div>
   <ul id="tabs" class="tabs" style="position: fixed; bottom: 0px;border-top:1px solid #ddd;z-index: 999;">
      <li class="tab">
         <a class=" small " target="_self" href="emp_task_assign.php" style="line-height: 1;font-size: 11px; ">
            <i class="material-icons" style="display: block;padding-top: 13px;line-height: 1.1;">create</i>
         </a>
      </li>
      <li class="tab">
         <a class="small active" target="_self" href="task.php" style="line-height: 1;font-size: 11px; ">
            <i class="material-icons" style="display: block;padding-top: 5px;line-height: 1.1;">event_note</i>
            Task List
         </a>
      </li>
      <li class="tab">
         <a class=" small " target="_self" href="dashboard.php" style="line-height: 1;font-size: 11px;">
            <i class="material-icons" style="display: block;padding-top: 13px;line-height: 1.1;">home</i>
         </a>
      </li>
      <li class="tab">
         <a class=" small " target="_self" href="assigned_task.php" style="line-height: 1;font-size: 11px; ">
            <i class="material-icons" style="display: block;padding-top: 13px;line-height: 1.1;">people</i>

         </a>
      </li>
      <li class="tab">
         <a class=" small " target="_self" href="attandance_details.php" style="line-height: 1;font-size: 11px; ">
            <i class="material-icons" style="display: block;padding-top: 13px;line-height: 1.1;">perm_contact_calendar</i>

         </a>
      </li>
   </ul>
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
      function get_detail() {
         from_date = document.getElementById("from_date").value;
         to_date = document.getElementById("to_date").value;
         var empid = document.getElementById("empid").value;
         location = 'location_report.php?from_date=' + from_date + '&to_date=' + to_date + '&empid=' + empid;
      }

      jQuery(document).ready(function() {
         jQuery('select').material_select();
      });

      function select_employee(empid) {
         location = '?empid=' + empid;
      }
   </script>
   <script>
      function exportTableToExcel(tableID, filename = '') {
         // alert(tableID);
         var downloadLink;
         var dataType = 'application/vnd.ms-excel';
         var tableSelect = document.getElementById(tableID);
         // Add a border to the table
         tableSelect.style.border = '1px solid #000';
         var thElements = tableSelect.querySelectorAll('th');
         var tdElements = tableSelect.querySelectorAll('td');

         thElements.forEach(th => {
            th.style.border = '1px solid black';
         });

         tdElements.forEach(td => {
            td.style.border = '1px solid black';
         });
         var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
         filename = filename ? filename + '.xls' : 'excel_data.xls';
         downloadLink = document.createElement("a");
         document.body.appendChild(downloadLink);
         if (navigator.msSaveOrOpenBlob) {
            var blob = new Blob(['\ufeff', tableHTML], {
               type: dataType
            });
            navigator.msSaveOrOpenBlob(blob, filename);
         } else {
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
            downloadLink.download = filename;
            downloadLink.click();

         }
         // Remove the border after export and set back to its original 
         tableSelect.style.border = 'none';
         thElements.forEach(th => {
            th.style.border = '1px solid #ebe2e2';
         });

         tdElements.forEach(td => {
            td.style.border = '1px solid #ebe2e2';
         });

      }
   </script>
</body>

</html>