SELECT
	source_client_id,
	source_name,
	source_link,
	COUNT(source_client_id) AS rank
FROM
	analytics_app_ne.posts
GROUP BY
	source_client_id
ORDER BY
	rank DESC