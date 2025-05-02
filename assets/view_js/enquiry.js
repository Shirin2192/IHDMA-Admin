if ($.fn.DataTable.isDataTable('#blogsTable')) {
    $('#blogsTable').DataTable().clear().destroy();
}

const table = $('#blogsTable').DataTable({
    processing: true,
    serverSide: false,
    ajax: {
        url: frontend + controllerName + '/blogs_data_on_datatable',
        type: 'POST',

    },
    columns: [
        { data: 'id' },
        { data: 'title' },
        // remove or replace the invalid blogs column
        { data: 'slug' },
        { data: 'content' },
        {
            data: 'featured_image',
            render: function (data) {
                return '<img src="' + frontend + 'uploads/blogs/' + data + '" alt="Featured Image" width="50" height="50" />';
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