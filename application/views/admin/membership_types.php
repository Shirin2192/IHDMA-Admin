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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Membership Types</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <form id="MembershipTypeForm">
                                    <h4 class="card-title">Add Membership Types</h4>
                                    <div class="form-validation">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label class="col-form-label" for="category_name">Select Category<span
                                                        class="text-danger">*</span></label>
                                                <select type="text" class="form-control chosen-select"
                                                    id="category_name" name="category_name"
                                                    placeholder="Enter Category Name"
                                                    data-placeholder="Select Category">
                                                    <option value=""></option>
                                                    <?php foreach($categories as $categories_key => $categories_row){ ?>
                                                    <option value="<?= $categories_row['id']?>">
                                                        <?= $categories_row['category_name']?></option>
                                                    <?php } ?>
                                                </select>
                                                <small class="text-danger" id="category_name_error"></small>

                                            </div>
                                            <div class="col-lg-6">
                                                <label class="col-form-label" for="type_name">Type Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="type_name" name="type_name"
                                                    placeholder="Enter Type Name">
                                                <small class="text-danger" id="type_name_error"></small>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="col-form-label" for="currency">Currency <span
                                                        class="text-danger">*</span></label>
                                                <select type="text" class="form-control chosen-select" id="currency" name="currency"
                                                   data-placeholder="Select Currency">
                                                   <option value=""></option>
                                                    <?php foreach($currencies as $currencies_key => $currencies_row){ ?>
                                                    <option value="<?= $currencies_row['id']?>">
                                                        <?= $currencies_row['code'] ." -  ". $currencies_row['symbol']?></option>
                                                    <?php } ?>
                                                </select>
                                                <small class="text-danger" id="currency_error"></small>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="col-form-label" for="price">Price <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="price" name="price"
                                                    placeholder="Enter Price">
                                                <small class="text-danger" id="price_error"></small>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="col-form-label" for="short_description">Short Description
                                                    <span class="text-danger">*</span></label>
                                                <textarea type="text" class="form-control" id="short_description"
                                                    name="short_description"
                                                    placeholder="Enter Short Description"></textarea>
                                                <small class="text-danger" id="short_description_error"></small>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="col-form-label" for="full_description">Full Description
                                                    <span class="text-danger">*</span></label>
                                                <textarea type="text" class="form-control" id="full_description"
                                                    name="full_description"
                                                    placeholder="Enter Short Description"></textarea>
                                                <small class="text-danger" id="full_description_error"></small>
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
                                <h4 class="card-title">Membership Types List</h4>
                                <div class="table-responsive">
                                    <table id="MemberTypeTable"
                                        class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>Sr. No</th>
                                                <th>Category Name</th>
                                                <th>Type Name</th>
                                                <th>Currency</th>
                                                <th>Price</th>
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
                        <h5 class="modal-title" id="viewMemberCategoryModalLabel">View Membership Type Details</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Title Section -->
                            <div class="col-md-6 form-group">
                                <label for="view_category_name" class="font-weight-bold">Category Name</label>
                                <p id="view_category_name" class="text-muted"></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="view_type_name" class="font-weight-bold">Type Name</label>
                                <p id="view_type_name" class="text-muted"></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="view_currency" class="font-weight-bold">Currency</label>
                                <p id="view_currency" class="text-muted"></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="view_price" class="font-weight-bold">Price</label>
                                <p id="view_price" class="text-muted"></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="view_short_description" class="font-weight-bold">Short Description</label>
                                <p id="view_short_description" class="text-muted"></p>
                            </div>
                            <!-- Slug Section -->
                            <div class="col-md-12 form-group">
                                <label for="view_full_description" class="font-weight-bold">Full Description</label>
                                <p id="view_full_description" class="text-muted"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editMemberTypeModal" tabindex="-1" role="dialog"
            aria-labelledby="editMemberTypeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMemberTypeModalLabel">Edit Membership Type</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editMemberTypeForm">
                            <input type="hidden" id="edit_membership_type_id" name="edit_membership_type_id">

                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="col-form-label" for="edit_category_name">Select Category<span
                                            class="text-danger">*</span></label>
                                    <select type="text" class="form-control chosen-select" id="edit_category_name"
                                        name="edit_category_name" data-placeholder="Enter Category Name">
                                        <option value=""></option>
                                        <?php foreach($categories as $categories_key => $categories_row){ ?>
                                                    <option value="<?= $categories_row['id']?>">
                                                        <?= $categories_row['category_name']?></option>
                                                    <?php } ?>
                                    </select>
                                    <small class="text-danger" id="edit_category_name_error"></small>

                                </div>
                                <div class="col-lg-6">
                                    <label class="col-form-label" for="edit_type_name">Type Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_type_name" name="edit_type_name"
                                        placeholder="Enter Type Name">
                                    <small class="text-danger" id="edit_type_name_error"></small>
                                </div>
                                <div class="col-lg-6">
                                    <label class="col-form-label" for="edit_currency">Currency <span
                                            class="text-danger">*</span></label>
                                    <select type="text" class="form-control chosen-select" id="edit_currency" name="edit_currency"
                                        placeholder="Enter Currency">
                                        <option value=""></option>
                                                    <?php foreach($currencies as $currencies_key => $currencies_row){ ?>
                                                    <option value="<?= $currencies_row['id']?>">
                                                        <?= $currencies_row['code'] ." -  ". $currencies_row['symbol']?></option>
                                                    <?php } ?>
                                    </select>
                                    <small class="text-danger" id="edit_currency_error"></small>
                                </div>
                                <div class="col-lg-6">
                                    <label class="col-form-label" for="edit_price">Price <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_price" name="edit_price"
                                        placeholder="Enter Price">
                                    <small class="text-danger" id="edit_price_error"></small>
                                </div>
                                <div class="col-lg-6">
                                    <label class="col-form-label" for="edit_short_description">Short Description <span
                                            class="text-danger">*</span></label>
                                    <textarea type="text" class="form-control" id="edit_short_description"
                                        name="edit_short_description" placeholder="Enter Short Description"></textarea>
                                    <small class="text-danger" id="edit_short_description_error"></small>
                                </div>
                                <div class="col-lg-6">
                                    <label class="col-form-label" for="edit_full_description">Full Description <span
                                            class="text-danger">*</span></label>
                                    <textarea type="text" class="form-control" id="edit_full_description"
                                        name="edit_full_description" placeholder="Enter Short Description"></textarea>
                                    <small class="text-danger" id="edit_full_description_error"></small>
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
        <div class="modal fade" id="deleteMemberTypeModal" tabindex="-1" role="dialog" aria-labelledby="deleteMemberTypeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteMemberTypeModalLabel">Confirm Soft Delete</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to soft delete this Member Type? This action cannot be undone.</p>
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
    <script src="<?= base_url()?>assets/view_js/membership_types.js"></script>
</body>

</html>