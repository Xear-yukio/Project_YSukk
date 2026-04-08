<?php
$urls = [
    'https://images.unsplash.com/photo-1507582020474-9a35b7d455d9?w=600&h=600&fit=crop', // drone
    'https://images.unsplash.com/photo-1473968512647-3e447244af8f?w=600&h=600&fit=crop', // drone
    'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=600&h=600&fit=crop', // speaker
    'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=600&h=600&fit=crop',
    'https://images.unsplash.com/photo-1609592424109-84f93cb62fd2?w=600&h=600&fit=crop', // powerbank
    'https://images.unsplash.com/photo-1616422285623-14ffae9656b7?w=600&h=600&fit=crop', // smart home toy
];
foreach($urls as $url) {
    if (strpos(@get_headers($url)[0], '200') !== false) {
        echo "OK - $url\n";
    } else {
        echo "FAIL - $url\n";
    }
}
