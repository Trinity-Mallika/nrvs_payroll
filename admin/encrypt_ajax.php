<?php
include("../adminsession.php");

$site_ids_string = $_POST['site_ids'] ?? '';
$site_ids = explode(",", $site_ids_string);

$joined = implode(",", $site_ids);
if (empty($joined)) {
    echo "No site IDs provided.";
    exit;
}

$encrypted = $obj->my_simple_crypt($joined, 'e');
echo $encrypted;
