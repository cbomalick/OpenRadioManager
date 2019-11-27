
<div class="requestform">
<h2>Manage Staff</h2>
<form form method="post" action="?p=unlockstaffsub">
<p style="text-align: center;">
<form>
<button type="button" class="actionbutton approve" onclick="window.location.href = '?p=addstaff';">Add Staff</button>
<button class="actionbutton reject" type="submit" name="unlock" value="unlock" id="submit">Unlock Staff</button>
</p>
<?
    $staffList = new StaffList();
    $staffList->printStaffList($staffList->completeList);
?>
</form></div>