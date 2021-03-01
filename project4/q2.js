db.laureates.aggregate([
    { $unwind : "$nobelPrizes"},
    { $unwind : "$nobelPrizes.affiliations"},
    { $match : { "nobelPrizes.affiliations.name.en" : "CERN"}},
    { $group : {
        _id: {
            country: "$nobelPrizes.affiliations.country.en"
        }
    }},
    { $project: { _id: 0, "country" : "$_id.country"} }
]).pretty()