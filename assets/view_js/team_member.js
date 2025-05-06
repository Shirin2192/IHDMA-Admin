$('#TeamMemberForm').on('submit', function (e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);

    // Clear previous error messages
    $('#TeamMemberForm small.text-danger').text('');

    $.ajax({
        url: frontend + controllerName + '/add_team_member',
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
                $('#TeamMemberTable').DataTable().ajax.reload(null, false);
            } else if (response.status === 'error') {
                if (response.errors) {
                    $.each(response.errors, function (key, val) {
                        const errorElement = $('#' + key + '_error');
                        errorElement.text(val);
                
                        // Auto-clear after 5 seconds (5000 ms)
                        setTimeout(function () {
                            errorElement.text('');
                        }, 5000);
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
    if ($.fn.DataTable.isDataTable('#TeamMemberTable')) {
        $('#TeamMemberTable').DataTable().clear().destroy();
    }
    
    const table = $('#TeamMemberTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: frontend + controllerName + '/team_member_data_on_datatable',
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
            { data: 'name',},
            { data: 'designation', },
            { data: 'description', },
            {
                data: 'photo',
                render: function (data, type, row) {
                    if (data) {
                        return `<img src="${frontend}${data}" alt="Photo" height="50" style="border-radius: 5px;" />`;
                    } else {
                        return '<span class="text-muted">No image</span>';
                    }
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
    $('#TeamMemberTable').on('click', '.view-btn', function () {
        const id = $(this).data('id'); // Get the team member ID
    
        // Make the AJAX request to fetch team member details
        $.ajax({
            url: frontend + controllerName + '/team_member_data_on_id',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const data = response.data;
    
                    // Basic text fields
                    $('#view_name').text(data.name);
                    $('#view_designation').text(data.designation);
                    $('#view_description').text(data.description);
    
                    // Social links
                    $('#view_facebook_link').html(data.facebook_link ? `<a href="${data.facebook_link}" target="_blank">${data.facebook_link}</a>` : '');
                    $('#view_linkedin_link').html(data.linkedin_link ? `<a href="${data.linkedin_link}" target="_blank">${data.linkedin_link}</a>` : '');
                    $('#view_youtube_link').html(data.youtube_link ? `<a href="${data.youtube_link}" target="_blank">${data.youtube_link}</a>` : '');
                    $('#view_twitter_link').html(data.twitter_link ? `<a href="${data.twitter_link}" target="_blank">${data.twitter_link}</a>` : '');
    
                    // Photo
                    if (data.photo) {
                        $('#view_photo').html(`<img src="${frontend}${data.photo}" class="img-fluid rounded shadow" alt="Photo" style="width:50%" />`);

                    } else {
                        $('#view_photo').text('No photo available');
                    }
    
                    // Show modal
                    $('#viewMemberTypeModal').modal('show');
                } else {
                    alert('Failed to load team member details.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + status + ' - ' + error);
                alert('An error occurred while fetching the team member details.');
            }
        });
    });
    
    

   // Edit button handler
   $('#TeamMemberTable').on('click', '.edit-btn', function () {
    const id = $(this).data('id'); // Get the team member ID

    $.ajax({
        url: frontend + controllerName + '/team_member_data_on_id',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                const data = response.data;

                // Populate form fields
                $('#edit_member_id').val(data.id); // Hidden ID input
                $('#edit_name').val(data.name);
                $('#edit_designation').val(data.designation);
                $('#edit_description').val(data.description);
                $('#edit_facebook_link').val(data.facebook_link);
                $('#edit_linkedin_link').val(data.linkedin_link);
                $('#edit_youtube_link').val(data.youtube_link);
                $('#edit_twitter_link').val(data.twitter_link);

                // Photo preview
                if (data.photo) {
                    $('#edit_current_photo').html(`<img src="${frontend}${data.photo}" class="img-fluid rounded shadow mt-2" alt="Photo" style="width: 100px;" />`);
                } else {
                    $('#edit_current_photo').html('<span class="text-muted">No photo uploaded</span>');
                }

                // Show the edit modal
                $('#editTeamMemberModal').modal('show');
            } else {
                alert('Failed to load team member details.');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error: ' + status + ' - ' + error);
            alert('An error occurred while fetching the team member details.');
        }
    });
});
// Delete button handler (Soft Delete)
$('#TeamMemberTable').on('click', '.delete-btn', function () {
    const id = $(this).data('id'); // Get the blog ID from the button's data-id attribute

    // Show the delete confirmation modal
    $('#deleteTeamMemberModal').modal('show');

    // Handle the confirm delete button click
    $('#confirmDeleteBtn').off('click').on('click', function () {
        $.ajax({
            url: frontend + controllerName + '/delete_team_member', // Ensure correct controller path
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    // Display success message using SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Team Member Deleted',
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
                $('#deleteTeamMemberModal').modal('hide');
            },
            error: function () {
                alert('Error while soft deleting the Team Member.');
                // Close the modal after the error
                $('#deleteTeamMemberModal').modal('hide');
            }
        });
    });
});


});
$('#editTeamMemberForm').on('submit', function (e) {
    e.preventDefault();

    let formData = new FormData(this); // Collect the form data, including the file
    $.ajax({
        url: frontend + controllerName + '/update_team_member', // Adjust this URL to your controller method
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        beforeSend: function () {
            // Clear previous errors
            $('#editTeamMemberForm small.text-danger').text('');
        },
        success: function (response) {
            if (response.status === 'success') {
                $('#editTeamMemberModal').modal('hide');
                $('#editTeamMemberForm')[0].reset();
                $('#TeamMemberTable').DataTable().ajax.reload(null, false); // Reload without resetting pagination
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
