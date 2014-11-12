SELECT
  COUNT(id) AS total_posts
FROM
  analytics_app_ne.posts
WHERE
  created_at BETWEEN '2013-12-01 00:00:00' AND '2013-12-01 11:59:59'