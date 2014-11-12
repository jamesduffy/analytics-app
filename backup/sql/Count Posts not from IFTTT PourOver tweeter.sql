SELECT count(id)
FROM analytics_app_ne.posts
WHERE
	source_client_id NOT IN
	('GzJVygE6vpLbabgBtEKzEKH8ASzS6mF3',
	'RQW8mehVHU3wLdWLWd8YH3tuYzAq29W2',
	'8dWzbxxwZeTTAH3v7Yy9TbJ82W6mexqH',
	'AnL26PHsjb4MbzDkwJcJD9qUMsem56dP',
	'6kmFxf2JrEqmFRQ4WncLfN8WWx7FnUS8')