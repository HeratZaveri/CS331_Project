<!DOCTYPE html><meta charset="utf-8">
<style> div {border: 2px solid blue; width: 50%; padding: 10px;}</style>
<style> div {display: none;} </style>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Rancho&effect=shadow-multiple|3d-float">
<style type ="text/css">
body {
  background-color: rgba(0, 128, 0, 0.3);
}
p {
  font-family: "Rancho";
  font-size: 30px;
  color: blue;
  text-align: center;
}
table, th, td {
  border: 1px solid black;
  padding: 4px;
}
table{
  width: 100%;
}
th,td{
  text-align: left;
  pading: 10px; 
}
th{
  background-color: lightcoral;
  color: white;
}
tr:nth-child(even){background-color: #f2f2f2}
tr:nth-child(odd){background-color: lightgrey}
</style>
<form action = "cs331.php">
<p class="font-effect-3d">Photo Shop SQL Queries</p>

<?php
//connect database
include("account2.php");
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors' , 1);

$db = mysqli_connect($hostname, $username, $password, $project) ;
if (mysqli_connect_errno())
  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  exit();
  }
print "<br>Successfully connected to MySQL.<br><br><br>";

mysqli_select_db($db, $project);

$choice = "";

if(isset($_GET["choice"])){
  $choice = $_GET["choice"];
}
$p_id = "";
if(isset($_GET["PhotoID"])){
  $p_id = $_GET["PhotoID"];
}

$pname = "";
if(isset($_GET["Photographer"])){
  $pname = $_GET["Photographer"];
}

if($choice == 'option1'){
    $querydb = "SELECT DISTINCT C.CName FROM Customer C, Transaction T WHERE C.LoginName = T.LoginName AND T.TotalAmount > 100";
    print "SQL QUERY for table: $querydb<br><br>";

    ($t = mysqli_query($db, $querydb) or die (mysqli_error($db)));
    echo "<table><tr><th>CName</th></tr>";
    while($r = mysqli_fetch_array($t,MYSQLI_ASSOC)){
      $name    = $r[ "CName" ];
      echo "<tr><td>" .$name. "</td></tr>";
    };
    echo "</table>";
    echo "<br><br>";
}

if($choice == 'option2'){
    $querydb = "SELECT * FROM Photo P WHERE P.PhotoID NOT IN (SELECT P1.PhotoID FROM Photo P1, Transaction T WHERE P1.TransID = T.TransID)";
    print "SQL QUERY for table: $querydb<br><br>";

    ($t = mysqli_query($db, $querydb) or die (mysqli_error($db)));
    echo "<table><tr><th>PhotoID</th><th>Speed</th><th>Film</th><th>Color/B and W</th><th>Resolution</th><th>Date</th><th>Price</th><th>TransID</th><th>Photographer Name</th></tr>";
    while($r = mysqli_fetch_array($t,MYSQLI_ASSOC)){
      $name    = $r[ "PhotoID" ];
      $speed  = $r[ "Speed" ];
      $film  = $r[ "Film" ];
      $fstop  = $r[ "FStop" ];
      $color  = $r[ "ColorBandW" ];
      $res  = $r[ "Resolution" ];
      $trans = $r["TransID"];
      $date  = $r[ "Date" ];
      $price  = $r[ "Price" ];
      $pname  = $r[ "PName" ];
      echo "<tr><td>" .$name. "</td><td>".$speed."</td><td>".$film."</td><td>".$color."</td><td>".$res."</td><td>".$date."</td><td>".$price."</td><td>".$trans."</td><td>".$pname."</td></tr>";
    };
    echo "</table>";
}

if($choice == 'option3'){
    $querydb = "SELECT C.CName FROM Customer C WHERE ((SELECT COUNT(C.LoginName)
	     FROM Customer C, Transaction T, Photo P, Models M
	     WHERE C.LoginName = T.LoginName AND T.TransID = P.TransID AND 	P.PhotoID = M.PhotoID AND M.MName = 'Monica lewis')=(SELECT COUNT(P.PhotoID) FROM Photo P, Models M 
       WHERE P.PhotoID = M.PhotoID AND M.MName = 'Monica lewis'))";
    print "SQL QUERY for table: $querydb<br><br>";

    ($t = mysqli_query($db, $querydb) or die (mysqli_error($db)));
    echo "<table><tr><th>CName</th></tr>";
    while($r = mysqli_fetch_array($t,MYSQLI_ASSOC)){
      $name    = $r[ "CName" ];
      echo "<tr><td>" .$name. "</td></tr>";
    };
    echo "</table>";
}

if($choice == 'option4'){
    $querydb = "SELECT P.PName FROM Photographer P WHERE NOT EXISTS (SELECT * FROM Photographer P, Influences I WHERE P.PName = I.EPName AND P.PBDate = I.EPBDate
    AND NOT EXISTS(SELECT P.PName FROM Photographer P, Influences I WHERE P.PName = I.EPName AND P.PBDate = I.EPBDate AND P.PNationality = 'American'))";
    print "SQL QUERY for table: $querydb<br><br>";

    ($t = mysqli_query($db, $querydb) or die (mysqli_error($db)));
    echo "<table><tr><th>PName</th></tr>";
    while($r = mysqli_fetch_array($t,MYSQLI_ASSOC)){
      $name    = $r[ "PName" ];
      echo "<tr><td>" .$name. "</td></tr>";
    };
    echo "</table>";
}

if($choice == 'option5'){
    $querydb = "SELECT DISTINCT PH.PName, PH.PBDate FROM Photographer PH, Photo P WHERE NOT EXISTS (SELECT * FROM Photographer PH, Photo P WHERE PH.PName = P.PName AND NOT EXISTS 
    (SELECT * FROM Photographer PH, Photo P, Models M WHERE PH.PName = P.PName AND M.PhotoID = P.PhotoID))";
    print "SQL QUERY for table: $querydb<br><br>";

    ($t = mysqli_query($db, $querydb) or die (mysqli_error($db)));
    echo "<table><tr><th>PName</th><th>PBDate</th></tr>";
    while($r = mysqli_fetch_array($t,MYSQLI_ASSOC)){
      $name    = $r[ "PName" ];
      $birth  = $r[ "PBDate" ];
      echo "<tr><td>" .$name. "</td><td>" . $birth.  "</td></tr>";
    };
    echo "</table>";
}
if($choice == 'option6'){
    $querydb = "SELECT P.TransID FROM Photo P, Photo P2 WHERE P.TransID = P2.TransID GROUP BY P.TransID HAVING COUNT(P.TransID)>3";
    print "SQL QUERY for table: $querydb<br><br>";

    ($t = mysqli_query($db, $querydb) or die (mysqli_error($db)));
    echo "<table><tr><th>TransID</th></tr>";
    while($r = mysqli_fetch_array($t,MYSQLI_ASSOC)){
      $trans    = $r[ "TransID" ];
      echo "<tr><td>" .$trans. "</td></tr>";
    };
    echo "</table>";
}
if($choice == 'option7'){
    $querydb = "SELECT DISTINCT M.MName FROM Models M WHERE NOT EXISTS (SELECT P.PName FROM Photographer PH, Photo P WHERE P.PName = PH.PName AND NOT EXISTS(SELECT P.PName FROM Photographer PH,Photo P WHERE P.PName = PH.PName AND P.PName = 'Fernando Gord'))";
    print "SQL QUERY for table: $querydb<br><br>";

    ($t = mysqli_query($db, $querydb) or die (mysqli_error($db)));
    echo "<table><tr><th>MName</th></tr>";
    while($r = mysqli_fetch_array($t,MYSQLI_ASSOC)){
      $name    = $r[ "MName" ];
      echo "<tr><td>" .$name. "</td></tr>";
    };
    echo "</table>";
}

if($choice == 'option8'){
    $querydb = "SELECT P.PName, P.PBDate, SUM(P.Price) FROM Photo P GROUP BY P.PName, P.PBDate ORDER BY SUM(P.Price) DESC";
    print "SQL QUERY for table: $querydb<br><br>";

    ($t = mysqli_query($db, $querydb) or die (mysqli_error($db)));
    echo "<table><tr><th>PName</th><th>PBDate</th><th>Total Price of Photos</th></tr>";
    while($r = mysqli_fetch_array($t,MYSQLI_ASSOC)){
      $name    = $r[ "PName" ];
      $birth  = $r[ "PBDate" ];
      $sum = $r["SUM(P.Price)"];
      echo "<tr><td>" .$name. "</td><td>" . $birth."</td><td>". $sum ."</td></tr>";
    };
    echo "</table>";
}
if($choice == 'option9'){
    $querydb = "DELETE FROM Photo WHERE PhotoID='$p_id'";
    print "SQL QUERY for table: $querydb<br><br>";

    ($t = mysqli_query($db, $querydb) or die (mysqli_error($db)));
     $s = "SELECT * FROM Photo";
    ($t1 = mysqli_query($db, $s) or die (mysqli_error($db)));
    print "After Deletion Table With Information: ";
    echo "<table><tr><th>PhotoID</th><th>Speed</th><th>Film</th><th>F-Stop</th><th>Color/B&W</th><th>Resolution</th><th>Price</th><th>Date</th><th>PName</th><th>PBDate</th></tr>";
    while($r = mysqli_fetch_array($t1,MYSQLI_ASSOC)){
      $name    = $r[ "PhotoID" ];
      $speed = $r[ "Speed" ];
      $film = $r["Film"];
      $fstop = $r["FStop"];
      $color = $r["ColorBandW"];
      $res = $r["Resolution"];
      $price = $r["Price"];
      $Date = $r["Date"];
      $PName = $r["PName"];
      $PBDate = $r["PBDate"];
      echo "<tr><td>" .$name. "</td><td>".$speed."</td><td>".$film."</td><td>".$fstop."</td><td>".$color."</td><td>".$res."</td><td>".$price."</td><td>".$Date."</td><td>"
      .$PName."</td><td>".$PBDate."</td></tr>";
    };
    echo "</table>";
    echo "<br><br>";
}
if($choice == 'option10'){
     $s1 = "SELECT * FROM  Photo WHERE PhotoID = '$p_id'";
    ($t2 = mysqli_query($db, $s1) or die (mysqli_error($db)));
    print "SQL QUERY for table: $s1<br><br>";
    echo "Before Updating Table: ";
    while($r = mysqli_fetch_array($t2,MYSQLI_ASSOC)){
      $id    = $r[ "PhotoID" ];
      $realname = $r[ "PName" ];
      $date = $r["PBDate"];
      echo "Photo ID: " .$id. "  Current Photogrpaher Name for PhotoID: " . $realname.  "  Current BirthDate" .$date. " ";
    };
    
    $querydb = "UPDATE Photo SET PNAME = '$pname' WHERE PhotoID = '$p_id' ";
    print "SQL QUERY for table: $querydb<br><br>";

    ($t = mysqli_query($db, $querydb) or die (mysqli_error($db)));
    print "Updated Table: <br><br>";
    $s = "SELECT * FROM Photo";
    ($t1 = mysqli_query($db, $s) or die (mysqli_error($db)));
    echo "<table><tr><th>PhotoID</th><th>PName</th><th>PBDate</th></tr>";
    while($r = mysqli_fetch_array($t1,MYSQLI_ASSOC)){
      $name    = $r[ "PhotoID" ];
      $realname = $r[ "PName" ];
      $date = $r["PBDate"];
      echo "<tr><td>" .$name. "</td><td>" . $realname.  "</td><td>" .$date. "</td></tr>";
    };
    echo "</table>";
    echo "<br><br>";
}
if($choice == 'option11'){
    $querydb = "SELECT C.CName, T.LoginName, SUM(T.TotalAmount) FROM Customer AS C, Transaction AS T WHERE T.LoginName = C.LoginName GROUP BY C.LoginName";
    print "SQL QUERY for table: $querydb<br><br>";

    ($t = mysqli_query($db, $querydb) or die (mysqli_error($db)));
    echo "<table><tr><th>LoginName</th><th>CName</th><th>TotalAmount</th></tr>";
    while($r = mysqli_fetch_array($t,MYSQLI_ASSOC)){
      $name    = $r[ "LoginName" ];
      $realname = $r[ "CName" ];
      $total = $r["SUM(T.TotalAmount)"];
      echo "<tr><td>" .$name. "</td><td>" . $realname.  "</td><td>" .$total. "</td></tr>";
    };
    echo "</table>";
    echo "<br><br>";
}
if($choice == "option12"){
    $querydb = "SELECT P.PName, P.PBDate, SUM(T.TotalAmount) FROM Photo AS P, Transaction AS T WHERE P.TransID = T.TransID GROUP BY P.PName, P.PBDate";
    print "SQL QUERY for table: $querydb<br><br>";

    ($t = mysqli_query($db, $querydb) or die (mysqli_error($db)));
    echo "<table><tr><th>PName</th><th>PBDate</th><th>Total Sales</th></tr>";
    while($r = mysqli_fetch_array($t,MYSQLI_ASSOC)){
      $name    = $r[ "PName" ];
      $bdate = $r[ "PBDate" ];
      $total = $r["SUM(T.TotalAmount)"];
      echo "<tr><td>" .$name. "</td><td>" . $bdate.  "</td><td>" .$total. "</td></tr>";
    };
    echo "</table>";
    echo "<br><br>";   
}

if($choice == "option13"){
    $query = "(SELECT SUM(T.TotalAmount) FROM LandScape L, Photo P, Transaction T WHERE P.TransID = T.TransID AND P.PhotoID = L.PhotoID) UNION (SELECT SUM(T.TotalAmount)FROM Models M, Photo P, Transaction T WHERE P.TransID = T.TransID AND P.PhotoID = M.PhotoID) UNION (SELECT SUM(T.TotalAmount) FROM Abstract A, Photo P, Transaction T WHERE P.TransID = T.TransID AND P.PhotoID = A.PhotoID)";
    print "SQL QUERY for table: $query<br><br>";

    ($t = mysqli_query($db, $query) or die (mysqli_error($db)));
    echo "<table><tr><th>Total Sales</th></tr>";
    while($r = mysqli_fetch_array($t,MYSQLI_ASSOC)){
      echo "<tr><td>".$r["SUM(T.TotalAmount)"]. "</td></tr>";
    };
    echo "</table>";
    echo "<br><br>";   
}
if($choice == "option14"){
    $querydb = "SELECT T.TDate, SUM(T.TotalAmount) FROM Transaction AS T GROUP BY T.TDate ORDER BY SUM(T.TotalAmount) DESC";
    print "SQL QUERY for table: $querydb<br><br>";

    ($t = mysqli_query($db, $querydb) or die (mysqli_error($db)));
    echo "<table><tr><th>TDate</th><th>TotalAmount</th></tr>";
    while($r = mysqli_fetch_array($t,MYSQLI_ASSOC)){
      $date    = $r[ "TDate" ];
      $total = $r["SUM(T.TotalAmount)"];
      echo "<tr><td>" .$date. "</td><td>" .$total. "</td></tr>";
    };
    echo "</table>";
    echo "<br><br>";   
}
?>

<select id="choice" name="choice">
  <option value = " "> </option>
  <option value="option1" > "List customers who spent more than $100 for the photos." </option>
  <option value="option2" >  "List photos which were not bought."  </option>
  <option value="option3" > "List customers who bought all photos (portraits) in which a model X modeled." </option>
  <option value="option4" > "List photographers who influenced exclusively photographers who are US citizens."        </option>
  <option value="option5" >  "List photographers which took only portrait photos."        </option>
  <option value="option6" >  "List transactions (transID) which contain more than 3 photos." </option>
  <option value="option7" >  "List models who modeled in all photos taken by photographer Y."   </option>
  <option value="option8" > "Rank the photographers by the total cost (sum of prices) of the photos they took." </option>
  <option value="option9" >   "Delete from relation Photo the photo with photoID=X."      </option>
  <option value="option10" >  "Update the photographer name of the photo with photoID=X to Y." </option>
  <option value="option11" >   "Compute total sales per customer "   </option>
  <option value="option12" >  "Compute total sales per photographer sorted by photographer"     </option>
  <option value="option13" > "Compute total sales by photo type Landscape, Abstract, Portrait"       </option>
  <option value="option14" >  "Compute top n dates (in a total sales per date list)"    </option>
</select><br><br>

<div id="PhotoID">
<br>Enter PhotoID <input type = text name = "PhotoID"><br><br>
</div><br><br>

<div id="Photographer">
<br>Enter Photographer Name <input type = text name = "Photographer"><br><br>
</div><br><br>

<input type = submit>

</form>

<script>

  var ptrPID = document.getElementById("PhotoID")
  var ptrPhtghr = document.getElementById("Photographer")
  var ptrChoice = document.getElementById("choice")
  ptrChoice.addEventListener('change', H)
  
  function H(){
      if(ptrChoice.value == "option9"){
          ptrPID.style.display = "block"
      }
      else if(ptrChoice.value != "option9" && ptrChoice.value != "option10"){
          ptrPID.style.display = "none"
      }
      else if(ptrChoice.value == "option10"){
          ptrPID.style.display = "block"
          ptrPhtghr.style.display = "block"
      }
      else if (ptrChoice.value == "option7"){
          ptrPhtghr.style.display = "block"
      }
      else {
        ptrPhtghr.style.display = "none"
      }
  }
</script>


