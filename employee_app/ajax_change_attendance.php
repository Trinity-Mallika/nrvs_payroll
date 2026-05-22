<?php include("appsession.php");
$tblname = "attendance_entry";
$attendance = $obj->test_input($_REQUEST['attendance']);
$attendance_date = $_REQUEST['attendance_date'];
$empid = $obj->test_input($_REQUEST['empid']);
$attendance_month = substr($attendance_date, 0, 7);
$sundays = $obj->getSundays($attendance_month);
$attendance_day = (int)substr($attendance_date, 8, 2);

if ($attendance == 'present' && $attendance_date != "") {
    /* check data already exists */
    $present = $obj->getvalfield($tblname, "count(*)", "empid = '$empid' and attendance_date = '$attendance_date'");
    if ($present == 0) {
        $form_data = array('empid' => $empid, 'attendance_date' => $attendance_date, 'createdate' => $createdate);
        $obj->insert_record($tblname, $form_data);
    } else {
        /* check half day marked */
        $is_half_day = $obj->getvalfield($tblname, "is_half_day", "empid = '$empid' and attendance_date = '$attendance_date'");
        if ($is_half_day == 1) {
            $form_data = array('is_half_day' => 0);
            $where = array('empid' => $empid, 'attendance_date' => $attendance_date);
            $obj->update_record($tblname, $where, $form_data);
        }
    }

    echo 1;
} elseif ($attendance == 'absent' && $attendance_date != "") {
    $where = array('empid' => $empid, 'attendance_date' => $attendance_date);
    $obj->delete_record($tblname, $where);
    echo 2;
} elseif ($attendance == 'halfday' && $attendance_date != "") {

    /* check data already exists */
    $present = $obj->getvalfield($tblname, "count(*)", "empid = '$empid' and attendance_date = '$attendance_date'");
    if ($present == 0) {
        $form_data = array('empid' => $empid, 'attendance_date' => $attendance_date, 'is_half_day' => 1, 'createdate' => $createdate);
        $obj->insert_record($tblname, $form_data);
    } else {
        /* convert to half day */
        $is_half_day = $obj->getvalfield($tblname, "is_half_day", "empid = '$empid' and attendance_date = '$attendance_date'");
        if ($is_half_day == 0) {
            $form_data = array('is_half_day' => 1);
            $where = array('empid' => $empid, 'attendance_date' => $attendance_date);
            $obj->update_record($tblname, $where, $form_data);
        }
    }
    echo 3;
} else {
    echo 4;
}
