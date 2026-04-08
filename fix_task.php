<?php

$file = 'C:/Users/Admin/.gemini/antigravity/brain/7ecfdc53-eae9-4e07-bba0-1b232e0de609/task.md';
$content = file_get_contents($file);

$content = str_replace(
    '- [ ] Define Admin management routes in `web.php` [ ]',
    '- [x] Define Admin management routes in `web.php` [x]',
    $content
);

$content = str_replace(
    '- [ ] Update Admin sidebar with functional links [ ]',
    '- [x] Update Admin sidebar with functional links [x]',
    $content
);

file_put_contents($file, $content);
echo "Task.md updated\n";
