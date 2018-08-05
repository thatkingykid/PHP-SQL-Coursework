<?php
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$returnedData = PostLocationRequest($_REQUEST['person'], $_REQUEST['location']);
	} 
	else {
		$returnedData = GetLocationRequest($_REQUEST['person']);
	}
	ob_start();
	echo $returnedData;
	ob_end_flush();

function GetLocationRequest($target)
{
	date_default_timezone_set('Europe/London');
    $server         = "SQL2008.net.dcs.hull.ac.uk";
    $connectionInfo = array(
        "Database" => "rde_510757"
    );
    $myConnection   = sqlsrv_connect($server, $connectionInfo);
    
    if (!$myConnection) {
        return "HTTP/1.1 404 Not Found \r\nContent-Type: text/plain\r\n\r\n";
    } else {
        $myQuery = "SELECT * FROM locations WHERE FUsername = '" . $target . "' ORDER BY TimeOfChange DESC";
        $results = sqlsrv_query($myConnection, $myQuery);
		$row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC);
		if($row != false)
		{
				sqlsrv_close($myConnection);
				return "HTTP/1.1 200 OK \r\nContent-Type: text/plain\r\n\r\n" . $row['Location'] . "\r\n";
		}
		else
		{
			return "HTTP/1.1 404 Not Found \r\nContent-Type: text/plain\r\n\r\n";
		}
    }
}
function PostLocationRequest($target, $update)
{
    $server         = "SQL2008.net.dcs.hull.ac.uk";
    $connectionInfo = array(
        "Database" => "rde_510757"
    );
    $myConnection   = sqlsrv_connect($server, $connectionInfo);
    
    
    if (!$myConnection) {
        return "HTTP/1.1 404 Not Found \r\nContent-Type: text/plain\r\n\r\n";
    } else {
        $existQuery     = "SELECT Username FROM locationusers WHERE Username = '" . $target . "'";
        $returnedResult = sqlsrv_query($myConnection, $existQuery);
		$resultArray = sqlsrv_fetch_array($returnedResult, SQLSRV_FETCH_ASSOC);
		$successfulAdd = true;
        if ($resultArray == "") {
            $successfulAdd = PostUserRequest($target, "NOT INCLUDED", "NOT INCLUDED");
        }
		if($successfulAdd){
			$myPostQuery = "INSERT INTO locations (FUsername, Location, TimeOfChange)
		VALUES ('" . $target . "', '" . $update . "', getdate())";
        
			if (sqlsrv_query($myConnection, $myPostQuery)) {
				sqlsrv_close($myConnection);
				return "HTTP/1.1 200 OK \r\nContent-Type: text/plain\r\n\r\n";
			} else {
				sqlsrv_close($myConnection);
				return "HTTP/1.1 404 Not Found \r\nContent-Type: text/plain\r\n\r\n";
			}
		}
		else {
				sqlsrv_close($myConnection);
				return "HTTP/1.1 404 Not Found \r\nContent-Type: text/plain\r\n\r\n";
			}
    }
}

	function PostUserRequest($userToEnter, $first, $last)
	{
		$server         = "SQL2008.net.dcs.hull.ac.uk";
    $connectionInfo = array(
        "Database" => "rde_510757"
    );
    $myConnection   = sqlsrv_connect($server, $connectionInfo);
    
    
    if (!$myConnection) {
        return "Bad connection to server";
    } else {
		$myQuery = "INSERT INTO locationusers (Username, Firstname, Surname) VALUES ('" . $userToEnter . "', '" . $first . "', '" . $last . "')";
		if(sqlsrv_query($myConnection, $myQuery))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	}
?>