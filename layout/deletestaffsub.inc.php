<?
$staffId = htmlentities($_POST["staffId"], ENT_QUOTES);
$staff = new Staff($staffId);
$staff->deactivateStaff($staffId,$loggedInName);

Echo "<script type=\"text/javascript\">
window.location.href = \"?p=staff\";
</script>";
?>