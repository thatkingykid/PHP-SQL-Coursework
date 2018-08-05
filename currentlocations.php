<html>
   <head>
      <title>List Current Locations</title>
      <span class="author" value="James King"></span>
      <span class="module" value="08246"></span>
      <link rel="stylesheet" href="resources/style.css" type="text/css">
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
			<?php 
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
					$myQuery = "SELECT FUsername, Location FROM(SELECT *, ROW_NUMBER() OVER (PARTITION BY FUsername ORDER BY TimeOfChange DESC) rn FROM locations) q WHERE rn = 1";
					$result = sqlsrv_query($myConnection, $myQuery);
					$resultsArray = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
					if($resultsArray == "")
					{
						echo '<h2 id="updateresult" style="margin-left: 1vw; margin-top: 2vh;">Error: No users in any locations</h2>';
					}
					else{
						echo '<table id="currentlocationlist" style="margin-left: 1vw; margin-top: 2vh;" border="1">';
						echo '<tr><th>Username</th> <th>Location</th></tr>';
						while($resultsArray = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
							echo '<tr><td>';
							echo $resultsArray['FUsername'];
							echo '</td> <td>';
							echo $resultsArray['Location'];
							echo '</td> </tr>';
						}
						echo '</table>';
					}
				}
				sqlsrv_close($myConnection);
			?>
		</tr>
	  </div>
	</body>
</html>