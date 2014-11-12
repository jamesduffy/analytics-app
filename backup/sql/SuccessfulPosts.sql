SELECT
	*,
	( (SUM(num_replies) + (SUM(num_stars)*1.5) + (SUM(num_reposts)*2) )/ COUNT(id)) AS rank
FROM
	analytics_app_ne.posts
GROUP BY
	user_id
ORDER BY
	rank DESC