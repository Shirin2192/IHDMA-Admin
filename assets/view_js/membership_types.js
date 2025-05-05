$(document).ready(function () {
    $('.chosen-select').chosen({
        width: '100%',           // Optional: makes it responsive
        no_results_text: "No result matched: " // Optional
    });
});
$('#MembershipTypeForm').on('submit', function (e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);

    // Clear previous error messages
    $('#MembershipTypeForm small.text-danger').text('');

    $.ajax({
        url: frontend + controllerName + '/save_membership_type',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        beforeSend: function () {
            $('.button_submit').prop('disabled', true);
        },
        success: function (response) {
            $('.button_submit').prop('disabled', false);

            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message,
                    timer: 2000,
                    confirmButtonColor: '#3085d6'
                });
                form.reset();
                $('.chosen-select').val('').trigger("chosen:updated");
                $('#MembershipTypeTable').DataTable().ajax.reload(null, false);
            } else if (response.status === 'error') {
                if (response.errors) {
                    $.each(response.errors, function (key, val) {
                        $('#' + key + '_error').text(val);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Something went wrong!'
                    });
                }
            }
        },
        error: function (xhr) {
            $('.button_submit').prop('disabled', false);
            Swal.fire({
                icon: 'error',
                title: 'Server Error',
                text: 'An unexpected error occurred.'
            });
            console.error(xhr.responseText);
        }
    });
});

$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('#MemberTypeTable')) {
        $('#MemberTypeTable').DataTable().clear().destroy();
    }
    
    const table = $('#MemberTypeTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: frontend + controllerName + '/membership_type_data_on_datatable',
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
            { data: 'category_name',},
            { data: 'type_name', },
            { data: 'code', },
            {
                data: 'price',
                render: function (data, type, row) {
                    return row.symbol ? `${row.symbol} ${data}` : data;
                }
            },
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
    $('#MemberTypeTable').on('click', '.view-btn', function () {
        const id = $(this).data('id'); // Get the blog ID from the button's data-id attribute
    
        // Make the AJAX request to fetch blog details
        $.ajax({
            url: frontend + controllerName + '/membership_type_data_on_id', // Corrected controller path
            type: 'POST',
            data: {
                id: id // Send the blog ID to fetch the details
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    
                    $('#view_category_name').text(response.data.category_name);
                    $('#view_type_name').text(response.data.type_name);
                    $('#view_currency').text(response.data.code);
                    $('#view_price').text(response.data.symbol + ' ' + response.data.price); // Append currency symbol to the price
                    $('#view_short_description').text(response.data.short_description);
                    $('#view_full_description').text(response.data.full_description);
                    $('#viewMemberTypeModal').modal('show');
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
$('#MemberTypeTable').on('click', '.edit-btn', function () {
    const id = $(this).data('id'); // Get the blog ID from the button's data-id attribute

    // Make the AJAX request to fetch blog details
    $.ajax({
        url: frontend + controllerName + '/membership_type_data_on_id', // Corrected controller path
        type: 'POST',
        data: {
            id: id // Send the blog ID to fetch the details
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // If the response is successful, populate the modal with the blog data
                $('#edit_membership_type_id').val(response.data.id);
                $('#edit_category_name').val(response.data.fk_category_id).trigger('chosen:updated');
                $('#edit_type_name').val(response.data.type_name);
                $('#edit_currency').val(response.data.fk_currency_id).trigger('chosen:updated');
                $('#edit_price').val(response.data.price);
                $('#edit_short_description').val(response.data.short_description);
                $('#edit_full_description').val(response.data.full_description);
                // Show the modal with the blog details
                $('#editMemberTypeModal').modal('show');
            } else {
                // Handle the error if the status is not success
                alert('Failed to load blog details.');
            }
        },
        error: function(xhr, status, error) {
            // Handle AJAX error
            console.error('AJAX Error: ' + status + ' - ' + error);
            alert('An error occurred while fetching the details.');
        }
    });
});

    

    // Delete button handler (Soft Delete)
// Delete button handler (Soft Delete)
$('#MemberTypeTable').on('click', '.delete-btn', function () {
    const id = $(this).data('id'); // Get the blog ID from the button's data-id attribute

    // Show the delete confirmation modal
    $('#deleteMemberTypeModal').modal('show');

    // Handle the confirm delete button click
    $('#confirmDeleteBtn').off('click').on('click', function () {
        $.ajax({
            url: frontend + controllerName + '/delete_membership_type', // Ensure correct controller path
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    // Display success message using SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Member Type Deleted',
                        text: response.message,
                        timer: 2000, // Auto-close after 2 seconds (2000 ms)
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });

                    // Reload DataTable without resetting the page
                    table.ajax.reload(null, false);
                } else {
                    // Display error message if the delete failed
                    alert(response.message);
                }

                // Close the modal after the action is complete
                $('#deleteMemberTypeModal').modal('hide');
            },
            error: function () {
                alert('Error while soft deleting the blog.');
                // Close the modal after the error
                $('#deleteMemberTypeModal').modal('hide');
            }
        });
    });
});


});
$('#editMemberTypeForm').on('submit', function (e) {
    e.preventDefault();

    let formData = new FormData(this); // Collect the form data, including the file
    $.ajax({
        url: frontend + controllerName + '/update_membership_type', // Adjust this URL to your controller method
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        beforeSend: function () {
            // Clear previous errors
            $('#editMemberTypeForm small.text-danger').text('');
        },
        success: function (response) {
            if (response.status === 'success') {
                $('#editMemberTypeModal').modal('hide');
                $('#editMemberTypeForm')[0].reset();
                $('#MemberTypeTable').DataTable().ajax.reload(null, false); // Reload without resetting pagination
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
