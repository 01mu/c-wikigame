<?php
$agent = 'c-wikigame';
$context = stream_context_create(array("http" => array("header" => $agent)));

function get_links($article_str)
{
    $links = array();

    $link_start_idx;
    $link_end_idx;
    $link_size;

    $link_set = false;

    $size = strlen($article_str) - 1;
    $index = 0;

    while($index != $size)
    {
        $fidx = $article_str[$index];
        $sidx = $article_str[$index + 1];

        if($fidx == '[' && $sidx == '[')
        {
            $link_start_idx = $index + 2;
            $link_set = true;
        }

        if($fidx == ']' && $sidx == ']' && $link_set == true)
        {
            $link_end_idx = $index;
            $link_size = $link_end_idx - $link_start_idx;
            $found_link = substr($article_str, $link_start_idx, $link_size);

            if(!bad_link_check($found_link) && !in_array($found_link, $links))
            {
                $links[] = $found_link;
            }

            $link_set = false;
        }

        $index++;
    }

    return $links;
}

function bad_link_check($found_link)
{
    $bad = [':', 'http', '\\', '<', '#'];
    $is_bad = false;

    foreach($bad as $check)
    {
        if(strpos($found_link, $check) !== false)
        {
            $is_bad = true;
            break;
        }
    }

    return $is_bad;
}

function fix_link($link)
{
    if(strpos($link, "|") !== false)
    {
        $line = strpos($link, "|");
        $link = substr($link, 0, $line);
    }

    return $link;
}

function fix_h($link)
{
    if(strpos($link, "#") !== false)
    {
        $line = strpos($link, "#");
        $link = substr($link, 0, $line);
    }

    return $link;
}

function get_quotes_string($redirect)
{
    global $context;
    $quotes;

    $redirect = str_replace(" ", "_", $redirect);
    $redirect = fix_h($redirect);

    $url = 'https://en.wikipedia.org/w/api.php?action=query' .
        '&titles=' . $redirect . '&prop=revisions&rvprop=content' .
        '&format=json&formatversion=2';

    $data = file_get_contents($url, false, $context);
    $wiki = json_decode($data, true);

    if(isset($wiki['query']['pages'][0]['missing']))
    {
        $quotes = 'err_page_missing';
    }
    else
    {
        $quotes = $wiki['query']['pages'][0]['revisions'][0]['content'];
    }

    return $quotes;
}

function get_redirect_url($url)
{
    stream_context_set_default(array(
        'http' => array(
            'method' => 'HEAD'
        )
    ));

    $headers = get_headers($url, 1);

    if($headers !== false && isset($headers['Location']))
    {
        return $headers['Location'];
    }

    return false;
}

function get_redirect()
{
    $wiki_rand = 'https://en.wikipedia.org/wiki/Special:Random';
    $replace = 'https://en.wikipedia.org/wiki/';

    $redirect = str_replace($replace, '', get_redirect_url($wiki_rand));

    return $redirect;
}

function check_redirect($article)
{
    $redirect = false;

    if(strpos($article, "#REDIRECT") !== FALSE)
    {
        $redirect = true;
    }

    return $redirect;
}

function get_redirect_link($article)
{
    $ret;

    if(count(get_links($article)) == 0)
    {
        $ret = 'no_links';
    }
    else
    {
        $ret = get_links($article)[0];
    }

    return $ret;
}

function get_final_links($links, $limit)
{
    $final = array();
    $rands = array();
    $link_count = count($links);

    if($link_count > $limit)
    {
        for($i = 0; $i < $limit; $i++)
        {
            $rand_val = rand(0, $link_count - 1);

            if(!in_array($rand_val, $rands))
            {
                $rands[] = $rand_val;
            }
        }
    }
    else
    {
        for($i = 0; $i < count($links); $i++)
        {
            $rands[] = $i;
        }
    }

    for($i = 0; $i < count($rands); $i++)
    {
        $r = $rands[$i];
        $link = $links[$r];
        $link = fix_link($link);
        $link = trim($link);

        $final[] = $link;
    }

    return $final;
}

function clean_popular_link($link)
{
    if($link[0] == ':')
    {
        $link = str_replace(':', '', $link);
    }

    $link = fix_link($link);
    $link = trim($link);

    return $link;
}

function get_random_popular($pdo)
{
    $article;

    $sql = 'SELECT article FROM popular ORDER BY RAND() LIMIT 1, 1';

    $stmt = $pdo->query($sql);

    while($row = $stmt->fetch())
    {
        $article = $row['article'];
    }

    return $article;
}

function show_error($notice)
{
    $bad = [$notice];
    echo json_encode($bad);
    die();
}
