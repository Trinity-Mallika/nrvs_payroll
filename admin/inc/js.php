    <!-- JAVASCRIPT -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <!-- datepicker -->
    <script src="assets/libs/flatpickr/flatpickr.min.js"></script>
    <!-- select button js -->
    <!-- juery min .js -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/choosen-select/chosen.jquery.min.js"></script>
    <script src="assets/select-2/select2.js"></script>
    <!--datatable js start-->
    <script src="assets/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/datatable/js/dataTables.bootstrap5.min.js"></script>
    <script src="assets/datatable/js/dataTables.responsive.min.js"></script>
    <script src="assets/datatable/js/dataTables.buttons.min.js"></script>
    <script src="assets/datatable/js/buttons.print.min.js"></script>
    <script src="assets/datatable/js/buttons.html5.min.js"></script>
    <script src="assets/datatable/js/vfs_fonts.js"></script>
    <script src="assets/datatable/js/pdfmake.min.js"></script>
    <script src="assets/datatable/js/jszip.min.js"></script>
    <script src="assets/js/pages/datatables.init.js"></script>
    <!--datatable js end-->

    <!-- Sweet Alerts js -->
    <script src="assets/js/app.js"></script>
    <script src="assets/js/sweetalert.js"></script>
    <script src="assets/js/commonfun.js"></script>
    <!-- App js -->

    <!-- form wizard init -->
    <script src="assets/js/pages/form-wizard.init.js"></script>

    <!-- scroll bar -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.querySelectorAll(".auto-scroll-wrapper").forEach(function(wrapper) {

                let tableResponsive = wrapper.querySelector(".table-responsive");
                let table = wrapper.querySelector("table");

                // Hide original scrollbar via JS
                tableResponsive.style.overflowX = "hidden";

                // Create floating scrollbar
                let scrollbar = document.createElement("div");
                let inner = document.createElement("div");

                // Apply styles directly via JS (no CSS file needed)
                scrollbar.style.position = "sticky";
                scrollbar.style.bottom = "0";
                scrollbar.style.height = "14px";
                scrollbar.style.overflowX = "auto";
                scrollbar.style.overflowY = "hidden";
                scrollbar.style.width = "100%";

                inner.style.height = "1px";

                scrollbar.appendChild(inner);
                wrapper.appendChild(scrollbar);

                function updateWidth() {
                    inner.style.width = table.scrollWidth + "px";
                }

                updateWidth();

                // Sync scroll
                scrollbar.addEventListener("scroll", function() {
                    tableResponsive.scrollLeft = scrollbar.scrollLeft;
                });

                tableResponsive.addEventListener("scroll", function() {
                    scrollbar.scrollLeft = tableResponsive.scrollLeft;
                });

                // Resize support
                window.addEventListener("resize", updateWidth);

            });

        });
    </script>