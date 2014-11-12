<?php

/**
 * UTF8_SUBSTR_REPLACE
 *
 * For some odd reason PHP still does not play nice with UTF-8.
 */
function utf8_substr_replace($original, $replacement, $position, $length)
{
	$startString = mb_substr($original, 0, $position, "UTF-8");
	$endString = mb_substr($original, $position + $length, mb_strlen($original), "UTF-8");

	$out = $startString . $replacement . $endString;

	return $out;
}
