<?php
require_once 'vendor/autoload.php';

use SimplePie\SimplePie;

// Read the .ini file
$feedsIni = parse_ini_file('feeds.ini', true);

// Array to hold the feed data
$feedData = [];

foreach ($feedsIni as $title => $feedInfo) {
  // Create a new SimplePie object
  $rss = new SimplePie();
  // Set the feed URL
  $rss->set_feed_url($feedInfo['uri']);

  // Initialize the feed object
  $rss->init();
  $rss->handle_content_type();

  // Array to hold the items for the current feed
  $items = [];

  // Loop through each item in the feed
  foreach ($rss->get_items() as $item) {
      // Add the item data to the array
      $items[] = [
          'title' => $item->get_title(),
          'link' => $item->get_link(),
          'description' => $item->get_description(),
          'date' => $item->get_date('j F Y | g:i a'),
      ];
  }

  // Add the feed data to the array
  $feedData[$title] = [
      'title' => $title,
      'items' => $items,
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
                        $json[$feed['title']][$entry['title']]['date'] = $entry['date'];
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
  <!-- Include Tailwind CSS from CDN -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <title>News Aggregator</title>
</head>
<body class="bg-gray-100 p-8">
  <div class="grid grid-cols-3 gap-4">';

foreach ($feedData as $feed) {
	// If for some reason the feed has no items, skip it.
	if (count($feed['items']) < 1) {
		continue;
	}

  echo '<div class="p-4 border border-gray-300 rounded-md">
         <h3 class="text-xl font-bold mb-4">' . $feed['title'] . '</h3>
         <ul class="list-disc pl-6">';

  foreach ($feed['items'] as $item) {
      echo '<li class="mb-2"><a href="https://archive.ph/submit/?url=' . $item['link'] . '" target="_blank">' . $item['title'] . '</a> <a href="https://12ft.io/' . $item['link'] . '" target="_blank">...</a></li>';
  }

  echo '</ul>
        </div>';
}

echo '</div>
     </body>
</html>';
?>

