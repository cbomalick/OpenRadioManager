<?
Echo"<div class=\"requestform\">
    <h2>Manage Requests</h2>
    <form form method=\"post\" action=\"?p=msub\">
    <p style=\"text-align: center;\">
    <form>
    <button class=\"actionbutton approve\" type=\"submit\" name=\"requestbutton\" value=\"approve\" id=\"submit\">Add to Play List</button>
    <button class=\"actionbutton reject\" type=\"submit\" name=\"requestbutton\" value=\"reject\" id=\"submit\">Reject</button>
    </p>
    ";
    $requestList = new RequestList();
    $filteredList = $requestList->filterList('Pending','2019-11-18 00:00:00','2019-11-22 00:00:00');
    $filteredList = $requestList->printManageList($filteredList);

    
Echo"</form></div>";
?>