<html>
   <head>
      <title>List Current Locations</title>
      <span class="author" value="James King"></span>
      <span class="module" value="08246"></span>
      <link rel="stylesheet" href="resources/style.css" type="text/css">
	  <script>
		function validateForm() {
			var myUser = document.forms["locationform"]["username"].value;
			
			if(myUser.trim().length == 0){
				alert("Please input data into the Username box!");
				return false;
			}
			else
			{
				return true;
			}
		}
	  </script>
   </head>
   <body>
      <div id="menudiv">
         <ul id="fixedmenu">
			<li id="menubutton"><a href="currentlocations.php">CURRENT LOCATIONS</a></li>
            <li id="menubutton"><a href="changelocation.php">CHANGE LOCATIONS</a></li>
            <li id="menubutton"><a href="editstaff.php">EDIT STAFF DETAILS</a></li>
            <li id="menubutton"><a href="prevtwentyfour.php">PREVIOUS 24 HOURS</a></li>
            <li id="menubutton"><a href="locationlist.php">LOCATION LIST</a></li>
         </ul>
      </div>
	  <div id="content">
	  <h4 id="tutorialtext" style="margin-left: 1vw; margin-top: 2vh;" >Enter a Username and press Submit to produce a table of a User's locations in the past 24 hours</h4>
		<form class="requestform" name="locationform" action="prevtwentyfour.php" method="get">
			Username: 
			<input type="text" name="username" required="required"> <br/>
			<input type="submit" content="Submit" onclick="validateForm()">
		</form>
		<?php 
			error_reporting(0);
			function GetBauerLocation() {
				$server         = "SQL2008.net.dcs.hull.ac.uk";
				$connectionInfo = array(
					"Database" => "rde_510757"
				);
				$myConnection   = sqlsrv_connect($server, $connectionInfo);
				
				if(!$myConnection) {
					echo '<script language="javascript">';
					echo 'alert("Failure in connecting to the SQL Server")';
					echo '</script>';
					exit;
				}
				else{
					date_default_timezone_set("Europe/London");
					$target = $_REQUEST['username'];
					$myQuery = "SELECT Location, TimeOfChange FROM locations WHERE FUsername = '". $target . "' AND TimeOfChange > dateadd(hour, -24, getdate())";
					$result = sqlsrv_query($myConnection, $myQuery);
					$resultsArray = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
					if($resultsArray == "")
					{
						echo '<h2 id="updateresult" style="margin-left: 1vw; margin-top: 2vh;">Error: User not in a location</h2>';
					}
					else
					{
						echo '<table id="currentlocationlist" style="margin-left: 1vw; margin-top: 2vh;">';
						echo '<tr><th>Location</th> <th>Time Moved</th></tr>';
						echo "<tr><td>" . $resultsArray['Location'] . "</td><td>" . $resultsArray['TimeOfChange']->format('d-m-y h-m-s') . "</td></tr>";
						while($resultsArray = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
							echo '<tr><td>';
							echo $resultsArray['Location'];
							echo '</td> <td>';
							echo $resultsArray['TimeOfChange']->format('d-m-y h-m-s');
							echo '</td></tr>';
						}
						echo '</table>';
					}
				}
				sqlsrv_close($myConnection);
			}
			if(isset($_GET))
			{
				GetBauerLocation();
			}
		?>
	  </div>
	</body>
</html>