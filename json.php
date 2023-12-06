<?php
require_once 'vendor/autoload.php';

// Read feeds from ini file
$feedsIni = parse_ini_file('feeds.ini', true);

// Loop through each feed and fetch data
$feedData = [];
foreach ($feedsIni as $title => $feedInfo) {
    $rss = simplexml_load_file($feedInfo['uri']);
    if ($rss) {
        $feedData[] = [
            'title' => $title,
            'items' => iterator_to_array($rss->channel->item),
        ];
    } else {
        $feedData[] = [
            'title' => $title,
            'error' => true,
        ];
    }
}

// Use Twig to render the JSON template
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader);

// Output JSON to a separate file
$jsonOutput = $twig->render('json_output.twig', ['data' => ['feeds' => $feedData]]);
print $jsonOutput;
