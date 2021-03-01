<?php 

// read JSON data
$file_content = file_get_contents("/home/cs143/data/nobel-laureates.json");
$data = json_decode($file_content, true);

$personFile = "person.del";
$orgFile = "org.del";
$awardFile = "award.del";
$affiliationFile = "affiliation.del";
$affiliatedFile = "affiliated.del";

$prizeID = 0;
$affiliateID = 0;
$affiliateArray = [];

foreach ($data["laureates"] as $laureate) {
    $laureateID = $laureate["id"];
    if (array_key_exists("givenName", $laureate)){
        // get the id, givenName, familyName, gender, birthdate, birth city, and birth country of each laureate
        $givenName = $laureate["givenName"]["en"];
        $familyName = array_key_exists("familyName", $laureate) ? $laureate["familyName"]["en"] : "\N";
        $gender = array_key_exists("gender", $laureate) ? $laureate["gender"] : "\N";
        $birthDate = array_key_exists("birth", $laureate) ? $laureate["birth"]["date"] : "\N";
        $birthCity = array_key_exists("birth", $laureate) ? (array_key_exists("place", $laureate["birth"]) ? (array_key_exists("city", $laureate["birth"]["place"]) ? $laureate["birth"]["place"]["city"]["en"] : "\N") : "\N") : "\N";
        $birthCountry = array_key_exists("birth", $laureate) ? (array_key_exists("place", $laureate["birth"]) ? $laureate["birth"]["place"]["country"]["en"] : "\N") : "\N";;
    
        // print the extracted information in the correct format
        $person = $laureateID . "\t" . $givenName . "\t" . $familyName . "\t" . $gender . "\t" . $birthDate . "\t" . $birthCity . "\t" . $birthCountry . PHP_EOL ;
        file_put_contents($personFile, $person, FILE_APPEND);
    }
    elseif (array_key_exists("orgName", $laureate)) {
        // get the id, orgName, founded date, city, and country of each org
        $orgName = $laureate["orgName"]["en"];
        $foundedDate = array_key_exists("founded", $laureate) ? $laureate["founded"]["date"] : "\N";
        $foundedCity = array_key_exists("founded", $laureate) ? (array_key_exists("city", $laureate["founded"]["place"]) ? $laureate["founded"]["place"]["city"]["en"] : "\N") : "\N";
        $foundedCountry = array_key_exists("founded", $laureate) ? (array_key_exists("country", $laureate["founded"]["place"]) ? $laureate["founded"]["place"]["country"]["en"] : "\N") : "\N";
    
        // print the extracted information
        $org = $laureateID . "\t" . $orgName . "\t" . $foundedDate . "\t" . $foundedCity . "\t" . $foundedCountry . PHP_EOL ;
        file_put_contents($orgFile, $org, FILE_APPEND);
    }
    
    foreach ($laureate["nobelPrizes"] as $prize) {
        $year = $prize["awardYear"];
        $category = $prize["category"]["en"];
        $sortOrder = $prize["sortOrder"];
        $portion = $prize["portion"];
        $dateAwarded = array_key_exists("dateAwarded", $prize) ? $prize["dateAwarded"] : "\N";
        $prizeStatus = $prize["prizeStatus"];
        $motivation = $prize["motivation"]["en"];
        $prizeAmount = $prize["prizeAmount"];

        // print the extracted information in the correct format
        $award = $prizeID . "\t" . $laureateID . "\t" . $year . "\t" . $category . "\t" . $sortOrder . "\t" . $portion . "\t" . $dateAwarded . "\t" . $prizeStatus . "\t" . $motivation . "\t" . $prizeAmount . PHP_EOL;
        file_put_contents($awardFile, $award, FILE_APPEND);

        if (array_key_exists("affiliations", $prize)) {
            foreach ($prize["affiliations"] as $affiliate) {
                $name = $affiliate["name"]["en"];
                $city = array_key_exists("city", $affiliate) ? $affiliate["city"]["en"] : "\N";
                $country = array_key_exists("country", $affiliate) ? $affiliate["country"]["en"] : "\N";

                $incompleteAffiliate = $name . "\t" . $city . "\t" . $country;
                if (!array_key_exists($incompleteAffiliate , $affiliateArray)) {
                    // print the extracted information in the correct format
                    $affiliateArray[$incompleteAffiliate] = $affiliateID;
                    $affiliation = $affiliateID . "\t" . $incompleteAffiliate . PHP_EOL ;
                    file_put_contents($affiliationFile, $affiliation, FILE_APPEND);
                    $affiliateArray[$incompleteAffiliate] = $affiliateID;
                    $affiliateID++;
                }
                $matchedAffiliateID = $affiliateArray[$incompleteAffiliate];
                // echo $matchedAffiliateID . PHP_EOL;
                $relatedAffiliate = $matchedAffiliateID . "\t" . $prizeID . PHP_EOL ;
                file_put_contents($affiliatedFile, $relatedAffiliate, FILE_APPEND);
            }
        }
        $prizeID++;
    }

}

?>