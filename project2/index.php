<!DOCTYPE html>
<html>
    <body>

        <h1>Project 2: Internet Movie Database</h1>

        <FORM METHOD="POST" ACTION="search.php">
            <INPUT TYPE="text" NAME="name" VALUE="" SIZE="25" MAXLENGTH="20">    
            <INPUT TYPE="submit" VALUE="search">
        </FORM>


    </body>
</html>

<!-- <?php 
    $db = new mysqli('localhost', 'cs143', '', 'cs143');
    if ($db->connect_errno > 0) { 
        die('Unable to connect to database [' . $db->connect_error . ']'); 
    }
?>

<?php
    ## Writing select query
    $query = "SELECT * FROM Student";
    $rs = $db->query($query);

    ## Getting results of query
    while ($row = $rs->fetch_assoc()) { 
        $sid = $row['sid']; 
        $name = $row['name']; 
        $email = $row['email']; 
        print "$sid, $name, $email<br>"; 
    }
    print 'Total results: ' . $rs->num_rows;

    ## Freeing result variable
    $rs->free();

    ## Updating db and chekcing rows
    $query = "UPDATE Student SET email = CONCAT(email, '.edu')";
    $db->query($query);
    print 'Total rows updated: ' . $db->affected_rows;

    ## Closing db connection
    $db->close();

    ## Error Handling
    $query = "SELECT * FROM Student"; 
    $rs = $db->query($query);
    if (!$rs) {
        $errmsg = $db->error; 
        print "Query failed: $errmsg <br>"; 
        exit(1); 
    }

    ## Escaping user input
    $query = "SELECT sid, email FROM Student WHERE name = '%s'";
    $sanitized_name = $db->real_escape_string($name);
    $query_to_issue = sprintf($query, $sanitized_name); 
    $rs = $db->query($query_to_issue);

    ## Prepared statements
    $statment = $db->prepare("SELECT sid, email FROM Student WHERE name=?");

    $name = 'James';
    $statement->bind_param('s', $name);
    ### ORRRR with more params you could do: 
    $statement->bind_param('sdi', $name, $GPA, $age); ## sdi means string, double, integer

    $statement->execute();

    $statement->bind_result($returned_sid, $returned_email);

    while ($statement->fetch()) { 
        echo $returned_sid . ' ' . $returned_email . '<br>';
    }

    $statement->free_result();
?> -->