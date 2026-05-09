<?php
header('Content-Type: application/json');

require_once 'db.php'; // your database connection

$response = [
    "success" => false,
    "message" => "Something went wrong"
];

try {

    // Read JSON input
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        throw new Exception("Invalid request data");
    }

    $order_id = trim($data['order_id'] ?? '');
    $upi_id   = trim($data['upi_id'] ?? '');

    if (empty($order_id) || empty($upi_id)) {
        throw new Exception("Order ID and UPI ID are required");
    }

    // Optional: validate UPI format
    if (!preg_match('/^[a-zA-Z0-9.\-_]{2,}@[a-zA-Z]{2,}$/', $upi_id)) {
        throw new Exception("Invalid UPI ID");
    }

    /*
    -------------------------------------------------
    CREATE refund_requests TABLE FIRST
    -------------------------------------------------

    CREATE TABLE refund_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id VARCHAR(50) NOT NULL,
        upi_id VARCHAR(255) NOT NULL,
        status VARCHAR(50) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    -------------------------------------------------
    */

    // Save refund request
    $stmt = $conn->prepare("
        INSERT INTO refund_requests (order_id, upi_id)
        VALUES (?, ?)
    ");

    $stmt->bind_param("ss", $order_id, $upi_id);

    if (!$stmt->execute()) {
        throw new Exception("Failed to save refund request");
    }

    $response['success'] = true;
    $response['message'] = "Refund request submitted successfully";

} catch (Exception $e) {

    $response['message'] = $e->getMessage();

}

echo json_encode($response);
?>