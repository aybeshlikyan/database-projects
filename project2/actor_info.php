<!DOCTYPE html>
<html>
    <head>
        <?php
            $id = $_GET["id"]; 
            $db = new mysqli('localhost', 'cs143', '', 'cs143');
            if ($db->connect_errno > 0) { 
                die('Unable to connect to database [' . $db->connect_error . ']'); 
            }

            $actorQuery = "SELECT last,first,dob,dod,sex FROM Actor WHERE id=$id";
            $actor = $db->query($actorQuery);
            if (!$actor) {
                $errmsg = $db->error; 
                print "Actor Query failed: $errmsg <br>"; 
                exit(1); 
            }
            $last = ""; 
            $first = ""; 
            $dob = "";
            $dod = "";
            $sex = "";
            $title = "";
            $year = "";
            $actorExists = $actor->num_rows;
            $moviesExist = 0;
            if ($actorExists){
                $actor=$actor->fetch_assoc();
                $last = $actor['last']; 
                $first = $actor['first']; 
                $dob = $actor['dob'];
                $dod = $actor['dod'];
                if ($dod == "") {$dod = "Still Alive";}
                $sex = $actor['sex'];
                
                $movieQuery = "SELECT Movie.title, Movie.year, Movie.id, MovieActor.role FROM Movie, MovieActor WHERE MovieActor.aid=$id AND Movie.id=MovieActor.mid";
                $movies = $db->query($movieQuery);
                if (!$movies) {
                    $errmsg = $db->error; 
                    print "Movie Query failed: $errmsg <br>"; 
                    exit(1); 
                }
                $moviesExist = $movies->num_rows;
            }
        ?>
        <title>
            <?php
                if (!$actorExists){
                    print "No Actor Found";
                }
                else {
                    print "Spotlight: " . $first . " " . $last;
                }
            ?>
        </title>
        <style>
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
            }
            table {
                width: 600px;
            }
            th, td {
                padding: 5px;
                text-align:left;
            }
            th {
                background-color: #000000;
                color: #FFFFFF;
            }
        </style>
    </head>
    <body>

        <h1>Actor Spotlight:
            <?php
                if (!$actorExists){
                    print "<em>No actor found by that ID.</em>";
                }
                else {
                    print $first . " " . $last;
                }
            ?>
        </h1>

        <FORM METHOD="POST" ACTION="search.php">
            <INPUT TYPE="text" NAME="name" VALUE="" SIZE="25" MAXLENGTH="20">    
            <INPUT TYPE="submit" VALUE="search">
        </FORM>

        <h2> Information: </h2>

        <table>
            <tr>
                <th>Sex</th>
                <th>Born</th>
                <th>Died</th>
            </tr>
            <tr>
                <td><?php print $sex; ?></td>
                <td><?php print $dob; ?></td>
                <td><?php print $dod; ?></td>
            </tr>
        </table>

        <h2> Appears In: </h2>

        <?php
            if (!$moviesExist){
                print "<em>No movies.</em>";
            }
            else {
                print "<table>";
                print "<tr>";
                print "<th>Year</th>";
                print "<th>Title</th>";
                print "<th>Role</th>";
                print "</tr>";
                while ($row=$movies->fetch_assoc()) {
                    $mid = $row['id']; 
                    $title = $row['title']; 
                    $year = $row['year'];
                    $role = $row['role']; 
                    print "<tr>";
                    print "<td>$year</td>";
                    print "<td><a href='./movie_info.php?id=$mid'>$title</a></td>";
                    print "<td>$role</td>";
                    print "</tr>";
                }
                print "</table>";
            }
        ?>
        
    </body>
</html>