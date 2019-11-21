<?
Echo"<div class=\"requestform\">
    <h2>Play List</h2>";

    $requestList = new RequestList();
    $filteredList = $requestList->filterList('Pending','2019-11-18','2019-11-20');
    $filteredList = $requestList->printPlayList($filteredList);
    
Echo"</div>";
?>