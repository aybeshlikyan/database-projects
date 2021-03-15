# set up SparkContext for WordCount application
from pyspark import SparkContext
sc = SparkContext("local", "BookPairs")

def combinations(reviewList):
    return { (i,j) for i in reviewList for j in reviewList if j>i }

# the main map-reduce task
data = sc.textFile("/home/cs143/data/goodreads.user.books")
bookLists = data.map(lambda user: user.split(":"))
bookLists = bookLists.map(lambda user: (user[0], [int(x) for x in user[1].split(",")]))

pairs = bookLists.flatMap(lambda reviews: combinations(reviews[1]))
pair1s = pairs.map(lambda pair: (pair, 1))
pairCounts = pair1s.reduceByKey(lambda a, b: a+b)

freqBookPairs = pairCounts.filter(lambda pair: pair[1] > 20)

freqBookPairs.saveAsTextFile("output")
