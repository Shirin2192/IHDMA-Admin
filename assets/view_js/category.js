$('#CategoryForm').on('submit', function (e) {
    e.preventDefault();

    let formData = new FormData(this); // Collect the form data, including the file
    $.ajax({
        url: frontend + controllerName + '/save_category', // Adjust this URL to your controller method
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        beforeSend: function () {
            // Clear previous errors
            $('#CategoryForm small.text-danger').text('');
        },
        success: function (response) {
            if (response.status === 'success') {
                $('#CategoryForm')[0].reset();
                $('#MemberCategoryTable').DataTable().ajax.reload(null, false); // Reload without resetting pagination
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message,
                    timer: 2000, // Auto-close after 2 seconds (2000 ms)
                    // showConfirmButton: true,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });

            } else if (response.status === 'error') {
                // Show validation errors under each input
                if (response.errors) {
                    $.each(response.errors, function (key, val) {
                        $('#' + key + '_error').text(val);
                        
                        // Hide the error message after 5 seconds
                        setTimeout(function() {
                            $('#' + key + '_error').text('');
                        }, 5000);
                    });
                }
                // Optional upload error fallback
                
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message,
                    timer: 2000, // Auto-close after 2 seconds (2000 ms)
                    // showConfirmButton: true,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error:', status, error);
            alert('An unexpected error occurred.');
        }
    });
});
$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('#MemberCategoryTable')) {
        $('#MemberCategoryTable').DataTable().clear().destroy();
    }
    
    const table = $('#MemberCategoryTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: frontend + controllerName + '/category_data_on_datatable',
            type: 'POST',
        },
        columns: [
            {
                data: null,
                title: 'Sr. No.',
                orderable: false,
                searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + 1;  // Auto-increment serial number
                }
            },
            { data: 'category_name', title: 'Category Name' },
            { data: 'description', title: 'Description' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-sm btn-info me-1 view-btn" title="View" data-id="${row.id}">
                            <i class="icon-eye menu-icon"></i>
                        </button>
                        <button class="btn btn-sm btn-warning me-1 edit-btn" title="Edit" data-id="${row.id}">
                            <i class="icon-pencil menu-icon"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" title="Delete" data-id="${row.id}">
                            <i class="icon-trash menu-icon"></i>
                        </button>
                    `;
                }
            }
        ]
    });
    

    // View button handler
    $('#MemberCategoryTable').on('click', '.view-btn', function () {
        const id = $(this).data('id'); // Get the blog ID from the button's data-id attribute
    
        // Make the AJAX request to fetch blog details
        $.ajax({
            url: frontend + controllerName + '/category_data_on_id', // Corrected controller path
            type: 'POST',
            data: {
                id: id // Send the blog ID to fetch the details
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // If the response is successful, populate the modal with the blog data
                    const CategoryData = response.data;
                    $('#view_category_name').text(CategoryData.category_name);
                    $('#view_description').text(CategoryData.description); 
                    $('#viewMemberCategoryModal').modal('show');
                } else {
                    // Handle the error if the status is not success
                    alert('Failed to load blog details.');
                }
            },
            error: function(xhr, status, error) {
                // Handle AJAX error
                console.error('AJAX Error: ' + status + ' - ' + error);
                alert('An error occurred while fetching the blog details.');
            }
        });
    });
    

   // Edit button handler
$('#MemberCategoryTable').on('click', '.edit-btn', function () {
    const id = $(this).data('id'); // Get the blog ID from the button's data-id attribute

    // Make the AJAX request to fetch blog details
    $.ajax({
        url: frontend + controllerName + '/category_data_on_id', // Corrected controller path
        type: 'POST',
        data: {
            id: id // Send the blog ID to fetch the details
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // If the response is successful, populate the modal with the blog data
                const EditCategoryData = response.data;

                // Populate modal fields
                $('#edit_category_id').val(EditCategoryData.id);
                $('#edit_category_name').val(EditCategoryData.category_name);
                $('#edit_description').val(EditCategoryData.description);

                // Show the modal with the blog details
                $('#editMembershipCategoryModal').modal('show');
            } else {
                // Handle the error if the status is not success
                alert('Failed to load blog details.');
            }
        },
        error: function(xhr, status, error) {
            // Handle AJAX error
            console.error('AJAX Error: ' + status + ' - ' + error);
            alert('An error occurred while fetching the blog details.');
        }
    });
});

    

    // Delete button handler (Soft Delete)
// Delete button handler (Soft Delete)
$('#MemberCategoryTable').on('click', '.delete-btn', function () {
    const id = $(this).data('id'); // Get the blog ID from the button's data-id attribute

    // Show the delete confirmation modal
    $('#deleteCategoryModal').modal('show');

    // Handle the confirm delete button click
    $('#confirmDeleteBtn').off('click').on('click', function () {
        $.ajax({
            url: frontend + controllerName + '/delete_category', // Ensure correct controller path
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    // Display success message using SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Category Deleted',
                        text: response.message,
                        timer: 2000, // Auto-close after 2 seconds (2000 ms)
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });

                    // Reload DataTable without resetting the page
                    table.ajax.reload(null, false);
                } else {
                    // Display error message if the delete failed
                    alert(response.message || 'Category delete failed.');
                }

                // Close the modal after the action is complete
                $('#deleteCategoryModal').modal('hide');
            },
            error: function () {
                alert('Error while soft deleting the blog.');
                // Close the modal after the error
                $('#deleteCategoryModal').modal('hide');
            }
        });
    });
});


});
$('#editMemberCategoryForm').on('submit', function (e) {
    e.preventDefault();

    let formData = new FormData(this); // Collect the form data, including the file
    $.ajax({
        url: frontend + controllerName + '/update_category', // Adjust this URL to your controller method
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        beforeSend: function () {
            // Clear previous errors
            $('#editMemberCategoryForm small.text-danger').text('');
        },
        success: function (response) {
            if (response.status === 'success') {
                $('#editMembershipCategoryModal').modal('hide');
                $('#editMemberCategoryForm')[0].reset();
                $('#MemberCategoryTable').DataTable().ajax.reload(null, false); // Reload without resetting pagination
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message,
                    timer: 2000, // Auto-close after 2 seconds (2000 ms)
                    // showConfirmButton: true,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });

            } else if (response.status === 'error') {
                // Show validation errors under each input
                if (response.errors) {
                    $.each(response.errors, function (key, val) {
                        $('#' + key + '_error').text(val);
                        
                        // Hide the error message after 5 seconds
                        setTimeout(function() {
                            $('#' + key + '_error').text('');
                        }, 5000);
                    });
                }
                // Optional upload error fallback
                
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message,
                    timer: 2000, // Auto-close after 2 seconds (2000 ms)
                    // showConfirmButton: true,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error:', status, error);
            alert('An unexpected error occurred.');
        }
    });
});
