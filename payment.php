<?php
// Razorpay credentials
$key_id = 'rzp_test_sRt1Dk6cVOlSun';
$key_secret = 'vx2iuB6RD8PtW2nDBlzVpiC4';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $amount_inr = $_POST['amount']; 
    
    // Convert the amount to paise (1 INR = 100 paise)
    $amount = $amount_inr * 100; // Amount in paise (₹40000 becomes 4000000 paise)

    // Razorpay API URL for creating orders
    $api_url = 'https://api.razorpay.com/v1/orders';

    // Order details
    $order_data = [
        'amount' => $amount,
        'currency' => 'INR',
        'receipt' => 'order_receipt_' . time(),
        'payment_capture' => 1 // Auto-capture payment
    ];

    // Convert order data to JSON
    $json_data = json_encode($order_data);

    // Initialize cURL
    $ch = curl_init($api_url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode($key_id . ':' . $key_secret)
    ]);

    // Execute cURL request
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the response
    $order = json_decode($response);

    // Check if the order was created successfully
    if (!empty($order->id)) {
        $order_id = $order->id;

        // Render the Razorpay checkout
        echo "
        <script src='https://checkout.razorpay.com/v1/checkout.js'></script>
        <script>
            var options = {
                'key': '$key_id',
                'amount': '$amount', // Amount should be in paise
                'currency': 'INR',
                'name': 'Car Selling Platform',
                'description': 'Car Purchase Payment',
                'order_id': '$order_id',
                'handler': function (response) {
                    alert('Payment Successful! Payment ID: ' + response.razorpay_payment_id);
                },
                'prefill': {
                    'name': 'John Doe',
                    'email': 'john.doe@example.com',
                    'contact': '9953125911'
                },
                'theme': {
                    'color': '#3399cc'
                }
            };
            var rzp = new Razorpay(options);
            rzp.open();
        </script>";
    } else {
        echo "Error creating Razorpay order.";
    }
} else {
    echo "Invalid request method.";
}
