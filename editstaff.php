<html>
   <head>
      <title>Edit Staff Member Details</title>
      <span class="author" value="James King"></span>
      <span class="module" value="08246"></span>
      <link rel="stylesheet" href="resources/style.css" type="text/css">
	  <script>
		function validateForm() {
			var myUser = document.forms["editstaff"]["username"].value,
			myFirst = document.forms["editstaff"]["firstname"].value,
			myLast = document.forms["editstaff"]["lastname"].value;
			
			if(myUser.trim().length == 0){
				alert("Please input data into the Username box!");
				return false;
			}
			else if(myFirst.trim().length == 0){
				alert("Please input data into the Firstname box!");
				return false;
			}
			else if(myLast.trim().length == 0){
				alert("Please input data into the Lastname box");
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
	  <h4 id="tutorialtext" style="margin-left: 1vw; margin-top: 2vh;" >Enter a User, First and Lastname into the entry fields below and press submit to register the new member to the databases</h4>
		<?php
			error_reporting(0);
			function PostUserRequest(){
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
					$target = $_REQUEST['username'];
					$first = $_REQUEST['firstname'];
					$last = $_REQUEST['lastname'];
					$existQuery     = "SELECT * FROM locationusers WHERE Username = '" . $target . "'";
					$returnedResult = sqlsrv_query($myConnection, $existQuery);
					$resultArray = sqlsrv_fetch_array($returnedResult, SQLSRV_FETCH_ASSOC);
					if ($resultArray == "") {
						$postQuery = "INSERT INTO locationusers (Username, Firstname, Surname) VALUES ('" . $target . "', '" . $first . "', '" . $last . "')";
						if (sqlsrv_query($myConnection, $postQuery)) {
							sqlsrv_close($myConnection);
							echo '<h2 id="updateresult" style="margin-left: 1vw; margin-top: 2vh;">Successful update</h2>';
						} 
						else {
							sqlsrv_close($myConnection);
							echo '<h2 id="updateresult" style="margin-left: 1vw; margin-top: 2vh;">Bad update</h2>';
						}
					}
					else{
						$postQuery = "UPDATE locationusers SET Firstname = '" . $first . "', Surname = '" . $last ."' WHERE Username = '" . $target ."';";
						if (sqlsrv_query($myConnection, $postQuery)) {
							sqlsrv_close($myConnection);
							echo '<h2 id="updateresult" style="margin-left: 1vw; margin-top: 2vh;">Successful update</h2>';
						} 
						else {
							sqlsrv_close($myConnection);
							echo '<h2 id="updateresult" style="margin-left: 1vw; margin-top: 2vh;">Bad update</h2>';
						}
					}
				}
				sqlsrv_close($myConnection);
			}
			if(isset($_POST)){
				PostUserRequest();
			}
		?>
		<form class="requestform" name="editstaff" action="editstaff.php" method="post">
			Username: 
			<input type="text" name="username" required="required"> <br/>
			First Name:  
			<input type="text" name="firstname" required="required"> <br/>
			Last Name: 
			<input type="text" name="lastname" required="required"> <br/>
			<input type="submit" content="Submit" onclick="validateForm()">
		</form>
	  </div>
	</body>
</html>

<!-- blah 