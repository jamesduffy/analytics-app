SELECT
	follows_user_id,
	SUM( 
		CASE WHEN is_deleted IS NULL 
		THEN 1 
		ELSE 0 
		END
	) AS follows
FROM analytics_app_ne.follows 
WHERE
	created_at BETWEEN '2014-01-14 00:00:00' AND '2014-01-14 23:59:59' AND
	user_id NOT IN (
		SELECT user_id FROM analytics_app_ne.uninteresting_users
	)
GROUP BY 1 
ORDER BY follows DESC 
LIMIT 25