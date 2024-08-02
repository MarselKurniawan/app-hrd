<?php
include_once "header.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.css" />
</head>

<body class="mt-24">
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-6">Add HRD</h1>

        <!-- Notification Container -->
        <div id="notification-container" class="hidden z-40">
            <div id="notification" class="p-4 mb-4 text-sm rounded-lg" role="alert"></div>
        </div>

        <!-- Form to Add User -->
        <form id="addUserForm" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md" method="POST">
            <div class="mb-4">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                <input type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
            </div>
            <input type="hidden" id="role_id" name="role_id" value="2"> <!-- Default role_id for HRD -->
            <button type="submit" class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Submit</button>
        </form>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Flowbite JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
    <!-- Custom JS -->
    <script>
        function showNotification(message, type) {
            var notificationContainer = $('#notification-container');
            var notification = $('#notification');

            notification.removeClass('hidden text-red-700 bg-red-100 text-green-700 bg-green-100');
            if (type === 'success') {
                notification.addClass('text-green-700 bg-green-100');
            } else {
                notification.addClass('text-red-700 bg-red-100');
            }

            notification.text(message);
            notificationContainer.removeClass('hidden');

            setTimeout(function() {
                notificationContainer.addClass('hidden');
            }, 3000); // Hide after 3 seconds
        }

        $(document).ready(function() {
            $('#addUserForm').on('submit', function(event) {
                event.preventDefault(); // Prevent form from submitting the default way
                $.ajax({
                    url: '/app-hrd/core/api/api_add_hrd.php',
                    type: 'POST',
                    data: JSON.stringify({
                        name: $('#name').val(),
                        email: $('#email').val(),
                        password: $('#password').val(),
                        role_id: $('#role_id').val()
                    }),
                    contentType: 'application/json',
                    success: function(response) {
                        try {
                            console.log("Server response: ", response);
                            var result = JSON.parse(response);
                            showNotification(result.message, "success");
                            if (result.message === "HRD added successfully.") {
                                $('#addUserForm')[0].reset();
                            }
                        } catch (e) {
                            console.error("Invalid JSON response: ", response);
                            showNotification("HRD added successfully.", "success");
                            $('#addUserForm')[0].reset();

                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error: ", status, error);
                        showNotification('Error adding HRD. Email Used', "error");
                    }
                });
            });
        });
    </script>
</body>

</html>