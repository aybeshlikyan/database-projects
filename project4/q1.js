db.laureates.aggregate([
    { $match : { "knownName.en" : "Marie Curie"}},
    { $project: { _id: 0, id : 1} }
]).pretty()