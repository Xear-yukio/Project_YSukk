<?php
$urls = [
    "https://logo.clearbit.com/bca.co.id",
    "https://logo.clearbit.com/bri.co.id",
    "https://logo.clearbit.com/bni.co.id",
    "https://logo.clearbit.com/bankmandiri.co.id",
    "https://logo.clearbit.com/gopay.co.id",
    "https://logo.clearbit.com/dana.id",
    "https://logo.clearbit.com/seabank.co.id",
    "https://logo.clearbit.com/qris.id"
];
foreach ($urls as $url) {
    if (strpos(@get_headers($url)[0], '200') !== false) {
        echo "OK - $url\n";
    } else {
        echo "FAIL - $url\n";
    }
}
