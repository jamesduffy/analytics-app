SELECT
	user_id,
    (AVG(num_replies) + (AVG(num_stars) * 1.5) + (AVG(num_reposts) * 2)) AS popularity,
	AVG(num_stars),
	AVG(num_replies),
	AVG(num_reposts)
FROM analytics_app_ne.posts
WHERE 
	created_at BETWEEN '2013-12-01 00:00:00' AND '2014-01-01 23:59:59'
GROUP BY user_id
ORDER BY popularity DESC
LIMIT 25