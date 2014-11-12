SELECT
	*
FROM analytics_app_ne.posts
WHERE
	created_at BETWEEN '2014-01-08 16:00:00' AND '2014-01-08 18:00:59' AND
	reply_to IS NULL AND
	(num_replies + (num_stars*1.5) + (num_reposts*2)) >
		(
			SELECT (AVG(num_replies) + (AVG(num_stars)*1.5) + (AVG(num_reposts)*2)) * 100
			FROM analytics_app_ne.posts
			WHERE created_at BETWEEN '2014-01-08 00:00:00' AND '2014-01-08 23:59:59'
		)
LIMIT 25