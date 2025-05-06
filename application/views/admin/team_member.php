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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Team Member</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <form id="TeamMemberForm">
                                    <h4 class="card-title">Add Team Member</h4>
                                    <div class="form-validation">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label class="col-form-label" for="name">Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    placeholder="Enter Name">
                                                <small class="text-danger" id="name_error"></small>
                                            </div>

                                            <div class="col-lg-6">
                                                <label class="col-form-label" for="designation">Designation <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="designation"
                                                    name="designation" placeholder="Enter Designation">
                                                <small class="text-danger" id="designation_error"></small>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="col-form-label" for="description">Description <span
                                                        class="text-danger">*</span></label>
                                                <textarea type="text" class="form-control" id="description"
                                                    name="description" placeholder="Enter description"></textarea>
                                                <small class="text-danger" id="description_error"></small>
                                            </div>

                                            <div class="col-lg-6">
                                                <label class="col-form-label" for="photo">Photo <span
                                                        class="text-danger">*</span></label>
                                                <input type="file" class="form-control" id="photo" name="photo">
                                                <small class="text-danger" id="photo_error"></small>
                                            </div>

                                            <div class="col-lg-6">
                                                <label class="col-form-label" for="facebook_link">Facebook Link <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="facebook_link"
                                                    name="facebook_link" placeholder="Enter Facebook Link">
                                                <small class="text-danger" id="facebook_link_error"></small>
                                            </div>

                                            <div class="col-lg-6">
                                                <label class="col-form-label" for="linkedin_link">LinkedIn Link <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="linkedin_link"
                                                    name="linkedin_link" placeholder="Enter LinkedIn Link">
                                                <small class="text-danger" id="linkedin_link_error"></small>
                                            </div>

                                            <div class="col-lg-6">
                                                <label class="col-form-label" for="youtube_link">YouTube Link <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="youtube_link"
                                                    name="youtube_link" placeholder="Enter YouTube Link">
                                                <small class="text-danger" id="youtube_link_error"></small>
                                            </div>

                                            <div class="col-lg-6">
                                                <label class="col-form-label" for="twitter_link">Twitter Link <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="twitter_link"
                                                    name="twitter_link" placeholder="Enter Twitter Link">
                                                <small class="text-danger" id="twitter_link_error"></small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-3">
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
                                <h4 class="card-title">Team Member List</h4>
                                <div class="table-responsive">
                                    <table id="TeamMemberTable"
                                        class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>Sr. No</th>
                                                <th>Name</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Image</th>
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
        <div class="modal fade" id="viewMemberTypeModal" tabindex="-1" role="dialog"
            aria-labelledby="viewMemberCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewMemberCategoryModalLabel">View Team Member Details</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Title Section -->
                            <div class="col-md-6 form-group">
                                <label for="view_name" class="font-weight-bold">Name</label>
                                <p id="view_name" class="lead text-dark"></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="view_designation" class="font-weight-bold">Designation</label>
                                <p id="view_designation" class="lead text-dark"></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="view_description" class="font-weight-bold">Description</label>
                                <p id="view_description" class="lead text-dark"></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="view_facebook_link" class="font-weight-bold">Facebook Link</label>
                                <p id="view_facebook_link" class="lead text-dark"></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="view_linkedin_link" class="font-weight-bold">LinkedIn Link</label>
                                <p id="view_linkedin_link" class="lead text-dark"></p>
                            </div>
                            <!-- Slug Section -->
                            <div class="col-md-6 form-group">
                                <label for="view_youtube_link" class="font-weight-bold">YouTube Link</label>
                                <p id="view_youtube_link" class="text-muted"></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="view_twitter_link" class="font-weight-bold">Twitter Link</label>
                                <p id="view_twitter_link" class="text-muted"></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="view_photo" class="font-weight-bold">Photo</label>
                                <p id="view_photo" class="text-muted"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editTeamMemberModal" tabindex="-1" role="dialog"
            aria-labelledby="editTeamMemberModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTeamMemberModalLabel">Edit Membership Type</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editTeamMemberForm">
                            <input type="hidden" id="edit_member_id" name="edit_member_id">

                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="col-form-label" for="edit_name">Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_name" name="edit_name"
                                        placeholder="Enter Name">
                                    <small class="text-danger" id="edit_name_error"></small>
                                </div>

                                <div class="col-lg-6">
                                    <label class="col-form-label" for="edit_designation">Designation <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_designation" name="edit_designation"
                                        placeholder="Enter Designation">
                                    <small class="text-danger" id="edit_designation_error"></small>
                                </div>
                                <div class="col-lg-6">
                                    <label class="col-form-label" for="edit_description">Description <span
                                            class="text-danger">*</span></label>
                                    <textarea type="text" class="form-control" id="edit_description" name="edit_description"
                                        placeholder="Enter description"></textarea>
                                    <small class="text-danger" id="edit_description_error"></small>
                                </div>

                                <div class="col-lg-6">
                                    <label class="col-form-label" for="edit_photo">Photo <span
                                            class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="edit_photo" name="edit_photo">
                                    <small class="text-danger" id="edit_photo_error"></small>
                                    <div id="edit_current_photo" class="mt-2"></div> <!-- For existing photo preview -->
                                </div>

                                <div class="col-lg-6">
                                    <label class="col-form-label" for="edit_facebook_link">Facebook Link <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_facebook_link" name="edit_facebook_link"
                                        placeholder="Enter Facebook Link">
                                    <small class="text-danger" id="edit_facebook_link_error"></small>
                                </div>

                                <div class="col-lg-6">
                                    <label class="col-form-label" for="edit_linkedin_link">LinkedIn Link <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_linkedin_link" name="edit_linkedin_link"
                                        placeholder="Enter LinkedIn Link">
                                    <small class="text-danger" id="edit_linkedin_link_error"></small>
                                </div>

                                <div class="col-lg-6">
                                    <label class="col-form-label" for="edit_youtube_link">YouTube Link <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_youtube_link" name="edit_youtube_link"
                                        placeholder="Enter YouTube Link">
                                    <small class="text-danger" id="edit_youtube_link_error"></small>
                                </div>

                                <div class="col-lg-6">
                                    <label class="col-form-label" for="edit_twitter_link">Twitter Link <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_twitter_link" name="edit_twitter_link"
                                        placeholder="Enter Twitter Link">
                                    <small class="text-danger" id="edit_twitter_link_error"></small>
                                </div>
                            </div>                           
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
        <div class="modal fade" id="deleteTeamMemberModal" tabindex="-1" role="dialog" aria-labelledby="deleteBlogModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteBlogModalLabel">Confirm Delete</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to soft delete this Team Member? This action cannot be undone.</p>
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
    <script src="<?= base_url()?>assets/view_js/team_member.js"></script>
</body>

</html>