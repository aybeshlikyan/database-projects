db.laureates.aggregate([
    { $unwind : "$nobelPrizes"},
    { $unwind : "$nobelPrizes.affiliations"},
    { $match : { "nobelPrizes.affiliations.name.en" : "University of California"}},
    { $group : {
        _id : {
            location: "$nobelPrizes.affiliations.city.en"
        },
        count : {$sum:1}
    }},
    { $count : "locations"}
]).pretty()