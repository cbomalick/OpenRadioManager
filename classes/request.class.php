<?
class Request {
    public $requestId;
    public $CurrentDateTime;

    public function __construct($requestId){
        
        if($requestId == "NEW"){
            return;
        } else {
            $connect = new DBConnect();

            $row = $connect->getData("SELECT requestid,artist,song,requestedby,status,createdtime from request WHERE requestid = '$requestId' ORDER BY createdtime") or die("Error: Request not found.");
            foreach($row as $row){
                $this->requestId = $row['requestid'];
                $this->artist = $row['artist'];
                $this->song = $row['song'];
                $this->requestedBy = $row['requestedby'];
                $this->status = $row['status'];
                $this->createdTime = date("m/d/Y g:i a", strtotime($row['createdtime']));
            }
        }
    }

    public function updateRequestStatus($requestId,$status,$loggedInEmployee){
        $CurrentDateTime = date("Y-m-d H:i:s");

        $connect = new DBConnect();
        //TODO: if ($employee->isValid($loggedInEmployee)){ execute } else { error }
        $sql = "UPDATE request SET status = '$status', lastmodified = '$CurrentDateTime', lastmodifiedby = '$loggedInEmployee' WHERE requestid = '$requestId'";
        $connect->runQuery($sql);
    }

    public function addNewRequest($artist,$song,$name){
        $newId = new IdNumber;
        $newId = $newId->generateID("REQ");
        $CurrentDateTime = date("Y-m-d H:i:s");

        $this->requestId = $newId;
        $this->artist = $artist;
        $this->song = $song;
        $this->requestedBy = $name;
        $this->status = "Pending";
        $this->createdTime = $CurrentDateTime;

        $connect = new DBConnect();
        //TODO: if ($employee->isValid($loggedInEmployee)){ execute } else { error }
        $sql = "INSERT INTO request 
        (requestid,artist,song,requestedby,status,createdtime) VALUES 
        ('$this->requestId','$this->artist','$this->song','$this->requestedBy','$this->status','$this->createdTime')";
        $connect->runQuery($sql);
    }

    public function countTimesRequested($artist,$song){
        $connect = new DBConnect();
        //TODO: if ($employee->isValid($loggedInEmployee)){ execute } else { error }
        $Count = $connect->fetchCount("SELECT * FROM request WHERE artist = '$artist' AND song = '$song' AND status != 'Cancelled'");
        return $Count;
    }

}
?>