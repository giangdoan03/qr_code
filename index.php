<?php
// Thông tin cần thiết cho yêu cầu
$client_id = '8746dbc3-9fd3-48c1-9822-e290c8771046';
$api_key = 'c1cbb283-5450-4584-abb7-a68cf30a8dc2';
$url = 'https://api.vietqr.io/v2/generate';

$transfer_content = 'DAT chuyen khoan';

// Dữ liệu gửi đi
$data = [
    "accountNo" => "244933718",
    "accountName" => "DOAN VAN GIANG",
    "acqId" => "970432",
    "addInfo" => $transfer_content,
    "amount" => "1000000",
    "template" => "compact"
];

// Chuyển dữ liệu thành JSON
$data_json = json_encode($data);

echo "<pre>";
print_r($data_json);
echo "</pre>";

// Khởi tạo cURL
$ch = curl_init($url);

// Thiết lập các tùy chọn cho cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "x-client-id: $client_id",
    "x-api-key: $api_key",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);

// Thực hiện yêu cầu và lấy kết quả trả về
$response = curl_exec($ch);

// Kiểm tra lỗi
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    // Chuyển đổi kết quả từ JSON sang mảng PHP
    $response_data = json_decode($response, true);

    // Hiển thị toàn bộ phản hồi từ API để kiểm tra
    echo "<pre>";
    print_r($response_data);
    echo "</pre>";

    // Nếu thành công, lấy chuỗi base64 của mã QR code từ qrDataURL
    if (isset($response_data['data']['qrDataURL'])) {
        $base64QRCode = $response_data['data']['qrDataURL'];
    } else {
        echo "Không lấy được chuỗi base64 của mã QR code.";
        exit;
    }
}

// Đóng cURL
curl_close($ch);

// Sử dụng UUID
$uuid = uniqid();

// Sử dụng timestamp và mã hash
$unique_string = md5(uniqid(rand(), true));

// In ra chuỗi duy nhất
echo "UUID: $uuid<br>";
echo "Unique String: $unique_string<br>";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code</title>
</head>
<body>
<h1>Mã QR Code</h1>
<?php if (isset($base64QRCode)): ?>
    <img src="<?php echo $base64QRCode; ?>" alt="QR Code">
<?php else: ?>
    <p>Không thể hiển thị mã QR code.</p>
<?php endif; ?>
</body>
</html>
