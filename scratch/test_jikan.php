<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

$mangaTitles = [
    'Jujutsu Kaisen' => 'https://cdn.myanimelist.net/images/manga/3/210341l.jpg',
    'Chainsaw Man' => 'https://cdn.myanimelist.net/images/manga/3/216464l.jpg',
    'One Piece' => 'https://cdn.myanimelist.net/images/manga/2/253146l.jpg',
    'Berserk' => 'https://cdn.myanimelist.net/images/manga/1/157897l.jpg',
    'Spy x Family' => 'https://cdn.myanimelist.net/images/manga/3/219741l.jpg',
    'Demon Slayer (Kimetsu no Yaiba)' => 'https://cdn.myanimelist.net/images/manga/3/179023l.jpg',
];

foreach ($mangaTitles as $title => $fallbackUrl) {
    $coverUrl = null;
    try {
        $cleanTitle = explode(' (', $title)[0];
        $apiUrl = 'https://api.jikan.moe/v4/manga?q=' . urlencode($cleanTitle) . '&limit=1';
        $response = Http::withoutVerifying()->timeout(5)->get($apiUrl)->json();
        
        if (isset($response['data'][0]['images']['jpg']['large_image_url'])) {
            $coverUrl = $response['data'][0]['images']['jpg']['large_image_url'];
        } elseif (isset($response['data'][0]['images']['jpg']['image_url'])) {
            $coverUrl = $response['data'][0]['images']['jpg']['image_url'];
        }
    } catch (\Exception $e) {
        // Ignored, fallback will be used
    }

    if (!$coverUrl) {
        $coverUrl = $fallbackUrl;
    }

    echo "RESULT FOR {$title}: {$coverUrl}\n";
    usleep(400000); // 0.4s rate limit delay
}
