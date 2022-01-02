<?php 
    require_once 'partials/header.inc.php'; 
?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4 col-md-5">
                        <div class="card card-user">
                            <div class="content" id="dataId" data-id="">
                                <div class="author">
                                  <img class="avatar" src="" />
                                  <h4 class="title fullname"><span id="fullname"></span><br />
                                     <a href="javascript:void(0);"><small id="email"></small></a>
                                  </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-7">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Edit Profile</h4>
                            </div>
                            <div class="content">
                                <form id="profileDetailsForm">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>First Name</label>
                                                <input type="text" class="form-control border-input" id="up_fname" name="up_fname" placeholder="First Name" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Last Name</label>
                                                <input type="text" class="form-control border-input" id="up_lname" name="up_lname" placeholder="Last Name" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Email Address</label>
                                                <input type="email" class="form-control border-input" id="up_email" name="up_email" placeholder="Email" value="">
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" id="client_id" name="client_id">
                                    <div class="text-center">
                                        <button type="submit" id="subProfile" name="subProfile" class="btn btn-info btn-fill btn-wd">Update Profile</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    $(document).ready(function(){
                        var first_interval = 2000;
                        var second_interval = 2000;
                        var _id = "<?= $_id; ?>";
                        $(function() {
                            setInterval(function() {
                                $.getJSON("/api/client/read_single.php?_id=" + _id, function(data){
                                    var firstname = data.firstname; var lastname = data.lastname; var fullname = firstname + " " +lastname; var email = data.email; var created = data.created; var modified = data.modified;

                                    $("#fullname").html(fullname); $("#email").html(email); $("#dataId").attr("data-id", _id);
                                });
                            }, first_interval);

                            setTimeout(function() {
                                $.getJSON("/api/client/read_single.php?_id=" + _id, function(data){
                                    // console.log(data); 
                                    var firstname = data.firstname; var lastname = data.lastname; var fullname = firstname + " " +lastname; var email = data.email; var created = data.created; var modified = data.modified;

                                    $("#up_fname").val(firstname);$("#up_lname").val(lastname);$("#up_email").val(email);$("#client_id").val(_id);
                                });
                            }, second_interval);
                        });
                        
                    });
                </script>
            </div>
        </div>


<?php 
    require_once 'partials/footer.inc.php'; 
?>
