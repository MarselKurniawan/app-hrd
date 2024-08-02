<?php
// session_start(); // Uncomment if session management is needed
$sessionId = session_id();

include_once "header.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.4.7/flowbite.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet">

</head>

<body class="mt-24">
    <div class="container mx-auto mt-10">
        <button type="button" class="mb-10 text-white bg-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center" data-modal-toggle="addEmployeeModal">
            <svg class="w-5 h-5 m-1 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M9 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H7Zm8-1a1 1 0 0 1 1-1h1v-1a1 1 0 1 1 2 0v1h1a1 1 0 1 1 0 2h-1v1a1 1 0 1 1-2 0v-1h-1a1 1 0 0 1-1-1Z" clip-rule="evenodd" />
            </svg>
            Add Employee
        </button>
        <table id="employeeTable" class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Age</th>
                    <th class="py-2 px-4 border-b">Salary</th>
                    <th class="py-2 px-4 border-b">Years Worked</th>
                    <th class="py-2 px-4 border-b">Status</th>
                    <th class="py-2 px-4 border-b">Position</th>
                    <th class="py-2 px-4 border-b">Contract</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody id="employeeTableBody">
                <!-- Data will be populated by JavaScript -->
            </tbody>
        </table>
    </div>

    <!-- Add Employee Modal -->
    <div id="addEmployeeModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center">
        <div class="relative p-4 w-full max-w-md h-full md:h-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex justify-between items-center p-5 rounded-t border-b dark:border-gray-600">
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                        Add Employee
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="addEmployeeModal">
                        <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-6">
                    <form id="addEmployeeForm" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Name</label>
                            <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                        </div>
                        <div class="mb-4">
                            <label for="age" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Age</label>
                            <input type="number" id="age" name="age" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                        </div>
                        <div class="mb-4">
                            <label for="salary" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Salary</label>
                            <input type="number" id="salary" name="salary" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                        </div>
                        <div class="mb-4">
                            <label for="years_worked" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Years Worked</label>
                            <input type="number" id="years_worked" name="years_worked" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                        </div>
                        <div class="mb-4">
                            <label for="employment_status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Employment Status</label>
                            <input type="text" id="employment_status" name="employment_status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                        </div>
                        <div class="mb-4">
                            <label for="position" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Position</label>
                            <input type="text" id="position" name="position" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                        </div>
                        <div class="mb-4">
                            <label for="contract_document" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Contract Document</label>
                            <input type="file" id="contract_document" name="contract_document" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        </div>
                        <div class="mb-4">
                            <input type="hidden" id="hidden_property_id" name="property_id" value="">
                        </div>
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.4.7/flowbite.min.js"></script>
    <script>
        $(document).ready(function() {
            const employeeTable = $('#employeeTable').DataTable({
                columns: [{
                        data: 'name'
                    },
                    {
                        data: 'age'
                    },
                    {
                        data: 'salary'
                    },
                    {
                        data: 'years_worked'
                    },
                    {
                        data: 'employment_status'
                    },
                    {
                        data: 'position'
                    },
                    {
                        data: 'contract_document',
                        render: function(data) {
                            return `<a href="${data}" target="_blank" class="text-blue-500 underline">View</a>`;
                        }
                    },
                    {
                        data: null,
                        defaultContent: '<button class="edit-button text-blue-500">Edit</button> <button class="delete-button text-red-500">Delete</button>'
                    }
                ]
            });

            function fetchEmployees(propertyId) {
                fetch(`/app-hrd/core/api/api_get_employees.php?property_id=${propertyId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Employees data:', data); // Debug log
                        if (data.employees && Array.isArray(data.employees.data)) {
                            employeeTable.clear().rows.add(data.employees.data).draw();
                        } else {
                            console.error('Unexpected employees format:', data);
                        }
                    })
                    .catch(error => console.error('Error fetching employees:', error));
            }

            // Handle property dropdown change
            $('#propertyDropdown').change(function() {
                const propertyId = $(this).val();
                if (propertyId) {
                    fetchEmployees(propertyId);
                } else {
                    employeeTable.clear().draw();
                }
            });

            // Handle Add Employee Form submission
            $('#addEmployeeForm').on('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(this);
                fetch('/app-hrd/core/api/add_employee.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            $('#addEmployeeModal').hide();
                            $('#propertyDropdown').trigger('change'); // Refresh employee list
                        } else {
                            console.error('Failed to add employee:', result.message);
                        }
                    })
                    .catch(error => console.error('Error adding employee:', error));
            });

            // // Handle Edit and Delete button clicks
            // $('#employeeTable').on('click', '.edit-button', function() {
            //     const row = $(this).closest('tr');
            //     const data = employeeTable.row(row).data();
            //     // Open edit modal with data
            //     console.log('Edit data:', data);
            // });

            $('#employeeTable').on('click', '.delete-button', function() {
                const row = $(this).closest('tr');
                const data = employeeTable.row(row).data();
                if (confirm('Are you sure you want to delete this record?')) {
                    fetch(`/app-hrd/core/api/delete_employee.php?id=${data.id}`, {
                            method: 'DELETE'
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                employeeTable.row(row).remove().draw();
                            } else {
                                console.error('Failed to delete employee:', result.message);
                            }
                        })
                        .catch(error => console.error('Error deleting employee:', error));
                }
            });

            // Initial fetch (ensure propertyId is defined or adjust logic)
            const propertyId = $('#propertyDropdown').val(); // Example, adjust as needed
            if (propertyId) {
                fetchEmployees(propertyId);
            }
        });
    </script>

</body>

</html>