<?php
require_once 'vendor/autoload.php';

use SimplePie\SimplePie;

$feedsIni = parse_ini_file('feeds.ini', true);
$feedData = [];

$gridColumns = 3;
if (isset($_GET['columns'])) {
  $gridColumns = $_GET['columns'];
}

$limit = 20;
if (isset($_GET['limit'])) {
  $limit = $_GET['limit'];
}

foreach ($feedsIni as $title => $feedInfo) {
  $rss = new SimplePie();
  $rss->set_feed_url($feedInfo['uri']);
  $rss->init();
  $rss->handle_content_type();

  $items = [];
  foreach ($rss->get_items() as $item) {
      $items[] = [
          'title' => $item->get_title(),
          'link' => $item->get_link(),
          'description' => $item->get_description(),
          'date_time' => $item->get_date('m/d/Y @ h:i a')
      ];
  }

  // Add the feed data to the array
  $feedData[$title] = [
      'title' => $title,
      'items' => array_slice($items, 0, $limit)
  ];
}

// Render the feed data in JSON if the HTTP Get parameter is set.
if (isset($_GET['json'])) {
	$json = [];
	foreach ($feedData as $feed) {
		// If for some reason the feed has no items, skip it.
		if (count($feed['items']) < 1) {
			continue;
		}
		$json[$feed['title']] = [];
		foreach ($feed['items'] as $entry) {
                        $json[$feed['title']][$entry['title']] = [];
                        $json[$feed['title']][$entry['title']]['date_time'] = $entry['date'];
                        $json[$feed['title']][$entry['title']]['description'] = $entry['description'];
                        $json[$feed['title']][$entry['title']]['original_uri'] = $entry['link'];
                        $json[$feed['title']][$entry['title']]['proxy_uri'] = 'https://12ft.io/' . $entry['link'];
                        $json[$feed['title']][$entry['title']]['archive_uri'] = 'https://archive.ph/submit/?url=' . $entry['link'];
		}
	}
	header('Content-Type: application/json; charset=utf-8');
        echo json_encode($json);
	exit(0);
}

// Render the feed data in HTML by default.
echo '<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <title>News Aggregator</title>
</head>
<body class="bg-gray-100 p-8">';
echo '<div class="grid grid-cols-1 md:grid-cols-' . $gridColumns . ' gap-4">';

foreach ($feedData as $feed) {
	// If for some reason the feed has no items, skip it.
	if (count($feed['items']) < 1) {
		continue;
	}

  echo '<div class="p-4 border border-gray-300 rounded-md">
         <h3 class="text-xl font-bold mb-4">' . $feed['title'] . '</h3>
         <ul class="list-disc pl-6">';

  foreach ($feed['items'] as $item) {
      echo '<li class="mb-2">';
      echo '<a href="' . $item['link'] . '" target="_blank">' . $item['title']  . '</a>';
      echo '<div class="text-sm w-24/2 shadow-xl border-2 border-opacity-25 bg-slate-100 text-gray-500 hover:text-black">';
      echo ' [ ' . '<i><a href="https://archive.ph/submit/?url=' . $item['link'] . '" target="_blank">archive</a></i>' . ' | ' . '<i><a href="https://12ft.io/' . $item['link']  . '" target="_blank">proxy</a></i>' . ' ]';
      echo ' [ ' . $item['date_time']  . ' ]';
      echo '</div>';
      echo '</li>';
  }

  echo '</ul>
        </div>';
}

echo '</div>
     </body>
</html>';
