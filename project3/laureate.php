<?php 

    $id = $_GET["id"];
    $db = new mysqli('localhost', 'cs143', '', 'cs143');
    if ($db->connect_errno > 0) { 
        die('Unable to connect to database [' . $db->connect_error . ']'); 
    }

    $laureatePersonQuery = "SELECT * FROM Person WHERE id=$id";
    $laureateOrgQuery = "SELECT * FROM Organization WHERE id=$id";

    $laureate = $db->query($laureatePersonQuery);
    if (!$laureate) {
        $errmsg = $db->error; 
        print "Laureate Query Failed: $errmsg <br>"; 
        exit(1); 
    }

    $isPerson = !!$laureate->num_rows;
    
    if(!$isPerson){
        $laureate = $db->query($laureateOrgQuery);
        if (!$laureate) {
            $errmsg = $db->error; 
            print "Laureate Query Failed: $errmsg <br>"; 
            exit(1); 
        }
    }

    $laureateExists = $laureate->num_rows;

    if($laureateExists){
        $laureate=$laureate->fetch_assoc();
        $laureateBuilder = [];
        $laureateBuilder["id"] = $laureate["id"];
        if($isPerson){
            $laureateBuilder["givenName"] = [];
            $laureateBuilder["givenName"]["en"] = $laureate["givenName"];
            $laureateBuilder["familyName"] = [];
            $laureateBuilder["familyName"]["en"] = $laureate["familyName"];
            $laureateBuilder["gender"] = $laureate["gender"];
            $laureateBuilder["birth"] = [];
            $laureateBuilder["birth"]["date"] = $laureate["birthDate"];
            $laureateBuilder["birth"]["place"] = [];
            $laureateBuilder["birth"]["place"]["city"] = [];
            $laureateBuilder["birth"]["place"]["city"]["en"] = $laureate["city"];
            $laureateBuilder["birth"]["place"]["country"] = [];
            $laureateBuilder["birth"]["place"]["country"]["en"] = $laureate["country"];

            //print_r($laureateBuilder);
        }
        else{
            $laureateBuilder["orgName"] = [];
            $laureateBuilder["orgName"]["en"] = $laureate["orgName"];
            $laureateBuilder["founded"] = [];
            $laureateBuilder["founded"]["date"] = $laureate["foundedDate"];
            $laureateBuilder["founded"]["place"] = [];
            $laureateBuilder["founded"]["place"]["city"] = [];
            $laureateBuilder["founded"]["place"]["city"]["en"] = $laureate["foundedCity"];
            $laureateBuilder["founded"]["place"]["country"] = [];
            $laureateBuilder["founded"]["place"]["country"]["en"] = $laureate["foundedCountry"];

            //print_r($laureateBuilder);
        }

        $awardsQuery = "SELECT * FROM Award WHERE laureateID=$id";
        $awards = $db->query($awardsQuery);
        if (!$awards) {
            $errmsg = $db->error; 
            print "Award Query Failed: $errmsg <br>"; 
            exit(1);
        }

        $laureateBuilder["nobelPrizes"] = [];
        $awardsExist = $awards->num_rows;
        if ($awardsExist) {
            while ($award=$awards->fetch_assoc()){
                $awardID = $award["id"];
                $awardBuilder = [];
                $awardBuilder["awardYear"] = $award["year"];
                $awardBuilder["category"] = [];
                $awardBuilder["category"]["en"] = $award["category"];
                $awardBuilder["sortOrder"] = $award["sortOrder"];
                $awardBuilder["portion"] = $award["portion"];
                $awardBuilder["dateAwarded"] = $award["dateAwarded"];
                $awardBuilder["prizeStatus"] = $award["prizeStatus"];
                $awardBuilder["motivation"] = [];
                $awardBuilder["motivation"]["en"] = $award["motivation"];
                $awardBuilder["prizeAmount"] = $award["prizeAmount"];
                
                $affiliationsQuery = "SELECT * FROM Affiliation WHERE id IN
                    (SELECT affiliateID FROM Affiliated WHERE awardID=$awardID)";
                $affiliations = $db->query($affiliationsQuery);
                if (!$affiliations) {
                    $errmsg = $db->error; 
                    print "Affiliations Query Failed: $errmsg <br>"; 
                    exit(1);
                }

                $awardBuilder["affiliations"] = [];
                $affiliationsExist = $affiliations->num_rows;
                if($affiliationsExist) {
                    while ($affiliation=$affiliations->fetch_assoc()){
                        $affiliationBuilder = [];
                        $affiliationBuilder["name"] = [];
                        $affiliationBuilder["name"]["en"] = $affiliation["name"];
                        $affiliationBuilder["city"] = [];
                        $affiliationBuilder["city"]["en"] = $affiliation["city"];
                        $affiliationBuilder["country"] = [];
                        $affiliationBuilder["country"]["en"] = $affiliation["country"];

                        array_push($awardBuilder["affiliations"] , $affiliationBuilder);
                    }
                }

                array_push($laureateBuilder["nobelPrizes"] , $awardBuilder);
            }
        }

        $finalOutput = json_encode ($laureateBuilder);
        print $finalOutput;
    }
    else{
        print "No Laureate exists with id: $id";
    }

?>