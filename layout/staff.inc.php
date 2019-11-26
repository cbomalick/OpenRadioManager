
<div class="requestform">
<h2>Manage Requests</h2>
<form form method="post" action="?p=msub">
<p style="text-align: center;">
<form>
<button type="button" class="actionbutton approve" onclick="window.location.href = '?p=addstaff';">Add Staff</button>
<button class="actionbutton reject" type="submit" name="removestaff" value="reject" id="submit">Remove Staff</button>
</p>
<?
    $staffList = new StaffList();
    $staffList->printStaffList($staffList->completeList);
?>