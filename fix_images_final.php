<?php

$file = 'c:/Users/Admin/Projek_YSukk/app/Models/Product.php';
$content = file_get_contents($file);

// Fix Product 4 specifically (Gaming Controller)
// Let's use 1600080972464-8e5f35f63d08 for 4 and 1621259182978-f09e5e2ca8d6 for 307
$old4 = "62 => [
                'id' => 4,
                'name' => 'GP11 Shooter USB Gamepad',
                'price' => '123.000',
                'old_price' => null,
                'discount' => null,
                'rating' => 5,
                'reviews' => 55,
                'description' => 'Gamepad USB ergonomis untuk pengalaman bermain game yang nyaman.',
                'colors' => ['#000'],
                'main_image' => 'https://images.unsplash.com/photo-1621259182978-f09e5e2ca8d6?w=600&h=600&fit=crop',";
$new4 = "62 => [
                'id' => 4,
                'name' => 'GP11 Shooter USB Gamepad',
                'price' => '123.000',
                'old_price' => null,
                'discount' => null,
                'rating' => 5,
                'reviews' => 55,
                'description' => 'Gamepad USB ergonomis untuk pengalaman bermain game yang nyaman.',
                'colors' => ['#000'],
                'main_image' => 'https://images.unsplash.com/photo-1600080972464-8e5f35f63d08?w=600&h=600&fit=crop',";

$content = str_replace($old4, $new4, $content);

// Fix Product 3 specifically (Sports Shoes)
// Let's use 1542291026-7eec264c27ff for 3 and 1511886929837-354d827aae26 for 306
$old3 = "45 => [
                'id' => 3,
                'name' => 'Sepatu Sepak Bola Jr. Zoom',
                'price' => '876.000',
                'old_price' => null,
                'discount' => null,
                'rating' => 5,
                'reviews' => 35,
                'description' => 'Sepatu sepak bola berkualitas tinggi untuk performa maksimal di lapangan.',
                'colors' => ['#ffff00', '#000'],
                'main_image' => 'https://images.unsplash.com/photo-1511886929837-354d827aae26?w=600&h=600&fit=crop',";
$new3 = "45 => [
                'id' => 3,
                'name' => 'Sepatu Sepak Bola Jr. Zoom',
                'price' => '876.000',
                'old_price' => null,
                'discount' => null,
                'rating' => 5,
                'reviews' => 35,
                'description' => 'Sepatu sepak bola berkualitas tinggi untuk performa maksimal di lapangan.',
                'colors' => ['#ffff00', '#000'],
                'main_image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&h=600&fit=crop',";

$content = str_replace($old3, $new3, $content);

file_put_contents($file, $content);
echo "Final Done\n";
