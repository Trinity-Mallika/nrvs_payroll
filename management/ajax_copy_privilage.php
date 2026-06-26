<?php include("../adminsession.php");

$fromuserid = $_POST['fromuserid'];
$touserid   = $_POST['touserid'];

if ($fromuserid == "" || $touserid == "") {
    echo "Invalid User";
    exit;
} 
// To User Type
$usertype = $obj->getvalfield("user", "usertype", "userid='$touserid'");

$type = ($usertype == 'management' || $usertype == 'super_management')
    ? 'mngmt'
    : 'hrms';

/* Existing privileges remove */
$obj->delete_record('privilage_setting', array(
    'userid' => $touserid
));

/* From User privileges fetch */
$privileges = $obj->executequery("
    SELECT * 
    FROM privilage_setting
    WHERE userid='$fromuserid'
");

foreach ($privileges as $row) {

    $data = array(
        "type"          => $type,
        "userid"        => $touserid,
        "page_id"       => $row['page_id'],
        "pagedit"       => $row['pagedit'],
        "pageview"      => $row['pageview'],
        "pagedel"       => $row['pagedel'],
        "page_add"      => $row['page_add'],
        "page_print"    => $row['page_print'],
        "page_approve"  => $row['page_approve'],
        "page_special"  => $row['page_special'],
        "privilage"     => $row['privilage'],
        "createdby"     => $loginid,
        "ipaddress"     => $ipaddress,
        "createdate"    => $createdate
    );

    $obj->insert_record("privilage_setting", $data);
}

echo "success";
exit;
?>