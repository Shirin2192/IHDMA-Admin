ClassicEditor
.create(document.querySelector('#objectives'))
.catch(error => {
    console.error(error);
});

$('#ObjectivesForm').on('submit', function (e) {
    e.preventDefault();

    // Clear previous error messages
    $('.error-text').text('');

    var form = $('#ObjectivesForm')[0];
    var formData = new FormData(form);

    $.ajax({
        url: frontend + controllerName + "/update_objective",
        type: 'POST',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.status === 'success') {
               
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message,
                    // showConfirmButton: true,
                    timer: 2000, // Auto-close after 2 seconds (2000 ms)
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                $('#ObjectivesForm')[0].reset();
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
            }
        },
        error: function () {
            alert('An error occurred. Please try again.');
        }
    });
});

