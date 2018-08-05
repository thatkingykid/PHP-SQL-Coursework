<html>
   <head>
      <title>Change a Staff Member's Location</title>
      <span class="author" value="James King"></span>
      <span class="module" value="08246"></span>
      <link rel="stylesheet" href="resources/style.css" type="text/css">
	  <script>
		function validateForm() {
			var myUser = document.forms["changelocation"]["person"].value,
			myLocation = document.forms["changelocation"]["location"].value;
			
			if(myUser.trim().length == 0){
				alert("Please input data into the Username box!");
				return false;
			}
			else if(myLocation.trim().length == 0){
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
	  <h3 id="tutorialtext" style="margin-left: 1vw; margin-top: 2vh;">Enter a Username and a Location into the form below, and click submit to register the new location to the server</h3>
		<form class="requestform" name="changelocation" action="location.php" method="post">
			Username: 
			<input type="text" name="person" required="required"> <br/>
			New Location: 
			<input type="text" name="location" required="required"> <br/>
			<input type="submit" content="Submit" onclick="return validateForm()">
		</form>
	  </div>
	</body>
</html>