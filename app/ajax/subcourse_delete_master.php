<?php include("../../adminsession.php");


$imgpath = "uploaded/sub_course_gallery/";
$pdfpath = "uploaded/paper_pdf/";
$id  = $_REQUEST['id'];

$tblname  = $_REQUEST['tblname'];

$tblpkey  = $_REQUEST['tblpkey'];

$module = $_REQUEST['module'];

$submodule = $_REQUEST['submodule'];

$pagename = $_REQUEST['pagename'];
// print_r($_REQUEST); die;
if ($id > 0) {
	$where = array($tblpkey => $id);

       
     $res = $obj->executequery("select * from sub_course_gallery where sub_course_id='$id'");
        foreach ($res as $row_get) 
        {
         $imagename = $row_get['imagename'];
         
         
         if ($imagename != "") 
         {
		   @unlink("../$imgpath" . $imagename);
	     }
	     $where1 = array('sub_course_id' => $id);
	     $obj->delete_record("sub_course_gallery", $where1);
        }
        $res1 = $obj->executequery("select * from previous_paper where sub_course_id='$id'");
        foreach ($res1 as $rowget) 
        {
         $paper_pdf = $rowget['paper_pdf'];
         
         
         if ($paper_pdf != "") 
         {
		   @unlink("../$pdfpath" . $paper_pdf);
	     }
	     $where2 = array('sub_course_id' => $id);
	     $obj->delete_record("previous_paper", $where2);
        }

       
	

	$obj->delete_record($tblname, $where);

}
echo 1;
