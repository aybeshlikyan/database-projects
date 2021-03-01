SELECT
    Actor.first, Actor.last
FROM 
    Actor
WHERE 
    Actor.id IN (
        SELECT 
            MovieActor.aid
        FROM
            MovieActor
        WHERE
            MovieActor.mid=(
                SELECT
                    Movie.id
                FROM
                    Movie
                WHERE
                    Movie.title='Die Another Day'
                ))
;