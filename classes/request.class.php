<?
class Request {
    public $requestId;
    public $CurrentDateTime;

    public function __construct($requestId){
        $this->connect = DBConnect::getInstance()->getConnection();

        if($requestId == "NEW"){
            return;
        } else {

            $sql = "SELECT requestid,artist,song,requestedby,status,createdtime from request WHERE requestid = ? ORDER BY createdtime";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$requestId]);
            $row = $stmt->fetchAll();

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
        //TODO: if ($employee->isValid($loggedInEmployee)){ execute } else { error }

        $sql = "UPDATE request SET status = ?, lastmodified = ?, lastmodifiedby = ? WHERE requestid = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$status, $CurrentDateTime, $loggedInEmployee, $requestId]);
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

        //TODO: if ($employee->isValid($loggedInEmployee)){ execute } else { error }
        $sql = "INSERT INTO request 
        (requestid,artist,song,requestedby,status,createdtime) VALUES 
        (?,?,?,?,?,?)";

        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->requestId, $this->artist, $this->song, $this->requestedBy, $this->status, $this->createdTime]);
    }

    public function countTimesRequested($artist,$song){
        //TODO: if ($employee->isValid($loggedInEmployee)){ execute } else { error }
        $sql = "SELECT COUNT(seqno) FROM request WHERE artist = ? AND song = ? AND status != 'Cancelled'";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$artist, $song]);
        $row = $stmt->fetch();
        $count = $row['COUNT(seqno)'];
        
        return $count;
    }

    public function fetchLastRequested($artist,$song){
        //TODO: if ($employee->isValid($loggedInEmployee)){ execute } else { error }

        $sql = "SELECT createdtime FROM request WHERE artist = ? AND song = ? AND status != 'Cancelled' ORDER BY CREATEDTIME DESC LIMIT 1";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$artist, $song]);
        $result = $stmt->fetchAll();

        foreach ($result as $result){
            $createdTime = date("m/d/Y g:i a", strtotime($result['createdtime']));
            // $mostRecent = date("m/d/Y g:i a", strtotime($mostRecent['createdtime']));
        }
        return $createdTime;
    }

}
?>