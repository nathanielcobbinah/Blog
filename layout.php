<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebDev Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-black text-gray-800">

    <!-- Header -->
    <?php include 'head.php'; ?>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8 bg-red">
        <?php include $content; ?>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

</body>
</html>
