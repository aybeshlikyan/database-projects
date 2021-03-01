<!DOCTYPE html>
<html>
    <head>
        <?php
            $mid = $_GET["mid"]; 
            $db = new mysqli('localhost', 'cs143', '', 'cs143');
            if ($db->connect_errno > 0) { 
                die('Unable to connect to database [' . $db->connect_error . ']'); 
            }
            
            $movieQuery = "SELECT title FROM Movie WHERE id=$mid";
            $movie = $db->query($movieQuery);
            if (!$movie) {
                $errmsg = $db->error; 
                print "Movie Query failed: $errmsg <br>"; 
                exit(1); 
            }

            $title = ""; 
            $movieExists = $movie->num_rows;
            if ($movieExists){
                $movie = $movie->fetch_assoc();
                $title = $movie['title'];
            }
        ?>
        <title>

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

        <h1>Write a Comment</h1>
        <h2> Movie: "<?php
                if (!$movieExists){
                    print "<em>Not a valid movie.</em>";
                }
                else {
                    print $title;
                }
            ?>"
        </h2>

        <FORM METHOD="POST" ACTION="process_review.php">
            <INPUT TYPE="hidden" NAME="mid" VALUE=<?php print $mid;?>>
            <h3>Your Name:</h3>
            <INPUT TYPE="text" NAME="name" VALUE="" SIZE="25" MAXLENGTH="20">
            <h3>Your Rating:</h3>  
            <SELECT NAME="score">
                <OPTION value='1' SELECTED>1</OPTION>
                <OPTION value='2'>2</OPTION>
                <OPTION value='3'>3</OPTION>
                <OPTION value='4'>4</OPTION>
                <OPTION value='5'>5</OPTION>
            </SELECT>
            <h3>Your Review:</h3>
            <TEXTAREA NAME="review" ROWS="5" COLS="30"></TEXTAREA><br>
            <INPUT TYPE="submit" VALUE="Submit Review">
        </FORM>
        <br>

        <h3>Start a New Search</h3>
        <FORM METHOD="POST" ACTION="search.php">
            <INPUT TYPE="text" NAME="name" VALUE="" SIZE="25" MAXLENGTH="20">    
            <INPUT TYPE="submit" VALUE="search">
        </FORM>
    </body>
</html>