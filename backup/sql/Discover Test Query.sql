SELECT 
	*,
	'repost' AS event_type
FROM analytics_app_ne.reposts
WHERE
	user_id IN ('32231')

UNION ALL

SELECT
	*,
	'star' AS event_type
FROM analytics_app_ne.stars
WHERE
	user_id IN ('32231')

ORDER BY created_at DESC
LIMIT 25