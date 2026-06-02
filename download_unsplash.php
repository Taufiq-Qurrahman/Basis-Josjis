<?php
$images = [
    'chocolate_milk.png' => 'https://images.unsplash.com/photo-1541658016709-82535e94bc69?w=600&auto=format&fit=crop&q=80',
    'iced_coffee.png' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=600&auto=format&fit=crop&q=80',
    'matcha_latte.png' => 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?w=600&auto=format&fit=crop&q=80',
    'strawberry_milk.png' => 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=600&auto=format&fit=crop&q=80'
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
