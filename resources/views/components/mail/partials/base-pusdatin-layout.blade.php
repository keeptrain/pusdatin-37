@props([
    'title' => '',
])
<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 0;">
    <!-- Header -->
    <div style="padding: 2px; text-align: center;">
        <img src="https://drive.google.com/uc?export=download&id=1BquEEMMDAdfyryH4EFqqB2krum-MzABd" alt="Logo" style="width: 300px;">
    </div>

    <!-- Body -->
    <div style="padding: 10px; max-width: 600px; margin: 0 auto;">
        {{ $slot }}
    </div>

    <!-- Footer -->
    <div style="background-color: #f4f4f4; padding: 10px; text-align: center; font-size: 12px; color: #777;">
        &copy; 2025 Pusat Data dan Teknologi Informasi Dinas Kesehatan. All rights reserved.
    </div>
</body>

</html>