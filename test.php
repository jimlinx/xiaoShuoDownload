<?php
$episode = "abc345def5";
$regex = "!(\d+)!";
preg_match($regex, $episode, $match);
echo json_encode($match) . "\n";