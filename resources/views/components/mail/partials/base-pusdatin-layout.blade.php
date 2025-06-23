@props([
    'title' => '',
    'message' => null
])
<?php
$logo = $message->embed(storage_path('app/assets/pusdatin-logo.jpg'));
?>
<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 0;">
    <!-- Header -->
    <div style="padding: 2px; text-align: center;">
        <img src="{{ $logo }}" alt="Logo" style="width: 300px;">
    </div>

    <!-- Body -->
    <div style="padding: 20px; max-width: 600px; margin: 0 auto;">
        {{ $slot }}
    </div>

    <!-- Footer -->
    <div style="background-color: #f4f4f4; padding: 10px; text-align: center; font-size: 12px; color: #777;">
        &copy; 2025 Pusat Data dan Teknologi Informasi Dinas Kesehatan. All rights reserved.
    </div>
</body>

</html>