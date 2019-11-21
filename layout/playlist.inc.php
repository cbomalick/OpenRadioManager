<?
Echo"<div class=\"requestform\">
    <h2>Play List</h2>";

    $requestList = (new RequestList())->filterList('Approved','2019-11-18','2019-11-19');
    var_dump($requestList);
    Echo "<br><br>";

    Echo "<br><br>";
    $filteredList = $requestList->printPlayList($requestList);
    var_dump($filteredList);
    Echo "<br><br>";

    
Echo"</div>";
?>