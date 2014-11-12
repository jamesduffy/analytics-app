SELECT SQL_NO_CACHE
	p1.id, p1.user_id, p1.created_at, p1.text, p1.source_name, p1.source_link, p1.source_client_id,
	p1.reply_to, p1.canonical_url, p1.thread_id, p1.num_replies, p1.num_stars, p1.num_reposts, p1.detected_language,
	(COUNT(DISTINCT replies.user_id) + (p1.num_stars*2) + (p1.num_reposts*2.5)) AS score
FROM posts p1
JOIN posts replies ON p1.id = replies.thread_id
WHERE
	p1.created_at > '2014-02-04 00:00:00' AND
	p1.reply_to IS NULL AND
	p1.detected_language = 'en' AND
	p1.user_id NOT IN ( SELECT user_id FROM uninteresting_users ) AND
	p1.source_client_id NOT IN ( SELECT client_id FROM uninteresting_clients ) AND
	p1.id NOT IN ( SELECT post_id FROM publishing_queue )
HAVING
	score > 11
GROUP BY replies.thread_id
ORDER BY (COUNT(DISTINCT replies.user_id) + (p1.num_stars*1.5) + (p1.num_reposts*2)) DESC
LIMIT 20