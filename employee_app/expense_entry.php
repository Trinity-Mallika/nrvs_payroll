<?php include("appsession.php");
$pagename = 'expense_entry.php';
$tblname = 'expense_entry';
$tblpkey = 'expense_id';
$imgpath = '../userpanel/uploaded/expence_img/';
$expense_date = date('Y-m-d');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include("topmenu.php"); ?>
    <style type="text/css">
        img {
            display: block;
            margin-left: auto;
            margin-right: auto;
            /* border-radius: 50%; */
            /* width: 30%; */
            background-color: #318bb1;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        }

        .card-panel {
            padding: 10px;
        }

        table td,
        table th {
            padding: 5px 5px;
        }

        span.badge.new:after {
            content: "";
        }


        /* Ensure the modal content is responsive */
        .modal-content {
            position: relative;
            width: 100%;
            max-width: 100%;
            /* Prevent the modal from exceeding screen width */
            height: 80vh;
            /* Set modal height to 80% of viewport height */
        }

        /* Wrapper around iframe to handle responsiveness */
        .pdf-container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        /* Make iframe take full width and height */
        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* For smaller screens (mobile), reduce the modal height */
        @media (max-width: 767px) {
            .modal-content {
                height: 60vh;
                /* Adjust modal height for mobile view */
            }
        }

        #pdf-viewer {
            height: 80vh;
            /* Adjust as needed */
            overflow-y: auto;
            /* Enable scrolling for large PDFs */
        }

        #pdf-pages-container {
            display: flex;
            flex-direction: column;
        }

        canvas {
            margin-bottom: 20px;
            /* Space between pages */
        }

        .modal .modal-content {
            padding: 10px;
        }

        @media only screen and (max-width: 992px) {
            .modal {
                width: 100%;
                height: 100%;
            }
        }

        .modal {
            max-height: none;
        }

        .modal-foot {
            position: absolute;
            top: 85%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #162a5e;
            color: white;
        }

        /* Center-align buttons */
        .swal-modal .swal-footer {
            display: flex;
            justify-content: center;
            /* Centers the buttons horizontally */
            gap: 10px;
            /* Adds spacing between the buttons */
        }

        /* Cancel button style */
        .swal-button-cancel {
            background-color: #f44336;
            /* Red */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
        }

        /* Confirm button style */
        .swal-button-confirm {
            background-color: #4CAF50;
            /* Green */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
        }

        /* Hover effects for both buttons */
        .swal-button-cancel:hover,
        .swal-button-confirm:hover {
            opacity: 0.8;
        }

        .btn-sm {
            font-size: 12px !important;
            width: 150px !important;
            height: 30px !important;
            line-height: 32px !important;
        }

        .upload-btn {
            font-size: 14px;
            border-bottom: 2px solid #cdcdcd;
            width: 100%;
            padding: 7px 20px 7px 0px;
        }
    </style>
</head>

<body id="homepage">
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
        <!-- RIGHT SIDENAV-->
        <?php //include("rightmenu.php");
        ?>
        <!-- END RIGHT SIDENAV-->
    </nav>
    <!-- END SIDENAV-->


    <!-- CONTENT -->
    <div id="page-content" style="background-color:#f9f9f9;">
        <div>
            <div class="container">
                <div class="row ">
                    <div class="col s12 m12 l12 ">
                        <div class="section-title">
                            <span class="theme-secondary-color" style="color: black;">expense</span> Entry
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="input-field col s12 m12 l12 ">
                        <input value="" id="amount" name="amount" type="text" class="validate" autofocus onkeypress="numberOnly(event)">
                        <label for="amount">Amount</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12 m12 l12 ">
                        <input type="date" value="<?php echo $expense_date ?>" id="expense_date" name='expense_date' type="text" class="validate" autofocus>
                        <label for="date" class="active">Date</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m12 l12 ">
                        <div class="file-field input-field">
                            <div class="btn ">
                                <span><i class="material-icons" style="font-size: 1rem;">file_upload</i> Upload Documents </span>
                                <input type="file" id="image" name='image' onchange="validateimage()">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text">
                            </div>
                        </div>

                        <!-- <label for="image" class="upload-btn"><i class="material-icons">file_upload</i> Upload Documents
                            <input id="image" name='image' type="file" autofocus hidden>
                        </label> -->

                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m12 l12 ">
                        <textarea id="remark" name="remark" class="materialize-textarea"></textarea>
                        <label for="remark">Remark</label>

                    </div>
                </div>
                <div class="row">
                    <div class=" col-12">
                        <button name="button" id="submit" onclick="save_data()" class="btn btn-theme" style="width: 100%;"> Add</button>
                    </div>
                </div>


                <div class="row ">
                    <div class="col s12 m12 l12 " style="margin-bottom: 15px">
                        <div class="section-title">
                            <span class="theme-secondary-color" style="color: black;">expense Entry</span> List
                        </div>
                    </div>
                    <?php

                    $res = $obj->executequery("select * from $tblname where createdby='$loginid' order by $tblpkey desc");
                    foreach ($res as $row) {
                        $exp_status = $row['exp_status'];
                        if ($exp_status == '0') {
                            $status = "Pending";
                            $color = 'orange';
                        } elseif ($exp_status == "1") {
                            $status  = "Approved";
                            $color = 'green';
                        } else {
                            $status = "Rejected";
                            $color = 'red';
                        }
                    ?>
                        <div class="col s12 m12 l12 ">
                            <div class="card-panel ">
                                <table>
                                    <tr>
                                        <th>Amount </th>
                                        <td class="right"><?php echo number_format($row['exp_amount'], 2) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Date </th>
                                        <td class="right"><?php echo $obj->dateformatindia($row['expense_date']) ?></td>
                                    </tr>

                                    <tr>
                                        <th>Remark </th>
                                        <td class="right"><?php echo ucfirst($row['remark']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Status </th>
                                        <td><span class="new badge <?php echo $color ?>"><?php echo $status ?></span></td>
                                    </tr>
                                    <tr>
                                        <?php if ($row['exp_img'] != "") { ?>
                                            <th>Document </th>
                                            <td class="right">
                                                <?php
                                                $imageFileType = strtolower(pathinfo($row['exp_img'], PATHINFO_EXTENSION));
                                                if ($imageFileType == 'jpg' || $imageFileType == 'jpeg') {
                                                    // Display button for modal if the file is an image
                                                ?>
                                                    <button data-target="modal1" onclick="show_modal('<?php echo $imgpath . $row['exp_img'] ?>')" class="btn modal-trigger btn-sm">View</button>
                                                <?php
                                                } else {
                                                    // Provide a direct download link if the file is not an image
                                                ?>
                                                    <a href="<?php echo $imgpath . $row['exp_img'] ?>" class="btn btn-primary btn-sm" target="_blank">Download </a>
                                                <?php
                                                }
                                                ?>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th class="right">
                                            <button class="btn red btn-sm" onclick="fundel('<?php echo  $row['expense_id'] ?>','<?php echo $row['exp_img'] ?>')">Delete</button>
                                        </th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <br>
            </div>
        </div>
    </div>
    <!-- END CONTENT -->

    <!-- Modal Structure -->
    <!-- Modal Structure -->
    <div id="modal1" class="modal">
        <div class="modal-content">
            <!-- image preview start -->
            <img src="" alt="" id="expanse_image" style="height: 100%; width:100% ">
            <!-- image preview end -->
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-foot modal-close waves-effect waves-green btn-flat">Close</a>
        </div>
    </div>

    <ul id="tabs" class="tabs" style="position: fixed; bottom: 0px;border-top:1px solid #ddd;z-index: 999;">
        <li class="tab">
            <a class=" small " target="_self" href="change-password.php" style="line-height: 1;font-size: 11px; ">
                <i class="material-icons" style="display: block;padding-top: 13px;line-height: 1.1;">create</i>

            </a>
        </li>
        <li class="tab">
            <a class="small " target="_self" href="emp_latlong_att.php" style="line-height: 1;font-size: 11px; ">
                <i class="material-icons" style="display: block;padding-top: 13px;line-height: 1.1;">event_note</i>

            </a>
        </li>
        <li class="tab">
            <a class=" small " target="_self" href="dashboard.php" style="line-height: 1;font-size: 11px;">
                <i class="material-icons" style="display: block;padding-top: 13px;line-height: 1.1;">home</i>
            </a>
        </li>
        <li class="tab">
            <a class=" small active" target="_self" href="setting.php" style="line-height: 1;font-size: 11px; ">
                <i class="material-icons" style="display: block;padding-top: 5px;line-height: 1.1;">people</i> Profile
            </a>
        </li>
        <li class="tab">
            <a class=" small " target="_self" href="attandance_details.php" style="line-height: 1;font-size: 11px; ">
                <i class="material-icons"
                    style="display: block;padding-top: 13px;line-height: 1.1;">perm_contact_calendar</i>

            </a>
        </li>
    </ul>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>

    <script>
        function show_modal(imgname) {
            document.getElementById('expanse_image').src = imgname;
            $('.modal').modal();
        }
        // swal("dhjfjdfh");

        function save_data() {
            var image = $('#image')[0];
            var amount = $('#amount').val();
            var expense_date = $('#expense_date').val();
            var remark = $('#remark').val();


            if (amount == "") {
                swal("", "Please Enter Amt", "error");
                return false;
            }


            var formData = new FormData();
            formData.append('amount', amount);
            formData.append('expense_date', expense_date);
            formData.append('remark', remark);

            if (image.files.length > 0) {
                formData.append('image', image.files[0]);
            }
            $.ajax({
                type: "POST",
                url: "save_expense_data.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    // alert(data);
                    if (data == 1) {
                        swal({
                            text: "Added successfully!",
                            icon: "success",
                        }).then((result) => {
                            location.reload()
                        });
                    } else if (data == 2) {
                        swal({
                            text: "Somthing went wrong!",
                            icon: "error",

                        }).then((result) => {
                            location.reload()
                        });
                    }
                }
            }); //ajax closed
        }
    </script>
    <script>
        function fundel(id, imgname) {
            // alert(id);
            var tblname = '<?php echo $tblname ?>';
            var tblpkey = '<?php echo $tblpkey ?>';
            var pagename = '<?php echo $pagename ?>';
            var imgpath = '<?php echo $imgpath ?>';

            swal({
                title: "Delete !!",
                text: "Do you want to delete this?",
                icon: "warning",
                buttons: {
                    cancel: {
                        text: "Cancel",
                        value: null,
                        visible: true,
                        className: "swal-button-cancel", // Custom class
                        closeModal: true
                    },
                    confirm: {
                        text: "Confirm",
                        value: true,
                        visible: true,
                        className: "swal-button-confirm", // Custom class
                        closeModal: true
                    }
                }
            }).then((willDelete) => {
                if (willDelete) {
                    jQuery.ajax({
                        type: "POST",
                        url: "delete_master_image.php",
                        data: {
                            id: id,
                            tblname: tblname,
                            tblpkey: tblpkey,
                            imgname: imgname,
                            pagename: pagename,
                            imgpath: imgpath,
                        },
                        dataType: "html",
                        success: function(response) {
                            // alert(response);
                            if (response == 1) {
                                swal({
                                    icon: "success",
                                    title: "Deleted!",
                                    text: "Deleted successfully.",

                                }).then(() => {
                                    location.reload();
                                });

                            } else {
                                swal({
                                    icon: "error",
                                    title: "Something Went Wrong!",
                                    text: "Please try again.",
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        }

                    }); // End of AJAX
                }
            });
        }

        function numberOnly(evt) {
            var theEvent = evt || window.event;

            // Handle paste
            if (theEvent.type === 'paste') {
                key = event.clipboardData.getData('text/plain');
            } else {
                // Handle key press
                var key = theEvent.keyCode || theEvent.which;
                key = String.fromCharCode(key);
            }
            var regex = /[0-9]|\-|\s/;
            if (!regex.test(key)) {
                theEvent.returnValue = false;
                if (theEvent.preventDefault) theEvent.preventDefault();
            }
        }
    </script>

    <script>
        function validateimage() {
            var input = document.getElementById('image');
            var file = input.files[0];

            if (file) {
                // Get the file name and extension
                var fileName = file.name.toLowerCase();
                var validExtensions = ['pdf', 'jpg', 'jpeg'];
                var fileExtension = fileName.split('.').pop();

                // Check if the file has a valid extension
                if (!validExtensions.includes(fileExtension)) {
                    alert("Please upload a file with a valid extension (PDF, JPG, JPEG)!");
                    input.value = ''; // Clear the input field
                    return;
                }
            }
        }
    </script>

</body>

</html>