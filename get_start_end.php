<?php
/*
 *  get random start link and goal link
 */

include_once 'functions.php';

if(!isset($_GET['limit']) || !isset($_GET['type']))
{
    show_error('bad');
}

$limit = $_GET['limit'];
$type = $_GET['type'];

if($limit <= 0 || $limit > 50)
{
    show_error('bad');
}

switch($type)
{
    case 'rand_pop':
        $article_goal = get_random_popular($pdo);
        $article_goal = str_replace(" ", "_", $article_goal);
        break;
    case 'rand':
        $article_goal = get_redirect();
        $article_goal = str_replace("_", " ", $article_goal);
        break;
    case 'specific':
        $article_goal = '';
        $article_goal_link = '';
        break;
    default:
        show_error('bad');
        break;
}

$start = get_redirect();
$article_start = str_replace("_", " ", $start);

$link_start = 'https://en.wikipedia.org/wiki/' . $start;
$link_goal = 'https://en.wikipedia.org/wiki/' . $article_goal;

$article_str = get_quotes_string($start);
$links = get_links($article_str);
$final = get_final_links($links, $limit);

$articles = array();

$articles[] = $article_start;
$articles[] = $article_goal;

$a_links = array();

$a_links[] = $link_start;
$a_links[] = $link_goal;

$json[] = $articles;
$json[] = $a_links;

if(!in_array($start, $links))
{
    $json[] = $final;
}
else
{
    $json[] = ['found_article'];
}

echo json_encode($json, JSON_UNESCAPED_UNICODE);
