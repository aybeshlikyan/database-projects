db.laureates.aggregate([
    { $group : {
        _id: "$familyName.en",
        count: {$sum:1}
    }},
    { $match : {
        count : {'$gte' : 5},
        _id : { '$ne' : null}
    }},
    { $project: { 
        _id: 0, 
        "familyName" : "$_id",
    }}
]).pretty()