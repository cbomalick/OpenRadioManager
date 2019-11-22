<?
Echo"<div class=\"requestform\">
    <h2>Play List</h2>";

    $requestList = new RequestList();
    $filteredList = $requestList->filterList('Approved','2019-11-18 00:00:00','2019-11-22 00:00:00');
    $filteredList = $requestList->printPlayList($filteredList);
    
Echo"</div>";
?>