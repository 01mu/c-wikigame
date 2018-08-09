<?php
/*
 *  get specific article links
 */

include_once 'functions.php';

if(!isset($_GET['limit']) || !isset($_GET['article']) || !isset($_GET['goal']))
{
    show_error('bad');
}

$limit = $_GET['limit'];
$article = $_GET['article'];
$goal = $_GET['goal'];

if($limit <= 0 || $limit > 50)
{
    show_error('bad');
}

$article = get_quotes_string($article);

if(check_redirect($article))
{
    $article = get_redirect_link($article);
    $article = get_quotes_string($article);
}

if($article === 'err_page_missing')
{
    show_error('err_page_missing');
}

$links = get_links($article);
$final = get_final_links($links, $limit);

if(count($final) == 0)
{
    show_error('err_page_missing');
}

if(!in_array($goal, $links))
{
    echo json_encode($final, JSON_UNESCAPED_UNICODE);
}
else
{
    echo json_encode(['found_article']);
}
