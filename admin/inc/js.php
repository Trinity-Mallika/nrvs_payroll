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
    <!-- Bottom Floating Horizontal Scrollbar -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.querySelectorAll(".auto-scroll-wrapper").forEach(function(wrapper) {

                const tableResponsive = wrapper.querySelector(".table-responsive");
                const table = wrapper.querySelector("table");

                if (!tableResponsive || !table) return;

                /* --------------------------------
                   Keep full table width
                -------------------------------- */
                // table.style.minWidth = "max-content";
                // table.style.width = "max-content";

                /* --------------------------------
                    Hide ONLY horizontal scrollbar
                    Keep vertical scrollbar visible
                    -------------------------------- */
                tableResponsive.style.overflowX = "auto";
                tableResponsive.style.overflowY = "auto";

                /* Firefox */
                tableResponsive.style.scrollbarWidth = "auto";

                /* IE / old Edge */
                tableResponsive.style.msOverflowStyle = "auto";


                /* Chrome / Edge / Safari */
                const style = document.createElement("style");

                style.innerHTML = `
                    .auto-scroll-wrapper .table-responsive::-webkit-scrollbar {
                        width: 15px;   /* Y-axis scrollbar */
                        height: 0px;   /* X-axis scrollbar hidden */
                    }
                `;

                document.head.appendChild(style);

                /* --------------------------------
                   Create bottom scrollbar
                -------------------------------- */
                let scrollbar = document.createElement("div");
                let inner = document.createElement("div");

                scrollbar.className = "floating-table-scrollbar";
                inner.className = "floating-table-scrollbar-inner";

                scrollbar.style.width = "100%";
                scrollbar.style.height = "14px";
                scrollbar.style.overflowX = "auto";
                scrollbar.style.overflowY = "scroll";
                scrollbar.style.position = "sticky";
                scrollbar.style.bottom = "0";
                scrollbar.style.zIndex = "20";
                scrollbar.style.background = "#f8f9fa";

                inner.style.height = "1px";

                scrollbar.appendChild(inner);
                wrapper.appendChild(scrollbar);

                /* --------------------------------
                   Update scrollbar width
                -------------------------------- */
                function updateWidth() {

                    // Get actual complete table width
                    const tableWidth = table.getBoundingClientRect().width;

                    // Get visible area
                    const containerWidth = tableResponsive.clientWidth;

                    // Set exact scroll width
                    inner.style.width = Math.max(
                        table.scrollWidth,
                        tableWidth,
                        containerWidth
                    ) + "px";
                }

                /* --------------------------------
                   Sync floating scrollbar -> table
                -------------------------------- */
                scrollbar.addEventListener("scroll", function() {

                    if (tableResponsive.scrollLeft !== scrollbar.scrollLeft) {
                        tableResponsive.scrollLeft = scrollbar.scrollLeft;
                    }

                });

                /* --------------------------------
                   Sync table -> floating scrollbar
                -------------------------------- */
                tableResponsive.addEventListener("scroll", function() {

                    if (scrollbar.scrollLeft !== tableResponsive.scrollLeft) {
                        scrollbar.scrollLeft = tableResponsive.scrollLeft;
                    }

                });

                /* --------------------------------
                   Initial update
                -------------------------------- */
                updateWidth();

                setTimeout(updateWidth, 100);
                setTimeout(updateWidth, 500);
                setTimeout(updateWidth, 1000);

                /* --------------------------------
                   Window resize
                -------------------------------- */
                window.addEventListener("resize", updateWidth);

                /* --------------------------------
                   Page load
                -------------------------------- */
                window.addEventListener("load", updateWidth);

                /* --------------------------------
                   DataTables redraw support
                -------------------------------- */
                if ($.fn.dataTable) {

                    $(document).on(
                        "draw.dt column-sizing.dt",
                        function() {
                            setTimeout(updateWidth, 50);
                        }
                    );

                }

            });

        });
    </script>

    <script>
document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById("search_options_side");

    if (!searchInput) return;

    setTimeout(function () {

        const navbar = document.getElementById("navbar-nav");

        if (!navbar) return;

        const allNavItems = navbar.querySelectorAll("li.nav-item");


        /* =========================================
           CHECK SEARCH BOX
        ========================================= */

        function isSearchItem(item) {
            return item.querySelector("#search_options_side") !== null;
        }


        /* =========================================
           EXPAND PARENT MENU
        ========================================= */

        function expandItem(item) {

            const dropdown = item.querySelector(":scope > .menu-dropdown");

            const toggle = item.querySelector(
                ":scope > a[data-bs-toggle='collapse']"
            );

            if (dropdown && toggle) {

                dropdown.classList.add("show");

                toggle.setAttribute("aria-expanded", "true");
            }
        }


        /* =========================================
           COLLAPSE PARENT MENU
        ========================================= */

        function collapseItem(item) {

            const dropdown = item.querySelector(":scope > .menu-dropdown");

            const toggle = item.querySelector(
                ":scope > a[data-bs-toggle='collapse']"
            );

            if (dropdown && toggle) {

                dropdown.classList.remove("show");

                toggle.setAttribute("aria-expanded", "false");
            }
        }


        /* =========================================
           RESET SIDEBAR
        ========================================= */

        function resetMenu() {

            allNavItems.forEach(function (item) {

                if (isSearchItem(item)) {

                    item.style.display = "block";

                    return;
                }


                // Show everything
                item.style.display = "block";


                // Remove temporary match
                delete item.dataset.match;


                // Close only parent menus
                collapseItem(item);

            });

        }


        /* =========================================
           SEARCH
        ========================================= */

        searchInput.addEventListener("input", function () {

            const query = this.value
                .toLowerCase()
                .trim();


            /* -------------------------------------
               LESS THAN 3 CHARACTERS
            ------------------------------------- */

            if (query.length < 3) {

                resetMenu();

                return;
            }


            /* =====================================
               STEP 1
               HIDE EVERYTHING
            ===================================== */

            allNavItems.forEach(function (item) {

                if (isSearchItem(item)) {

                    item.style.display = "block";

                    return;
                }


                item.style.display = "none";

                delete item.dataset.match;

                collapseItem(item);

            });


            /* =====================================
               STEP 2
               FIND MATCHING PAGE / MENU
            ===================================== */

            allNavItems.forEach(function (item) {

                if (isSearchItem(item)) {
                    return;
                }


                /*
                 * PAGE NAME
                 */
                let pageName = "";


                const directLink = item.querySelector(
                    ":scope > a.nav-link"
                );


                if (directLink) {

                    pageName = directLink.innerText
                        .replace(/\s+/g, " ")
                        .trim()
                        .toLowerCase();

                }


                /*
                 * URL
                 */
                let pageUrl = "";

                if (directLink) {

                    pageUrl = (
                        directLink.getAttribute("href") || ""
                    ).toLowerCase();

                }


                /*
                 * PARENT MENU NAME
                 */
                let menuName = "";

                const menuToggle = item.querySelector(
                    ":scope > a[data-bs-toggle='collapse']"
                );


                if (menuToggle) {

                    menuName = menuToggle.innerText
                        .replace(/\s+/g, " ")
                        .trim()
                        .toLowerCase();

                }


                /*
                 * COMPLETE SEARCH TEXT
                 */
                const searchText =
                    pageName + " " +
                    pageUrl + " " +
                    menuName;


                /*
                 * SAVE MATCH
                 */
                if (searchText.includes(query)) {

                    item.dataset.match = "1";

                } else {

                    item.dataset.match = "0";

                }

            });


            /* =====================================
               STEP 3
               SHOW MATCHES + THEIR PARENTS
            ===================================== */

            allNavItems.forEach(function (item) {

                if (isSearchItem(item)) {
                    return;
                }


                if (item.dataset.match !== "1") {
                    return;
                }


                /*
                 * SHOW MATCHED ITEM
                 */
                item.style.display = "block";


                /*
                 * OPEN IF IT IS A PARENT MENU
                 */
                expandItem(item);


                /*
                 * FIND PARENT
                 *
                 * li
                 *   ul
                 *      li  <-- current
                 *          div.menu-dropdown
                 */

                let parentLi =
                    item.closest("ul")?.closest("li.nav-item");


                /*
                 * OPEN ALL PARENTS
                 */
                while (parentLi) {

                    if (isSearchItem(parentLi)) {
                        break;
                    }


                    parentLi.style.display = "block";

                    expandItem(parentLi);


                    parentLi =
                        parentLi.closest("ul")
                            ?.closest("li.nav-item");

                }

            });


            /* =====================================
               STEP 4
               IF PARENT MENU ITSELF MATCHES
               SHOW ALL CHILD PAGES
            ===================================== */

            allNavItems.forEach(function (item) {

                if (isSearchItem(item)) {
                    return;
                }


                if (item.dataset.match !== "1") {
                    return;
                }


                const dropdown = item.querySelector(
                    ":scope > .menu-dropdown"
                );


                if (dropdown) {

                    /*
                     * Parent menu matched.
                     * Show all its pages.
                     */

                    dropdown
                        .querySelectorAll(":scope ul > li.nav-item")
                        .forEach(function (child) {

                            child.style.display = "block";

                        });


                    item.style.display = "block";

                    expandItem(item);

                }

            });


        });


    }, 300);

});
</script>