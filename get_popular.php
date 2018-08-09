<?php
/*
 *  get popular wikipedia articles
 */

$http = 'Wikipedia:Multiyear_ranking_of_most_viewed_pages';
$sql = 'INSERT INTO popular (article) VALUES (?)';

$article_str = get_quotes_string($http);
$end = strlen($article_str);
$start = strpos($article_str, '==Countries==');
$article_str = substr($article_str, $start, $end);
$links = get_links($article_str);

foreach($links as $thing)
{
    if(strpos($thing, ':') == 0)
    {
        $link = clean_popular_link($thing);

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($link));
    }
}
