<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <!-- theme meta -->
    <meta name="theme-name" content="quixlab" />

    <title>IHDMA - Export Member</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <!-- Pignose Calender -->
    <?php include('common/css_files.php');?>

</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->


    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <?php include('common/nav_header.php');?>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        <?php include('common/header.php');?>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <?php include('common/sidebar.php');?>
        <!--**********************************
            Sidebar end
        ***********************************-->

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">

            <div class="row page-titles mx-0">
                <div class="col p-md-0">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Export Member List</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Member List</h4>
                                <div class="table-responsive">
                                    <table id="memberDataTable" class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>Sr. No</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Contact No</th>
                                                <th>Membership Type</th>
                                                <th>Action</th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Tiger Nixon</td>
                                                <td>System Architect</td>
                                                <td>Edinburgh</td>
                                                <td>61</td>
                                              
                                                <td>

                                                    <a href="javascript:void(0)" class="btn btn-primary">Edit</a>
                                                    <a href="javascript:void(0)" class="btn btn-danger">Delete</a>
                                            </td>
                                            </tr>
                                           
                                        </tbody>
                                        
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- #/ container -->
         <!-- Modal Popup -->
          <!-- Modal Popup -->
        <!-- View Blog Details Modal -->
        <div class="modal fade" id="viewMemberModal" tabindex="-1" role="dialog" aria-labelledby="viewMemberModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewMemberModalLabel">View Blog Details</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Title Section -->
                        <div class="form-group">
                            <label for="view_title" class="font-weight-bold">Title</label>
                            <p id="view_title" class="lead text-dark">Loading...</p>
                        </div>

                        <!-- Slug Section -->
                        <div class="form-group">
                            <label for="view_slug" class="font-weight-bold">Slug</label>
                            <p id="view_slug" class="text-muted">Loading...</p>
                        </div>

                        <!-- Content Section -->
                        <div class="form-group">
                            <label for="view_content" class="font-weight-bold">Content</label>
                            <p id="view_content" class="text-justify">Loading...</p>
                        </div>

                        <!-- Featured Image Section -->
                        <div class="form-group">
                            <label for="view_featured_image" class="font-weight-bold">Featured Image</label>
                            <div class="text-center">
                                <img id="view_featured_image" src="" alt="Featured Image"
                                    class="img-fluid rounded shadow-sm" style="max-height: 400px; max-width: 100%;">
                            </div>
                        </div>

                        <!-- Status Section -->
                        <div class="form-group">
                            <label for="view_status" class="font-weight-bold">Status</label>
                            <p id="view_status" class="badge badge-info">Loading...</p>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="editMemberModal" tabindex="-1" role="dialog" aria-labelledby="editMemberModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMemberModalLabel">Edit Blog</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editBlogForm" enctype="multipart/form-data">
                            <input type="hidden" id="edit_blog_id" name="edit_blog_id">

                            <div class="row">
                                <!-- Title -->
                                <div class="form-group col-md-6">
                                    <label for="edit_title" class="font-weight-bold">Title</label>
                                    <input type="text" class="form-control" id="edit_title" name="edit_title"
                                        placeholder="Enter Title">
                                    <small class="text-danger" id="edit_title_error"></small>
                                </div>

                                <!-- Slug -->
                                <div class="form-group col-md-6">
                                    <label for="edit_slug" class="font-weight-bold">Slug</label>
                                    <input type="text" class="form-control" id="edit_slug" name="edit_slug"
                                        placeholder="Enter Slug">
                                    <small class="text-danger" id="edit_slug_error"></small>
                                </div>
                            </div>

                            <div class="form-group">
                                <!-- Content (full width) -->
                                <label for="edit_content" class="font-weight-bold">Content</label>
                                <textarea class="form-control" id="edit_content" name="edit_content" rows="5"
                                    placeholder="Enter Content"></textarea>
                                <small class="text-danger" id="edit_content_error"></small>
                            </div>

                            <!-- Featured Image Preview -->
                            <div class="form-group text-center">
                                <label for="current_featured_image" class="font-weight-bold">Current Featured
                                    Image</label><br>
                                <img id="current_featured_image" src="" alt="Featured Image"
                                    class="img-fluid rounded shadow-sm" style="max-height: 200px; display: none;">
                            </div>

                            <div class="row">
                                <!-- Featured Image Upload -->
                                <div class="form-group col-md-6">
                                    <label for="edit_featured_image" class="font-weight-bold">Featured Image</label>
                                    <input type="file" class="form-control" id="edit_featured_image"
                                        name="edit_featured_image">
                                    <small class="text-danger" id="edit_featured_image_error"></small>
                                </div>

                                <!-- Status -->
                                <div class="form-group col-md-6">
                                    <label for="edit_status" class="font-weight-bold">Status</label>
                                    <select class="form-control" id="edit_status" name="edit_status">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                    <small class="text-danger" id="edit_status_error"></small>
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <!-- For Bootstrap 4 -->
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                <button type="submit" class="btn btn-primary" form="editBlogForm">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteMemberModal" tabindex="-1" role="dialog" aria-labelledby="deleteMemberModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteMemberModalLabel">Confirm Soft Delete</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to soft delete this blog? This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Confirm Delete</button>
                    </div>
                </div>
            </div>
        </div>
          
    </div>
    <!--**********************************
            Content body end
        ***********************************-->


    <!--**********************************
            Footer start
        ***********************************-->
    <div class="footer">
        <div class="copyright">
            <p>Copyright &copy; Designed & Developed by <a href="https://themeforest.net/user/quixlab">Quixlab</a>
                2018</p>
        </div>
    </div>
    <!--**********************************
            Footer end
        ***********************************-->
    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <?php include('common/js_files.php');?>
    <script src="<?= base_url()?>assets/view_js/member.js"></script>

</body>

</html>