<?php include("appsession.php");
if ($_SESSION['type'] != "admin") {
   header('Location: dashboard.php');
}
$crit = "where empid='$loginid' ";

if (isset($_GET['task_date'])) {
   $task_date = $obj->test_input($_GET['task_date']);
   if ($task_date != "")
      $crit .= " and task_date = '$task_date' ";
} else {
   $task_date = "";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <?php include("topmenu.php"); ?>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

   <style>
      span.badge.new:after {
         content: "";
      }

      .card-list {
         border-radius: 20px;
      }

      .card .card-content {
         padding: 10px;
      }

      .card .card-content p {
         font-size: 13px;
         font-weight: 500;
         margin: 2px 2px;
      }

      .text-content {
         border: 1px solid white;
         background: white;
         border-radius: 15px;
         padding: 8px;
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
   <!-- RIGHT SIDENAV-->
   <!-- END RIGHT SIDENAV-->

</nav>
<!-- END SIDENAV-->

<body onload="getrecord()">

   <div class="section">
      <div class="container">




         <div class="row" id="staggered-test">

            <!-- <div class="col s12">
               <div class="collection" id="staggered-test">

               </div>
            </div> -->

         </div>


      </div>
   </div>
   <!-- Modal Structure -->
   <div id="modal1" class="modal">
      <div class="modal-content" style="height: 280px;">
         <h5>Leave Approval</h5>
         <hr>
         <div class="input-field col s12" style="margin-top: 30px;">
            <select name="approve" id="approve">
               <option value="" disabled selected>Choose your option</option>
               <option value="1">Approve</option>
               <option value="2">Reject</option>
            </select>
            <label>Select</label>
         </div>
         <input type="hidden" id="application_id" name="application_id" value="0">
         <input type="button" id="" name="" class="btn btn-primary" value="Update" onclick="approve_leave();">
      </div>
   </div>


   <!-- Modal Trigger -->
   <!-- <a class="waves-effect waves-light btn modal-trigger" href="#modal2">Modal</a> -->

   <!-- Modal Structure -->
   <!-- <div id="modal2" class="modal">
      <div class="modal-content">
         <h4>Modal Header</h4>
         <p>A bunch of text</p>
      </div>
      <div class="modal-footer">
         <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Agree</a>
      </div>
   </div> -->




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
   <script type="text/javascript">
      jQuery(document).ready(function() {
         jQuery('.modal').modal();
         jQuery('select').material_select();
      });


      function show_modal(application_id) {
         // alert(application_id);
         jQuery('#application_id').val(application_id);
      }

      function approve_leave() {

         var approve = document.getElementById('approve').value;
         var application_id = document.getElementById('application_id').value;

         if(approve==''){
            swal("warning","Approval Status Cant Be Blank!!");
            return false;
         }
         jQuery.ajax({
            type: 'POST',
            url: 'ajax_approve_application.php',
            data: 'application_id=' + application_id + '&approve=' + approve,
            dataType: 'html',
            success: function(data) {
               // jQuery('#modal1').hide();
               location.href="emp_application.php";
               getrecord();
            }

         }); //ajax close
         //window.location.reload();
      }
   </script>
   <script>
      function getrecord() {
         jQuery.ajax({
            type: 'GET',
            url: 'ajax_approval_data.php',
            // data: 'application_id=' + application_id,
            dataType: 'html',
            success: function(data) {
               document.getElementById('staggered-test').innerHTML = data;
            }

         }); //ajax close
      }

     
       function funDel(application_id) {
            swal({
                title: "Are you sure ?",
                text: "",
                icon: "warning",
                buttons: true,
                dangerMode: true,
              })

              .then((willDelete) => {
                if (willDelete) {

                  tblname = 'leave_application';
                  tblpkey = 'application_id';
                  jQuery.ajax({
                    type: 'POST',
                    url: 'ajax_delete_leave.php',
                    data: 'application_id=' + application_id + '&tblname=' + tblname + '&tblpkey=' + tblpkey,
                    dataType: 'html',
                    success: function(data) {
                      //alert(data);
                      if (data == 1) {
                        swal("Successfully Deleted!");

                      } else {
                        swal("Something Went Wrong!");
                      }

                      location = "emp_application.php";

                    }
                  });

                }
              });
          }
   </script>

</body>

</html>