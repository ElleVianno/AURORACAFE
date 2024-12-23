<?php
require_once('vendor/autoload.php');

// Gantikan dengan kunci rahsia anda dari Stripe Dashboard
\Stripe\Stripe::setApiKey('sk_test_51QXalwRwjdqLrFL87zVL4NklmCXlQREf3V4yGeKlYxJMu5Q4zuTou0kI7bzgq3Lq59UhdfrflnPJIGn1IpeKz8MC00s4R7uLWQ');

// Ambil token kad dan jumlah bayaran dari frontend
$input = json_decode(file_get_contents('php://input'), true);
$token = $input['token'];
$amount = $input['amount'];  // Jumlah dalam sen (contoh: RM 1.00 = 100 sen)

// Mengendalikan pemprosesan bayaran
try {
    // Buat bayaran dengan Stripe
    $charge = \Stripe\Charge::create([
        'amount' => $amount,  // Jumlah bayaran dalam sen
        'currency' => 'myr',   // Mata wang MYR
        'source' => $token,    // Token kad debit dari frontend
        'description' => 'Pembayaran untuk pesanan di Secret Recipe',  // Deskripsi bayaran
        'metadata' => [
            'order_id' => uniqid('order_')  // ID pesanan unik untuk rujukan
        ],
    ]);

    // Jika bayaran berjaya, balas dengan success
    echo json_encode(['success' => true, 'message' => 'Pembayaran berjaya!']);
} catch (\Stripe\Exception\ApiErrorException $e) {
    // Jika berlaku ralat, balas dengan mesej ralat
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
