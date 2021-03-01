<!DOCTYPE html>
<html>
    <head>
        <?php 
            $mid = $_POST["mid"];
            $name = $_POST["name"];
            $rating = intval($_POST["score"]);
            $comment = $_POST["review"];
            $time = date("Y-m-d") . " " . date("h:i:s");

            $db = new mysqli('localhost', 'cs143', '', 'cs143');
            if ($db->connect_errno > 0) { 
                die('Unable to connect to database [' . $db->connect_error . ']'); 
            }

            $addReview = "INSERT INTO Review VALUES ('$name', '$time', $mid, $rating, '$comment')";
            $check_addReview = $db->query($addReview);
            if (!$check_addReview) {
                $errmsg = $db->error; 
                print "Adding review failed: $errmsg <br>"; 
                exit(1); 
            }
        ?>
    </head>
    <body>
        <h1>Comment submitted successfully!</h1>
        <h3>Name: <?php print $name;?></h3>
        <h3>Time: <?php print $time;?></h3>
        <h3>Rating: <?php print $rating;?></h3>
        <h3>Review: <?php print $comment;?></h3>

        <br>
        <h3>Start a New Search</h3>
        <FORM METHOD="POST" ACTION="search.php">
            <INPUT TYPE="text" NAME="name" VALUE="" SIZE="25" MAXLENGTH="20">    
            <INPUT TYPE="submit" VALUE="search">
        </FORM>
    </body>
</html>