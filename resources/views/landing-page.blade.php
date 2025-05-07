<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Document</title>
</head>

<body>
    <div class="min-h-screen flex flex-col">
        <x-user.landing-nav />
        <x-user.hero />
        <x-user.service/>
    </div>
    <!-- navbar -->




    <!-- end navbar -->

    {{-- JavaScript for toggling mobile menu --}}
    <script>
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 10) {
                navbar.classList.add('bg-white/90', 'backdrop-blur-md', 'shadow-sm');
            } else {
                navbar.classList.remove('bg-white/90', 'backdrop-blur-md', 'shadow-sm');
            }
        });

        function toggleMenu() {
            const menu = document.getElementById("mobile-menu");
            const menuIcon = document.getElementById("menu-icon");
            const closeIcon = document.getElementById("close-icon");
            menu.classList.toggle("hidden");
            menuIcon.classList.toggle("hidden");
            closeIcon.classList.toggle("hidden");
        }

        function closeMenu() {
            const menu = document.getElementById("mobile-menu");
            const menuIcon = document.getElementById("menu-icon");
            const closeIcon = document.getElementById("close-icon");
            menu.classList.add("hidden");
            menuIcon.classList.remove("hidden");
            closeIcon.classList.add("hidden");
        }
    </script>
</body>

</html>