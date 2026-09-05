    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content=" Infosol Systems LTD" name=" Infosol Systems LTD" />
    <meta content="Makanziee" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />
    <!-- <link href="assets/choosen-select/chosen.min.css" rel="stylesheet" type="text/css" /> -->
    <link href="assets/select-2/select2.css" rel="stylesheet" type="text/css" />
    <link href="assets/datatable/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/datatable/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />


    <style>
        .ri-pencil-fill {
            font-size: 1rem !important;
        }

        .ri-delete-bin-fill {
            font-size: 1rem !important;
        }

        .ri-eye-fill {
            font-size: 1rem !important;
        }

        .table-responsive {
            max-height: 800px;
            height: auto;
        }

        .table thead {
            background: white;
        }

        .table thead {
            position: sticky !important;
            top: 0;
            z-index: 10;
        }

        /* scroll code  */
        .auto-scroll-wrapper {
            width: 100%;
            position: relative;
        }

        /* Scroll container */
        .auto-scroll-wrapper .table-responsive {
            max-height: 800px;
            height: auto;
            width: 100%;
            overflow-x: auto;
            overflow-y: auto;
            position: relative;

            /* Hide native horizontal scrollbar */
            scrollbar-width: auto;
        }

        .auto-scroll-wrapper .table-responsive::-webkit-scrollbar {
            width: 10px;
            /* Y scrollbar */
            height: 0px;
            /* X scrollbar hidden */
        }

        /* IMPORTANT */
        .auto-scroll-wrapper table {
            margin-bottom: 0 !important;
            /* Don't use max-content here */
            width: max-content !important;
            /* Let table be wider than viewport */
            min-width: 100%;
            table-layout: auto !important;
        }

        /* Don't allow content to change height */
        .auto-scroll-wrapper th,
        .auto-scroll-wrapper td {
            white-space: nowrap;
        }

        /* Sticky header */
        .auto-scroll-wrapper thead th {
            position: sticky !important;
            top: 0 !important;
            z-index: 20 !important;
            background-color: #d8e4eb !important;
            white-space: nowrap;
            box-shadow: 0 1px 0 #000;
        }
    </style>