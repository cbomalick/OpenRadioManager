<?
Echo"<div class=\"requestform\">
    <h2>Manage Requests</h2>
    <form form method=\"post\" action=\"?p=msub\">
    <p style=\"text-align: center;\">
    <form>
    <button class=\"actionbutton approve\" type=\"submit\" name=\"requestbutton\" value=\"approve\" id=\"submit\">Approve</button>
    <button class=\"actionbutton reject\" type=\"submit\" name=\"requestbutton\" value=\"reject\" id=\"submit\">Reject</button>
    </p>
    ";
    $requestList = new RequestList();
    $requestList->printList('Pending');
Echo"</form></div>";
?>