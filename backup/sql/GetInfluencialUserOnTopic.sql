SELECT
	user_id,
	((SUM(num_stars) * 1.5) + (SUM(num_reposts) * 2) + SUM(num_replies)) AS rank
FROM
	analytics_app_ne.posts
WHERE
	text LIKE '%beer%' AND 
	created_at BETWEEN '2013-12-01 00:00:00' AND '2013-12-01 11:59:59'
GROUP BY user_id
ORDER BY rank DESC
LIMIT 25