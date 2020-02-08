<?
// $connect = new DBConnect();
// $con = $connect->connectDB();

$connect = DBConnect::getInstance()->getConnection();
$staff = new Staff("NEW");

//Verify username and password were submitted
if (!isset($_POST['email'], $_POST['password'])){
	die ('Username and/or password does not exist!');
}

$email = $_POST['email'];

//Match submitted details to database
	// $stmt = $connect->prepare("SELECT staffid,email,password,userlevel FROM accounts WHERE email = ? AND status = 'Active'");
	// $stmt->bind_param('s',$email);
	// $stmt->execute();
	// $stmt->store_result(); 

	$sql = "SELECT staffid,email,password,userlevel FROM accounts WHERE email = ? AND status = 'Active'";
	$stmt = $connect->prepare($sql);
	$stmt->execute([$email]);
	
	if (count($stmt) > 0){
		$row = $stmt->fetch();
		$staffId = $row['staffid'];
		$email = $row['email'];
		$password = $row['password'];

		//If Account exists, verify password
		if (password_verify($_POST['password'],$password)){
			//Success
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['id'] = $staffId;
            Echo"<p style=\"text-align: center;\">Logged in successfully</p>";
            Echo "<script type=\"text/javascript\">
                window.location.href = \"index.php\";
                </script>";
			//AuditLog('Logged In','Desktop Login',$employeeid);
			$staff->unlockStaff($staffId);
			exit();
		} else {
			Echo "<p style=\"text-align: center;\">Incorrect username and/or password</p>";
			//$Username = FilterInput($_POST['email']);
			//AuditLog('Failed Login','Incorrect Password Attempted',$Username);
			$staff->threeStrikes($email,"Incorrect Password Attempted");
		}
	} else {
		Echo "<p style=\"text-align: center;\">Incorrect username and/or password</p>";
			//$Username = FilterInput($_POST['email']);
			//AuditLog('Failed Login','Incorrect Username Attempted',$Username);
			$staff->threeStrikes($email,"Incorrect Username Attempted");
	}

?>