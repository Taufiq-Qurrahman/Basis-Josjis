<?php
$images = [
    'chocolate_premium.png' => 'https://images.unsplash.com/featured/600x600/?chocolate,milkshake,glass',
    'matcha_premium.png' => 'https://images.unsplash.com/featured/600x600/?matcha,latte,cup',
    'coffee_premium.png' => 'https://images.unsplash.com/featured/600x600/?iced,latte,coffee',
    'strawberry_premium.png' => 'https://images.unsplash.com/featured/600x600/?strawberry,milkshake,pink',
    'taro_premium.png' => 'https://images.unsplash.com/featured/600x600/?purple,bubbletea,drink',
    'red_velvet_premium.png' => 'https://images.unsplash.com/featured/600x600/?pink,shake,glass'
];

foreach ($images as $filename => $url) {
    echo "Downloading featured $filename...\n";
    
    // Set user agent so Unsplash doesn't block the request
    $options = [
        'http' => [
            'method' => 'GET',
            'header' => 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.0.0 Safari/537.36'
        ]
    ];
    $context = stream_context_create($options);
    
    $content = file_get_contents($url, false, $context);
    if ($content !== false) {
        file_put_contents('public/assets/images/menu/' . $filename, $content);
        echo "Successfully saved $filename\n";
    } else {
        echo "Failed to download $filename\n";
    }
}
