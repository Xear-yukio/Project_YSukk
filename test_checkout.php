<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\DB::beginTransaction();
try {
    $order = \App\Models\Order::create([
        'user_id' => 1,
        'full_name' => 'Test',
        'phone' => '123',
        'email' => 'test@test.com',
        'province' => 'Prov',
        'city' => 'City',
        'district' => 'Dist',
        'postal_code' => '123',
        'address' => 'Addr',
        'notes' => '',
        'total_amount' => 1000,
        'payment_method' => 'qris',
        'status' => 'pending'
    ]);
    echo "Order created: {$order->id}\n";
    
    // Test getPaymentInstructions method by instantiating CheckoutController
    $ctrl = new \App\Http\Controllers\CheckoutController();
    $reflection = new \ReflectionMethod($ctrl, 'getPaymentInstructions');
    $reflection->setAccessible(true);
    
    // Login user 1 so auth()->id() works
    \Auth::loginUsingId(1);
    
    $paymentInfo = $reflection->invoke($ctrl, 'qris');
    $view = view('checkout.payment', compact('order', 'paymentInfo'))->render();
    echo "QRIS View rendered successfully.\n";
    
    $order->payment_method = 'bca';
    $paymentInfo = $reflection->invoke($ctrl, 'bca');
    $view = view('checkout.payment', compact('order', 'paymentInfo'))->render();
    echo "BCA View rendered successfully.\n";
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getFile() . ":" . $e->getLine();
}
\DB::rollBack();
echo "Done.\n";
