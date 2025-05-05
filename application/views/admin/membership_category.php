<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <!-- theme meta -->
    <meta name="theme-name" content="quixlab" />

    <title>IHDMA - Dashboard</title>
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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Membership Category</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <form id="CategoryForm">
                                    <h4 class="card-title">Add Membership Category</h4>
                                    <div class="form-validation">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label class="col-form-label" for="category_name">Category Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="category_name"
                                                    name="category_name" placeholder="Enter Category Name">
                                                    <small id="category_name_error" class="text-danger"></small>

                                            </div>
                                            <div class="col-lg-6">
                                                <label class="col-form-label" for="description">Description <span
                                                        class="text-danger">*</span></label>
                                                <textarea type="text" class="form-control" id="description"
                                                    name="description" placeholder="Enter Description"></textarea>
                                                <small class="text-danger" id="description_error"></small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-8 ml-auto">
                                            <button type="submit" class="btn btn-success button_submit">Submit</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Membership Category List</h4>
                                <div class="table-responsive">
                                    <table id="MemberCategoryTable"
                                        class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>Sr. No</th>
                                                <th>Category Name</th>
                                                <th>Description</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
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
        <!-- View Blog Details Modal -->
        <div class="modal fade" id="viewMemberCategoryModal" tabindex="-1" role="dialog" aria-labelledby="viewMemberCategoryModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewMemberCategoryModalLabel">View Category Details</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="col-form-label" for="view_category_name">Category Name</label>
                                <p id="view_category_name" class="lead"></p>
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label" for="view_description">Description</label>
                                <p id="view_description" class="lead"></p>
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editMembershipCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editMembershipCategoryModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMembershipCategoryModalLabel">Edit Category Details</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editMemberCategoryForm">
                            <input type="hidden" id="edit_category_id" name="edit_category_id">

                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="col-form-label" for="edit_category_name">Category Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_category_name" name="edit_category_name"
                                        placeholder="Enter Category Name">
                                    <small class="text-danger" id="edit_category_name_error"></small>

                                </div>
                                <div class="col-lg-6">
                                    <label class="col-form-label" for="edit_description">Description <span
                                            class="text-danger">*</span></label>
                                    <textarea type="text" class="form-control" id="edit_description" name="edit_description"
                                        placeholder="Enter Description"></textarea>
                                    <small class="text-danger" id="edit_description_error"></small>
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <!-- For Bootstrap 4 -->
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteCategoryModal" tabindex="-1" role="dialog" aria-labelledby="deleteCategoryModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteCategoryModalLabel">Confirm Soft Delete</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to soft delete this Category? This action cannot be undone.</p>
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
            <p>Copyright &copy; Designed & Developed by <a href="javascript:void(0);">IHDMA</a>
                </p>
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
    <script src="<?= base_url()?>assets/view_js/category.js"></script>
</body>

</html>