#!/bin/sh
if ps -ef | grep -v grep | grep consumeGlobalStream.php ; then
	exit 0
else
	php5 -q /home/james/www/analytics-app/private/consumeGlobalStream.php < /dev/null &
	exit 0
fi
