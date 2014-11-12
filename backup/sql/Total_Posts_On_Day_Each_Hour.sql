SELECT
  HOUR(created_at),
  count(id)
FROM
  analytics_app_ne.posts
WHERE
  created_at BETWEEN '2013-12-01 00:00:00' AND '2013-12-01 11:59:59'
GROUP BY
  HOUR(created_at)