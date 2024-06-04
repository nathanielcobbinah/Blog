<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | EscadeDev</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white">

    <!-- Header -->
    <?php include 'head.php'; ?>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="bg-gray-800 shadow-md rounded-lg p-6">
            <h1 class="text-3xl font-bold mb-4">Contact Us</h1>
            <p class="mb-6 text-gray-300">
                We would love to hear from you! Whether you have a question, feedback, or just want to say hello, feel free to reach out to us. Fill out the form below or use one of our social media channels.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Contact Form -->
                <div>
                    <form action="contact_process.php" method="post" class="space-y-4">
                        <div>
                            <label for="name" class="block text-gray-400 mb-1">Name:</label>
                            <input type="text" id="name" name="name" required class="w-full px-4 py-2 border border-gray-700 rounded-md focus:outline-none focus:ring focus:ring-indigo-400 focus:border-indigo-500 bg-gray-900 text-white">
                        </div>
                        <div>
                            <label for="email" class="block text-gray-400 mb-1">Email:</label>
                            <input type="email" id="email" name="email" required class="w-full px-4 py-2 border border-gray-700 rounded-md focus:outline-none focus:ring focus:ring-indigo-400 focus:border-indigo-500 bg-gray-900 text-white">
                        </div>
                        <div>
                            <label for="message" class="block text-gray-400 mb-1">Message:</label>
                            <textarea id="message" name="message" rows="6" required class="w-full px-4 py-2 border border-gray-700 rounded-md focus:outline-none focus:ring focus:ring-indigo-400 focus:border-indigo-500 bg-gray-900 text-white"></textarea>
                        </div>
                        <div>
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:bg-indigo-700">Send Message</button>
                        </div>
                    </form>
                </div>
                <!-- Contact Information -->
                <div class="flex flex-col space-y-4 text-gray-300">
                    <div>
                        <h2 class="text-xl font-semibold">Our Office</h2>
                        <p>123 WebDev Street<br>Suite 456<br>WebCity, WB 78910</p>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold">Email</h2>
                        <p><a href="mailto:contact@escadedev.com" class="text-indigo-400 hover:text-indigo-500">contact@escadedev.com</a></p>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold">Phone</h2>
                        <p>+1 (123) 456-7890</p>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold">Follow Us</h2>
                        <div class="flex space-x-4">
                            <a href="#" class="hover:text-indigo-400"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="hover:text-indigo-400"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="hover:text-indigo-400"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="hover:text-indigo-400"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Font Awesome for social media icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="
