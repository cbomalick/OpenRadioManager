<?
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
$CurrentDateTime = date("Y-m-d H:i:s");

if (isset($_GET['p'])) {
	$page = $_GET['p'];
} else {
	$page= "";
}
?>

<html>
<head>
    <title>OpenRadioManager Installation Wizard</title>
    <link href="css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="container">
        <div class="panel">
            <?
            $CurrentURL = $_SERVER['HTTP_HOST'];
            $CurrentPage = $_SERVER['REQUEST_URI'];
            $pieces = explode("/", $CurrentPage);
            $i = 1;
            $installPath = "";

            while($i < (count($pieces)-1)){
                $installPath = $installPath . "/" . $pieces[$i];
                $i++;
            }

            switch($page){
                default:
                Echo "<h2>OpenRadioManager Installation Wizard</h2>
                <form form method=\"post\" action=\"?p=installsub\">
                <p class=\"header\">Station Name <span class=\"requiredfield\">*</span></p>
                    <p><input name=\"stationName\" id=\"stationName\" value=\"\"></p>
                <p class=\"header\">Description</p>
                    <textarea name=\"stationDescription\"></textarea></p>
                <p class=\"header\">Timezone <span class=\"requiredfield\">*</span></p>
                    <p>
                        <select name=\"timeZone\" id=\"timeZone\">
                            <option value=\"America/New_York\">Eastern</option>
                            <option value=\"America/Chicago\" selected=\"\">Central</option>
                            <option value=\"America/Denver\">Mountain</option>
                            <option value=\"America/Phoenix\">Mountain no DST</option>
                            <option value=\"America/Los_Angeles\">Pacific</option>
                            <option value=\"America/Anchorage\">Alaska</option>
                            <option value=\"America/Adak\">Hawaii</option>
                            <option value=\"Pacific/Honolulu\">Hawaii no DST</option>
                        </select>
                    </p>
                    <p class=\"header\">MySQL Database Name <span class=\"requiredfield\">*</span></p>
                        <p><input name=\"sqlDatabase\" id=\"sqlDatabase\" value=\"\"></p>
                    <p class=\"header\">MySQL Username <span class=\"requiredfield\">*</span></p>
                        <p><input name=\"sqlUser\" id=\"sqlUser\" value=\"\"></p>
                    <p class=\"header\">MySQL Password <span class=\"requiredfield\">*</span></p>
                        <p><input name=\"sqlPass\" id=\"sqlPass\" value=\"\"></p>
                <p class=\"header\">Domain <span class=\"requiredfield\">*</span></p>
                    <p><input name=\"domain\" id=\"domain\" value=\"{$CurrentURL}\"></p>
                <p class=\"header\">Installation Path <span class=\"requiredfield\">*</span></p>
                    <p><input name=\"installPath\" id=\"installPath\" value=\"{$installPath}\"></p>
                <p class=\"header\">Admin First Name <span class=\"requiredfield\">*</span></p>
                    <p><input name=\"firstName\" id=\"firstName\" value=\"\"></p>
                <p class=\"header\">Admin Last Name <span class=\"requiredfield\">*</span></p>
                    <p><input name=\"lastName\" id=\"lastName\" value=\"\"></p>
                <p class=\"header\">Admin Email <span class=\"requiredfield\">*</span></p>
                    <p><input name=\"email\" id=\"email\" value=\"\"></p>
                <button class=\"actionbutton request\" type=\"submit\" name=\"submit\" id=\"submit\">Submit Request</button>
                </form>";
                break;

                case"installsub":
                if(!empty($_POST["stationName"]) && !empty($_POST["timeZone"]) && !empty($_POST["domain"]) && !empty($_POST["installPath"]) && !empty($_POST["sqlDatabase"]) && !empty($_POST["sqlUser"]) && !empty($_POST["sqlPass"])  && !empty($_POST["firstName"])  && !empty($_POST["lastName"])  && !empty($_POST["email"])){
                    $stationName = htmlentities($_POST['stationName'], ENT_QUOTES);
                    $stationDescription = htmlentities($_POST['stationDescription'], ENT_QUOTES);
                    $timeZone = htmlentities($_POST['timeZone'], ENT_QUOTES);
                    $domain = htmlentities($_POST['domain'], ENT_QUOTES);
                    $installPath = htmlentities($_POST['installPath'], ENT_QUOTES);
                    $sqlDatabase = htmlentities($_POST['sqlDatabase'], ENT_QUOTES);
                    $sqlUser = htmlentities($_POST['sqlUser'], ENT_QUOTES);
                    $sqlPass = htmlentities($_POST['sqlPass'], ENT_QUOTES);
                    $firstName = htmlentities($_POST['firstName'], ENT_QUOTES);
                    $lastName = htmlentities($_POST['lastName'], ENT_QUOTES);
                    $email = htmlentities($_POST['email'], ENT_QUOTES);

                    //Test db connection
                    $mysqli = new MySQLi('localhost', $sqlUser, $sqlPass, $sqlDatabase);
                    if ($mysqli->connect_errno) {
                        printf("Connect failed: %s\n", $mysqli->connect_error);
                        exit();
                    }
                    else {
                    echo "Connection confirmed. <br />";

                    //Write database login to file
                    $dbPath = "classes/config.ini";

                    $updateFile = file_get_contents($dbPath);
                    $updateFile = str_replace("**REPLACE_USER**", "$sqlUser",$updateFile);
                    file_put_contents($dbPath, $updateFile);

                    $updateFile = file_get_contents($dbPath);
                    $updateFile = str_replace("**REPLACE_PASSWORD**", "$sqlPass",$updateFile);
                    file_put_contents($dbPath, $updateFile);

                    $updateFile = file_get_contents($dbPath);
                    $updateFile = str_replace("**REPLACE_DB**", "$sqlDatabase",$updateFile);
                    file_put_contents($dbPath, $updateFile);
                    
                    //Create tables
                    $sqlTables = "CREATE TABLE `accounts` (
                        `id` int(11) NOT NULL,
                        `staffid` varchar(16) NOT NULL,
                        `email` varchar(100) NOT NULL,
                        `password` varchar(255) NOT NULL,
                        `forcereset` varchar(10) NOT NULL,
                        `tokens` text NOT NULL,
                        `userlevel` int(8) NOT NULL,
                        `status` varchar(25) NOT NULL,
                        `createdby` varchar(50) NOT NULL,
                        `createddate` datetime NOT NULL,
                        `lastmodifiedby` varchar(50) NOT NULL,
                        `lastmodifieddate` datetime NOT NULL,
                        `cancelledby` varchar(50) NOT NULL,
                        `canceldate` datetime NOT NULL
                      );
                      
                      CREATE TABLE `failed_login` (
                        `seqno` int(8) NOT NULL,
                        `email` varchar(200) NOT NULL,
                        `reason` text NOT NULL,
                        `createdtime` datetime NOT NULL,
                        `ip` varchar(25) NOT NULL,
                        `status` varchar(25) NOT NULL
                      );
                      
                      CREATE TABLE `password_change_requests` (
                        `seqno` int(8) NOT NULL,
                        `email` varchar(100) NOT NULL,
                        `time` datetime NOT NULL,
                        `hashstring` varchar(40) NOT NULL,
                        `status` varchar(25) NOT NULL,
                        `ipaddress` varchar(25) NOT NULL
                      );
                      
                      CREATE TABLE `request` (
                        `seqno` int(8) NOT NULL,
                        `requestid` varchar(16) NOT NULL,
                        `artist` text NOT NULL,
                        `song` text NOT NULL,
                        `requestedby` varchar(200) NOT NULL,
                        `status` varchar(25) NOT NULL,
                        `createdtime` datetime NOT NULL,
                        `lastmodified` datetime NOT NULL,
                        `lastmodifiedby` varchar(25) NOT NULL
                      );
                      
                      CREATE TABLE `staff` (
                        `seqno` int(8) NOT NULL,
                        `staffid` varchar(16) NOT NULL,
                        `firstname` varchar(100) NOT NULL,
                        `lastname` varchar(100) NOT NULL,
                        `email` varchar(100) NOT NULL,
                        `hiredate` date NOT NULL,
                        `userlevel` int(8) NOT NULL,
                        `status` varchar(25) NOT NULL,
                        `createdby` varchar(100) NOT NULL,
                        `createddate` datetime NOT NULL,
                        `lastmodifiedby` varchar(100) NOT NULL,
                        `lastmodifieddate` datetime NOT NULL
                      );
                      
                      CREATE TABLE `station` (
                        `seqno` int(8) NOT NULL,
                        `name` varchar(200) NOT NULL,
                        `description` text NOT NULL,
                        `timezone` varchar(100) NOT NULL,
                        `domain` varchar(200) NOT NULL,
                        `installpath` varchar(200) NOT NULL,
                        `webaddress` varchar(100) NOT NULL,
                        `status` varchar(25) NOT NULL
                      );
                      
                      ALTER TABLE `accounts`
                        ADD PRIMARY KEY (`id`);
                      
                      ALTER TABLE `failed_login`
                        ADD PRIMARY KEY (`seqno`);
                      
                      ALTER TABLE `password_change_requests`
                        ADD PRIMARY KEY (`seqno`);
                      
                      ALTER TABLE `request`
                        ADD PRIMARY KEY (`seqno`);
                      
                      ALTER TABLE `staff`
                        ADD PRIMARY KEY (`seqno`);
                      
                      ALTER TABLE `station`
                        ADD PRIMARY KEY (`seqno`);
                      
                      ALTER TABLE `accounts`
                        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
                      
                      ALTER TABLE `failed_login`
                        MODIFY `seqno` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
                      
                      ALTER TABLE `password_change_requests`
                        MODIFY `seqno` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
                      
                      ALTER TABLE `request`
                        MODIFY `seqno` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
                      
                      ALTER TABLE `staff`
                        MODIFY `seqno` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
                      
                      ALTER TABLE `station`
                        MODIFY `seqno` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
                        
                    INSERT INTO station (seqno, name, description, timezone, domain, installpath, status) VALUES
                (1, '$stationName', '$stationDescription', '$timeZone', '$domain', '$installPath', 'Active');";

                    if ($result = $mysqli->multi_query($sqlTables) === TRUE) {
                    } else {
                        Echo "Error, tables not created <br />";
                        exit;
                    }

                    include('classes/packages.inc.php');

                    while ($mysqli->next_result()) {;}
                    //Create admin user
                    $staff = new Staff("NEW");
                    $staff->addNewStaff($firstName,$lastName,$email,$CurrentDateTime,4,'Installation');
                
                    Echo"Setup complete";
                    unlink(basename($_SERVER['PHP_SELF'])) or die("Couldn't delete file");
                    }
                    Echo "<script type=\"text/javascript\">
                    window.location.href = \"index.php\";
                    </script>";
                } else {
                    Echo"<div class=\"requestform\">
                        <h2>Manage Station</h2>
                            <p style=\"text-align: center;\">Please fill out all required fields</p>
                        </div>";
                }

                break;
            }
            ?>

        </div>
            <? include('layout/footer.inc.php'); ?>
    </div>
</body>
</html>