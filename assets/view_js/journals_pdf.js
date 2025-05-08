$('#JournalPdfForm').on('submit', function (e) {
    e.preventDefault();

    // Clear previous error messages
    $('.error-text').text('');

    var form = $('#JournalPdfForm')[0];
    var formData = new FormData(form);

    $.ajax({
        url: frontend + controllerName + "/save_journal_pdf",
        type: 'POST',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.status === 'success') {
                $('#JournalPDFsTable').DataTable().ajax.reload(null, false); // Reload without resetting pagination
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message,
                    // showConfirmButton: true,
                    timer: 2000, // Auto-close after 2 seconds (2000 ms)
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                $('#JournalPdfForm')[0].reset();
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
                if (response.upload_error) {
                    $('#banner_error').text(response.upload_error);

                    // Hide the upload error message after 5 seconds
                    setTimeout(function() {
                        $('#banner_error').text('');
                    }, 5000);
                }
            }
        },
        error: function () {
            alert('An error occurred. Please try again.');
        }
    });
});
$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('#JournalPDFsTable')) {
        $('#JournalPDFsTable').DataTable().clear().destroy();
    }
    
    const table = $('#JournalPDFsTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: frontend + controllerName + '/journal_pdf_data_on_datatable',
            type: 'POST',

        },
        columns: [
            { data: 'id' },
            { data: 'title' },
            // remove or replace the invalid blogs column
          
            {
                data: 'file_path',
                render: function (data) {
                    if (data) {
                        return '<a href="' + frontend + 'uploads/journal_pdfs/' + data + '" target="_blank">View PDF</a>';
                    } else {
                        return 'No file';
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
    $('#JournalPDFsTable').on('click', '.view-btn', function () {
        const id = $(this).data('id'); // Get the journal PDF ID
    
        // AJAX call to get the journal PDF data by ID
        $.ajax({
            url: frontend + controllerName + '/journal_pdf_data_on_id',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const data = response.data;
    
                    // Set title
                    $('#view_title').text(data.title);
    
                    // Set file path link
                    if (data.file_path) {
                        const pdfUrl = frontend + 'uploads/journal_pdfs/' + data.file_path;
                        $('#view_pdfs_link')
                            .attr('href', pdfUrl)
                            .text('View PDF')
                            .show();
                    } else {
                        $('#view_pdfs_link')
                            .attr('href', '#')
                            .text('No PDF Available')
                            .hide(); // Hide if no file
                    }
    
                    // Show modal
                    $('#viewJournalPdfsModal').modal('show');
                } else {
                    alert('Failed to load journal PDF details.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                alert('An error occurred while fetching the journal PDF details.');
            }
        });
    });
    
   // Edit button handler
   $('#JournalPDFsTable').on('click', '.edit-btn', function () {
    const id = $(this).data('id'); // Get the journal PDF ID

    // Make AJAX request to fetch journal PDF details
    $.ajax({
        url: frontend + controllerName + '/journal_pdf_data_on_id',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                const pdfData = response.data;

                // Populate the modal form fields
                $('#edit_pdf_id').val(pdfData.id);
                $('#edit_title').val(pdfData.title);
                $('#edit_current_pdf').val(pdfData.file_path); // hidden input to store existing file name

                // Show preview or download link for current PDF
                if (pdfData.file_path) {
                    const fileUrl = frontend + 'uploads/journal_pdfs/' + pdfData.file_path;
                    $('#current_pdf_link')
                        .attr('href', fileUrl)
                        .text('View Current PDF')
                        .show();
                } else {
                    $('#current_pdf_link')
                        .hide();
                }

                // Show the modal
                $('#editJournalPdfsModal').modal('show');
            } else {
                alert('Failed to load journal PDF details.');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
            alert('An error occurred while fetching the journal PDF details.');
        }
    });
});


    

    // Delete button handler (Soft Delete)
// Delete button handler (Soft Delete)
$('#JournalPDFsTable').on('click', '.delete-btn', function () {
    const id = $(this).data('id'); // Get the blog ID from the button's data-id attribute

    // Show the delete confirmation modal
    $('#deleteJournalPdfsModal').modal('show');

    // Handle the confirm delete button click
    $('#confirmDeleteBtn').off('click').on('click', function () {
        $.ajax({
            url: frontend + controllerName + '/delete_journal_pdf', // Ensure correct controller path
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    // Display success message using SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Journal PDFs Deleted',
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
                $('#deleteJournalPdfsModal').modal('hide');
            },
            error: function () {
                alert('Error while soft deleting the blog.');
                // Close the modal after the error
                $('#deleteJournalPdfsModal').modal('hide');
            }
        });
    });
});


});
$('#editpdfsForm').on('submit', function (e) {
    e.preventDefault();

    let formData = new FormData(this); // Collect the form data, including the file
    $.ajax({
        url: frontend + controllerName + '/update_journal_pdf', // Adjust this URL to your controller method
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        beforeSend: function () {
            // Clear previous errors
            $('#editpdfsForm small.text-danger').text('');
        },
        success: function (response) {
            if (response.status === 'success') {
                $('#editJournalPdfsModal').modal('hide');
                $('#editpdfsForm')[0].reset();
                $('#current_featured_image').hide();
                // Refresh the table if needed
                $('#JournalPDFsTable').DataTable().ajax.reload(null, false); // Reload without resetting pagination
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
                // Display validation errors
                if (response.errors) {
                    $.each(response.errors, function (key, value) {
                        $('#edit_' + key + '_error').text(value);
                    });
                } else {
                    alert('Something went wrong. Please try again.');
                }
            }
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error:', status, error);
            alert('An unexpected error occurred.');
        }
    });
});
