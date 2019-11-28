<?
$loggedInUser->checkPagePermission(1);

Echo"<div class=\"requestform\">
    <h2>Play List</h2>";

    $requestList = new RequestList();
    $filteredList = $requestList->filterList('Approved','2000-01-01 00:00:00',$CurrentDateTime);
    $filteredList = $requestList->printPlayList($filteredList);
    
Echo"</div>";
?>