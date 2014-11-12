SELECT
  SUM(popularity) AS rank,
  user_id
FROM
  analytics_app_ne.leaderboard_popular_posts_day
JOIN
  posts
  ON leaderboard_popular_posts_day.post_id = posts.id
GROUP BY
  user_id
ORDER BY
  SUM(popularity) DESC
LIMIT 100