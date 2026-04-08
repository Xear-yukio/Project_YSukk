<?php
$qris = "https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_QRIS.svg/1024px-Logo_QRIS.svg.png";
echo $qris . "\n";
print_r(@get_headers($qris));
