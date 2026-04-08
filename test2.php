<?php
$urls = [
    "https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_QRIS.svg/1024px-Logo_QRIS.svg.png",
    "https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/1024px-Bank_Central_Asia.svg.png",
    "https://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/BRI_Logo.svg/1024px-BRI_Logo.svg.png",
    "https://upload.wikimedia.org/wikipedia/commons/thumb/5/51/BNI_logo.svg/1024px-BNI_logo.svg.png",
    "https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/1024px-Bank_Mandiri_logo_2016.svg.png"
];
foreach ($urls as $url) {
    if (strpos(@get_headers($url)[0], '403') !== false) {
        echo "FAIL 403 - $url\n";
    } else {
        echo "OK - $url\n";
    }
}
