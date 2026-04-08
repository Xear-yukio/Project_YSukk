<?php

$file = 'C:/Users/Admin/.gemini/antigravity/brain/7ecfdc53-eae9-4e07-bba0-1b232e0de609/task.md';
$content = file_get_contents($file);

$content = str_replace(
    '- [/] Implementing Functional Admin Dashboard [/]',
    '- [x] Implementing Functional Admin Dashboard [x]',
    $content
);

$items = [
    'Create `AdminController` [ ]',
    'Define Admin management routes in `web.php` [ ]',
    'Create themed views for User management [ ]',
    'Create themed views for Product management [ ]',
    'Create themed views for Category management [ ]',
    'Create themed views for Order management [ ]',
    'Create themed views for Reports & Settings [ ]',
    'Update Admin sidebar with functional links [ ]'
];

foreach ($items as $item) {
    $doneItem = str_replace('[ ]', '[x]', $item);
    $content = str_replace($item, $doneItem, $content);
}

file_put_contents($file, $content);
echo "Task.md fully updated\n";
