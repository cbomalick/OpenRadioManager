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
    $filteredList = $requestList->filterList('Pending','2000-01-01 00:00:00',$CurrentDateTime);
    $filteredList = $requestList->printManageList($filteredList);

    
Echo"</form></div>";
?>