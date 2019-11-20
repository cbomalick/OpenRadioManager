<?
    if (isset($_POST['selectedrequest'])) {
        $requests = filter_var_array($_POST['selectedrequest'],FILTER_SANITIZE_STRING); //Accept as array and sanitize
    } else {
        $requests = [];
        Echo "<script type=\"text/javascript\">
        window.location.href = \"?p=requests\";
        </script>";
        Echo "<p>Requests approved. <a href=\"?p=requests\" class=\"clickable\">Click here</a> if your browser does not automatically take you back.</p>";
        return;
    }

    $button = $_POST['requestbutton'];
    if($button == "approve") {
        $action = "approve";
        $requestList = new RequestList();
        $requestList->updateList($action, $requests,$loggedInEmployee);
        
        Echo "<script type=\"text/javascript\">
        window.location.href = \"?p=requests\";
        </script>";
        Echo "<p>Requests approved. <a href=\"?p=requests\" class=\"clickable\">Click here</a> if your browser does not automatically take you back.</p>";
    } else if ($button == "reject"){
        $action = "reject";
        $requestList = new RequestList();
        $requestList->updateList($action, $requests,$loggedInEmployee);

        Echo "<script type=\"text/javascript\">
        window.location.href = \"?p=requests\";
        </script>";
        Echo "<p>Requests approved. <a href=\"?p=requests\" class=\"clickable\">Click here</a> if your browser does not automatically take you back.</p>";
    } else
        Echo "Invalid Button";
?>