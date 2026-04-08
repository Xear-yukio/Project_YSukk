<?php

$file = 'c:/Users/Admin/Projek_YSukk/app/Models/Product.php';
$content = file_get_contents($file);

// Add unique images for 306
$old306 = "'main_image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&h=600&fit=crop',
                'gallery' => ['https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=300&h=300&fit=crop'],";

$new306 = "'main_image' => 'https://images.unsplash.com/photo-1511886929837-354d827aae26?w=600&h=600&fit=crop',
                'gallery' => [
                    'https://images.unsplash.com/photo-1511886929837-354d827aae26?w=300&h=300&fit=crop',
                    'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=300&h=300&fit=crop'
                ],";

$content = str_replace($old306, $new306, $content);

// Add unique images for 307
$old307 = "'main_image' => 'https://images.unsplash.com/photo-1600080972464-8e5f35f63d08?w=600&h=600&fit=crop',
                'gallery' => ['https://images.unsplash.com/photo-1600080972464-8e5f35f63d08?w=300&h=300&fit=crop'],";

$new307 = "'main_image' => 'https://images.unsplash.com/photo-1621259182978-f09e5e2ca8d6?w=600&h=600&fit=crop',
                'gallery' => [
                    'https://images.unsplash.com/photo-1621259182978-f09e5e2ca8d6?w=300&h=300&fit=crop',
                    'https://images.unsplash.com/photo-1600080972464-8e5f35f63d08?w=300&h=300&fit=crop'
                ],";

$content = str_replace($old307, $new307, $content);

file_put_contents($file, $content);
echo "Done\n";
