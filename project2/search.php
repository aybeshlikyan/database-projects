<!DOCTYPE html>
<html>     
    <head>    
        <title>Search Results</title>
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
        <?php
            $db = new mysqli('localhost', 'cs143', '', 'cs143');
            if ($db->connect_errno > 0) { 
                die('Unable to connect to database [' . $db->connect_error . ']'); 
            }
            $name = $_POST["name"];
            $searchQuery = explode(" ", $name); 

            // print "EXPLODED<br>";

            $sanitized_searchQuery = $searchQuery;
            // print $sanitized_searchQuery . "<br>";
            $index = 0;
            foreach($sanitized_searchQuery as $word){
                // print "HERE'S WORD: " . $word . "<br>";
                // print "HERE'S SANITIZED WORD: " . $db->real_escape_string($word) . "<br>";
                $sanitized_searchQuery[$index] = $db->real_escape_string($word);
                $index++;
            }
            $numWords = count($sanitized_searchQuery);

            // print "SANITIZED, counted " . $numWords . "<br>";
            
            $actorQuery = "SELECT last,first,dob,id
                            FROM Actor
                            WHERE %s";
            $actorQuery_builder = "";
            for ($i = 0; $i < $numWords; $i++){
                $word = $sanitized_searchQuery[$i];
                $actorQuery_builder = $actorQuery_builder . sprintf("(last LIKE '%%%s%%' OR first LIKE '%%%s%%')", $word, $word);
                if($i != $numWords-1){
                    $actorQuery_builder = $actorQuery_builder . " AND ";
                }   
            } 
            $actorQuery_final = sprintf($actorQuery, $actorQuery_builder);

            // print "ACTOR QUERY: " . $actorQuery_final . "<br>";

            $movieQuery = "SELECT title,year,id
                            FROM Movie
                            WHERE %s";
            $movieQuery_builder = "";
            for ($i = 0; $i < $numWords; $i++){
                $word = $sanitized_searchQuery[$i];
                $movieQuery_builder = $movieQuery_builder . sprintf("title LIKE '%%%s%%'", $word);
                if($i != $numWords-1){
                    $movieQuery_builder = $movieQuery_builder . " AND ";
                }   
            } 
            $movieQuery_final = sprintf($movieQuery, $movieQuery_builder);

            // print "MOVIE QUERY: " . $movieQuery_final . "<br>";

            $actors = $db->query($actorQuery_final);
            if (!$actors) {
                $errmsg = $db->error; 
                print "Actor Query failed: $errmsg <br>"; 
                exit(1); 
            }

            $movies = $db->query($movieQuery_final);
            if (!$movies) {
                $errmsg = $db->error; 
                print "Movie Query failed: $errmsg <br>"; 
                exit(1); 
            }
        ?>
    </head>
    <body>  
        <h1>Search Results for: "<?php print $name;?>" </h1>

        <FORM METHOD="POST" ACTION="search.php">
            <INPUT TYPE="text" NAME="name" VALUE="" SIZE="25" MAXLENGTH="20">    
            <INPUT TYPE="submit" VALUE="search">
        </FORM>

        <h2>Matching Actors:</h2>
        <?php
            if ($actors->num_rows == 0){
                print "<em>No matching actors.</em>";
            }
            else {
                print "<table>";
                print "<tr>";
                print "<th>Name</th>";
                print "<th>Date of Birth</th>";
                print "</tr>";
                while ($row=$actors->fetch_assoc()) {
                    $id = $row['id']; 
                    $last = $row['last']; 
                    $first = $row['first']; 
                    $dob = $row['dob'];
                    print "<tr>";
                    print "<td><a href='./actor_info.php?id=$id'>$first $last</a></td>";
                    print "<td>$dob</td>";
                    print "</tr>";
                }
                print "</table>";
            }
        ?>

        <h2>Matching Movies:</h2>
        <?php
            if ($movies->num_rows == 0){
                print "<em>No matching movies.</em>";
            }
            else {
                print "<table>";
                print "<tr>";
                print "<th>Title</th>";
                print "<th>Year</th>";
                print "</tr>";
                while ($row=$movies->fetch_assoc()) {
                    $id = $row['id']; 
                    $title = $row['title']; 
                    $year = $row['year']; 
                    print "<tr>";
                    print "<td><a href='./movie_info.php?id=$id'>$title</a></td>";
                    print "<td>$year</td>";
                    print "</tr>";
                }
                print "</table>";
            }
        ?>
    </body>   
</html>