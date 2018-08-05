<html>
   <head>
      <title>List Current Locations</title>
      <span class="author" value="James King"></span>
      <span class="module" value="08246"></span>
      <link rel="stylesheet" href="resources/style.css" type="text/css">
	  <script>
		function validateForm() {
			var myUser = document.forms["locationlistform"]["location"].value;
			
			if(myUser.trim().length == 0){
				alert("Please input data into the Location box!");
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
	  <div id="formdiv">
		<form class="requestform" name="locationlistform" action="locationlist.php" method="get">
			Location:
			<input type="text" name="location" required="required">
			<input type="submit" content="Submit" onclick="return validateForm()">
		</form>
		<h4 id="tutorialtext" style="margin-left: 1vw; margin-top: 2vh;" >Enter a location from the list below and press Submit to return the users in that location</h4>
	  </div>
		<div id="locationlistdiv">
			<?php
				error_reporting(0);
				if(isset($_GET))
				{
					GetInhabitingUsers();
				}
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
					$myLocationQuery = "SELECT DISTINCT Location FROM locations";
					$locationsTable = sqlsrv_query($myConnection, $myLocationQuery);
					while($locationsArray = sqlsrv_fetch_array($locationsTable, SQLSRV_FETCH_ASSOC))
					{
						echo '<h3 id="locationlistelement" style="margin-left: 1vw; margin-top: 2vh;">' . $locationsArray['Location'] . '</h3>';
					}
				}
				function GetInhabitingUsers()
				{
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
						$myLocation = $_REQUEST['location'];
						$myQuery = "SELECT FUsername FROM(SELECT *, ROW_NUMBER() OVER (PARTITION BY FUsername ORDER BY TimeOfChange DESC) rn FROM locations) q WHERE rn = 1 AND Location = '".$myLocation . "'";
						$result = sqlsrv_query($myConnection, $myQuery);
						$resultsArray = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
						if($resultsArray == "")
						{
							echo '<h2 id="updateresult" style="margin-left: 1vw; margin-top: 2vh;">Error: No users in this locations</h2>';
						}
						else
						{
							echo '<table id="currentlocationlist" style="margin-left: 1vw; margin-top: 2vh;">';
							echo '<tr><th>Users in Location: ' . $myLocation . '</th></tr>';
							echo "<tr><td>" . $resultsArray['FUsername'] . "</td></tr>";
								while($resultsArray = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
									echo '<tr><td>';
									echo $resultsArray['FUsername'];
									echo '</td></tr>';
								}
							echo '</table>';
						}
					}
				}
			?>
		</div>
	  </div>
	</body>
</html>