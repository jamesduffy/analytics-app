SELECT the_hour, avg(total_posts) AS avg_posts FROM (
SELECT
	HOUR(created_at) AS the_hour,
	count(id) AS total_posts
FROM analytics_app_ne.posts
WHERE
	source_client_id NOT IN
		('GzJVygE6vpLbabgBtEKzEKH8ASzS6mF3','RQW8mehVHU3wLdWLWd8YH3tuYzAq29W2',
			'AnL26PHsjb4MbzDkwJcJD9qUMsem56dP','8dWzbxxwZeTTAH3v7Yy9TbJ82W6mexqH', '6kmFxf2JrEqmFRQ4WncLfN8WWx7FnUS8' ) AND
	created_at BETWEEN '2013-12-01 00:00:00' AND '2014-01-13 11:59:59'
GROUP BY
	HOUR(created_at), DATE(created_at)
) s GROUP BY the_hour ORDER BY avg_posts DESC