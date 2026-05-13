<?php include("../../adminsession.php");
$id  = $_REQUEST['id'];
$tblname  = $_REQUEST['tblname'];
$tblpkey  = $_REQUEST['tblpkey'];
$module = $_REQUEST['module'];
$submodule = $_REQUEST['submodule'];
$pdffile = $_REQUEST['pdffile'];
$pagename = $_REQUEST['pagename'];
$imgpath = $_REQUEST['imgpath'];
print_r($_REQUEST);
$where = array($tblpkey => $id);
// $rowimg = $obj->select_record($tblname, $where);
// $oldimg = $rowimg["imgname"];

if ($id > 0) {

    if ($pdffile != "") {
        @unlink("../$imgpath" . $pdffile);
    }
    $res = $obj->delete_record($tblname, $where);
}
