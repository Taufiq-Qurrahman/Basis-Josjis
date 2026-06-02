<?php
$images = [
    'chocolate_premium.png' => 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=600&auto=format&fit=crop&q=80',
    'strawberry_premium.png' => 'https://images.unsplash.com/photo-1553787499-6f9133860278?w=600&auto=format&fit=crop&q=80',
    'taro_premium.png' => 'https://images.unsplash.com/photo-1541658016709-82535e94bc69?w=600&auto=format&fit=crop&q=80', // Fallback to gourmet chocolate if taro ID is not found, or let's use a beautiful purple berry drink:
    'purple_drink.png' => 'https://images.unsplash.com/photo-1628557118391-7683d735f4cf?w=600&auto=format&fit=crop&q=80', // taro tea
    'red_velvet_premium.png' => 'https://images.unsplash.com/photo-1618254746011-f18c644efbe8?w=600&auto=format&fit=crop&q=80' // Red velvet / pink latte
];

foreach ($images as $filename => $url) {
    echo "Downloading $filename...\n";
    $content = file_get_contents($url);
    if ($content !== false) {
        file_put_contents('public/assets/images/menu/' . $filename, $content);
        echo "Successfully saved $filename\n";
    } else {
        echo "Failed to download $filename\n";
    }
}
