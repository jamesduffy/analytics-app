SELECT
	DATE(created_at) AS day,
	COUNT(id) AS total_posts
FROM
	analytics_app_ne.posts
GROUP BY
	DATE(created_at)