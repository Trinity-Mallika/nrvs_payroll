<?php include("appsession.php");
$id  = $_REQUEST['id'];
$tblname  = $obj->test_input($_REQUEST['tblname']);
$tblpkey  =  $obj->test_input($_REQUEST['tblpkey']);
$module = isset($_REQUEST['module']) ? $obj->test_input($_REQUEST['module'])  : "";
$submodule = isset($_REQUEST['submodule']) ? $obj->test_input($_REQUEST['submodule']) : "";
$imgname =  $obj->test_input($_REQUEST['imgname']);
$pagename =  $obj->test_input($_REQUEST['pagename']);
$imgpath =  $obj->test_input($_REQUEST['imgpath']);


$where = array($tblpkey => $id);

if ($imgname != "") {
    @unlink("$imgpath" . $imgname);
}
$obj->delete_record($tblname, $where);
echo 1;
