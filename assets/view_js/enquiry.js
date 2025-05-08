if ($.fn.DataTable.isDataTable('#EnquiryDataTable')) {
    $('#EnquiryDataTable').DataTable().clear().destroy();
}
const table = $('#EnquiryDataTable').DataTable({
    processing: true,
    serverSide: false,
    ajax: {
        url: frontend + controllerName + '/export_enquires_data_on_datatable',
        type: 'POST',
    },
    columns: [
        { data: 'id' },
        { data: 'name' },
        { data: 'email' },
        { data: 'phone' },
        { data: 'message' },        
        {
            data: null,
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                return `
                    <button class="btn btn-sm btn-info me-1 view-btn" title="View" data-id="${row.id}">
                        <i class="icon-eye menu-icon"></i>
                    </button>
                   
                `;
            }
        }
    ]
});
// 
/* <button class="btn btn-sm btn-warning me-1 edit-btn" title="Edit" data-id="${row.id}">
<i class="icon-pencil menu-icon"></i>
</button>
<button class="btn btn-sm btn-danger delete-btn" title="Delete" data-id="${row.id}">
<i class="icon-trash menu-icon"></i>
</button> */

$('#EnquiryDataTable').on('click', '.view-btn', function () {
    const id = $(this).data('id'); // Get the blog ID from the button's data-id attribute

    // Make the AJAX request to fetch blog details
    $.ajax({
        url: frontend + controllerName + '/export_enquires_data_on_id', // Corrected controller path
        type: 'POST',
        data: {
            id: id // Send the blog ID to fetch the details
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // If the response is successful, populate the modal with the blog data
                const EnquirymemberData = response.data;
                $('#view_name').text(EnquirymemberData.name); 
                $('#view_email').text(EnquirymemberData.email); 
                $('#view_phone').text(EnquirymemberData.phone); 
                $('#view_message').text(EnquirymemberData.message); 
                // Show the modal with the blog details
                $('#viewMemberModal').modal('show');
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
$('#EnquiryDataTable').on('click', '.edit-btn', function () {
const id = $(this).data('id'); // Get the blog ID from the button's data-id attribute

// Make the AJAX request to fetch blog details
$.ajax({
    url: frontend + controllerName + '/export_enquires_data_on_id', // Corrected controller path
    type: 'POST',
    data: {
        id: id // Send the blog ID to fetch the details
    },
    dataType: 'json',
    success: function(response) {
        if (response.status === 'success') {
            // If the response is successful, populate the modal with the blog data
            const EnquirymemberData = response.data;

            // Populate modal fields
            $('#edit_name').text(EnquirymemberData.name); 
            $('#edit_email').text(EnquirymemberData.email); 
            $('#edit_phone').text(EnquirymemberData.mobile); 
            $('#edit_message').text(EnquirymemberData.membership_type); 

            // Show the modal with the blog details
            $('#editMemberModal').modal('show');
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
$('#EnquiryDataTable').on('click', '.delete-btn', function () {
const id = $(this).data('id'); // Get the blog ID from the button's data-id attribute

// Show the delete confirmation modal
$('#deleteMemberModal').modal('show');

// Handle the confirm delete button click
    $('#confirmDeleteBtn').off('click').on('click', function () {
        $.ajax({
            url: frontend + controllerName + '/delete_blog', // Ensure correct controller path
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    // Display success message using SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Enquiry Deleted',
                        text: response.message,
                        timer: 2000, // Auto-close after 2 seconds (2000 ms)
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });

                    // Reload DataTable without resetting the page
                    table.ajax.reload(null, false);
                } else {
                    // Display error message if the delete failed
                    alert(response.message || 'Soft delete failed.');
                }

                // Close the modal after the action is complete
                $('#deleteMemberModal').modal('hide');
            },
            error: function () {
                alert('Error while soft deleting the blog.');
                // Close the modal after the error
                $('#deleteMemberModal').modal('hide');
            }
        });
    });
});
