<!DOCTYPE html>
<html>
    <head>
        <?php
            $id = $_GET["id"]; 
            $db = new mysqli('localhost', 'cs143', '', 'cs143');
            if ($db->connect_errno > 0) { 
                die('Unable to connect to database [' . $db->connect_error . ']'); 
            }

            $movieQuery = "SELECT title,year,rating,company FROM Movie WHERE id=$id";
            $movie = $db->query($movieQuery);
            if (!$movie) {
                $errmsg = $db->error; 
                print "movie Query failed: $errmsg <br>"; 
                exit(1); 
            }

            $title = ""; 
            $year = "";
            $mpaa = ""; 
            $company = "";
            $director = "";
            $genre = "";
            $avgScore = 0;

            $movieExists = $movie->num_rows;
            $actorsExist = 0;
            $reviewsExist = 0;

            if ($movieExists){
                $movie = $movie->fetch_assoc();
                $title = $movie['title']; 
                $year = $movie['year'];
                $mpaa = $movie['rating']; 
                $company = $movie['company'];
                
                $directorQuery = "SELECT last,first FROM Director WHERE id=
                                    (SELECT did FROM MovieDirector WHERE mid=$id)";
                $directorRes = $db->query($directorQuery);
                if (!$directorRes) {
                    $errmsg = $db->error; 
                    print "Director Query failed: $errmsg <br>"; 
                    exit(1); 
                }
                $directorExists = $directorRes->num_rows;
                if($directorExists){
                    $directorRes=$directorRes->fetch_assoc();
                    $director = $directorRes['first'] . " " . $directorRes['last'];
                }

                $genreQuery = "SELECT genre FROM MovieGenre WHERE mid=$id";
                $genreRes = $db->query($genreQuery);
                if (!$genreRes) {
                    $errmsg = $db->error; 
                    print "Genre Query failed: $errmsg <br>"; 
                    exit(1); 
                }
                $genreExists = $genreRes->num_rows;
                if($genreExists){
                    $genreRes=$genreRes->fetch_assoc();
                    $genre = $genreRes['genre'];
                }

                $actorQuery = "SELECT Actor.last, Actor.first, Actor.id, MovieActor.role FROM Actor, MovieActor WHERE MovieActor.mid=$id AND Actor.id=MovieActor.aid";
                $actors = $db->query($actorQuery);
                if (!$actors) {
                    $errmsg = $db->error; 
                    print "Actor Query failed: $errmsg <br>"; 
                    exit(1); 
                }
                $actorsExist = $actors->num_rows;

                $avgUserScoreQuery = "SELECT AVG(rating) FROM Review GROUP BY mid HAVING mid=$id";
                $avgUserScore = $db->query($avgUserScoreQuery);
                if (!$avgUserScore) {
                    $errmsg = $db->error; 
                    print "Average User Score Query failed: $errmsg <br>"; 
                    exit(1); 
                }

                $reviewsExist = $avgUserScore->num_rows;
                if ($reviewsExist) {
                    $avgUserScore = $avgUserScore->fetch_assoc();
                    $avgScore = $avgUserScore['AVG(rating)'];
                    $reviewQuery = "SELECT name,time,rating,comment FROM Review WHERE mid=$id";
                    $reviews = $db->query($reviewQuery);
                    if (!$reviewQuery) {
                        $errmsg = $db->error; 
                        print "Review Query failed: $errmsg <br>"; 
                        exit(1); 
                    }
                }
                
                
            }
        ?>
        <title>
            <?php
                if (!$movieExists){
                    print "No Movie Found";
                }
                else {
                    print "Spotlight: " . $title;
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

        <h1>Movie Spotlight:
            <?php
                if (!$movieExists){
                    print "<em>No movie found by that ID.</em>";
                }
                else {
                    print $title;
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
                <th>Year</th>
                <th>Company</th>
                <th>MPAA Rating</th>
                <th>Director</th>
                <th>Genre</th>
            </tr>
            <tr>
                <td><?php print $year; ?></td>
                <td><?php print $company; ?></td>
                <td><?php print $mpaa; ?></td>
                <td><?php print $director; ?></td>
                <td><?php print $genre; ?></td>
            </tr>
        </table>

        <h2> Cast: </h2>

        <?php
            if (!$actorsExist){
                print "<em>No actors.</em>";
            }
            else {
                print "<table>";
                print "<tr>";
                print "<th>Name</th>";
                print "<th>Role</th>";
                print "</tr>";
                while ($row=$actors->fetch_assoc()) {
                    $aid = $row['id']; 
                    $last = $row['last']; 
                    $first = $row['first'];
                    $role = $row['role']; 
                    print "<tr>";
                    print "<td><a href='./actor_info.php?id=$aid'>" . $first . " " . $last . "</a></td>";
                    print "<td>$role</td>";
                    print "</tr>";
                }
                print "</table>";
            }
        ?>
        
        <h2> Reviews: </h2>

        <?php
            if (!$reviewsExist){
                print "<em>No reviews yet. <a href='./submit_review.php?mid=$id'>Be the first!</a></em>";
            }
            else {
                print "<h3>Average User Rating: $avgScore</h3><br>";
                while ($row=$reviews->fetch_assoc()) {
                    $name = $row['name']; 
                    $time = $row['time']; 
                    $rating = $row['rating'];
                    $comment = $row['comment']; 
                    print "<p>Name: $name</p>";
                    print "<p>Time: $time</p>";
                    print "<p>Rating: $rating</p>";
                    print "<p>Comment: $comment</p><br>";
                }
                print "<h3><a href='./submit_review.php?mid=$id'>Submit Your Own Review!</a></h3>";
            }
        ?>
    </body>
</html>