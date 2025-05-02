if ($.fn.DataTable.isDataTable('#memberDataTable')) {
    $('#memberDataTable').DataTable().clear().destroy();
}
const table = $('#memberDataTable').DataTable({
    processing: true,
    serverSide: false,
    ajax: {
        url: frontend + controllerName + '/export_member_data_on_datatable',
        type: 'POST',
    },
    columns: [
        { data: 'id' },
        { data: 'name' },
        { data: 'email' },
        { data: 'mobile' },
        {data: 'membership_type'},
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

$('#memberDataTable').on('click', '.view-btn', function () {
    const id = $(this).data('id'); // Get the blog ID from the button's data-id attribute

    // Make the AJAX request to fetch blog details
    $.ajax({
        url: frontend + controllerName + '/export_member_data_on_id', // Corrected controller path
        type: 'POST',
        data: {
            id: id // Send the blog ID to fetch the details
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // If the response is successful, populate the modal with the blog data
                const blogData = response.data;
                $('#view_title').text(blogData.title); // Set the title
                $('#view_slug').text(blogData.slug); // Set the slug
                $('#view_content').text(blogData.content); // Set the content

                // Ensure the image URL is correct
                if (blogData.featured_image) {
                    $('#view_featured_image').attr('src', frontend + 'uploads/blogs/' + blogData.featured_image);
                } else {
                    // Fallback image if no featured image is found
                    $('#view_featured_image').attr('src', '/path/to/default-image.jpg');
                }

                $('#view_status').text(blogData.status); // Set the status

                // Show the modal with the blog details
                $('#viewBlogModal').modal('show');
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
$('#memberDataTable').on('click', '.edit-btn', function () {
const id = $(this).data('id'); // Get the blog ID from the button's data-id attribute

// Make the AJAX request to fetch blog details
$.ajax({
    url: frontend + controllerName + '/export_member_data_on_id', // Corrected controller path
    type: 'POST',
    data: {
        id: id // Send the blog ID to fetch the details
    },
    dataType: 'json',
    success: function(response) {
        if (response.status === 'success') {
            // If the response is successful, populate the modal with the blog data
            const blogData = response.data;

            // Populate modal fields
            $('#edit_blog_id').val(blogData.id);
            $('#edit_title').val(blogData.title);
            $('#edit_slug').val(blogData.slug);
            $('#edit_content').val(blogData.content);

            // Check if the blog has a featured image and set it
            if (blogData.featured_image) {
                $('#current_featured_image').attr('src', frontend + 'uploads/blogs/' + blogData.featured_image).show();
            } else {
                $('#current_featured_image').hide();  // Hide the image section if there's no image
            }

            $('#edit_status').val(blogData.status);  // Set the status dropdown

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
$('#memberDataTable').on('click', '.delete-btn', function () {
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
                        title: 'Blog Soft Deleted',
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
