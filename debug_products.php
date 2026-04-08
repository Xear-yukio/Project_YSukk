<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Category;

foreach(Product::take(20)->get() as $p) {
    echo "ID: {$p->id} | Name: {$p->name} | Cat: " . ($p->category ? $p->category->slug : 'NULL') . "\n";
}
