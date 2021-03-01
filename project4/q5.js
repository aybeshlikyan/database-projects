db.laureates.aggregate([
    { $unwind : "$nobelPrizes"},
    { $match: {orgName : { '$ne' : null}}},
    { $group : {
        _id : {
            year: "$nobelPrizes.awardYear"
        }
    }},
    { $count : "years"}
]).pretty()