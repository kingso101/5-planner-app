<?php 
    require_once 'partials/header.inc.php'; 
?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4 col-md-5">
                        <div class="card card-user">
                            <div class="image">
                                <img src="assets/img/background.jpg" alt="..."/>
                            </div>
                            <div class="content" id="dataId" data-id="">
                                <div class="author">
                                  <img class="avatar border-white" src="assets/img/faces/face-2.jpg" alt="..."/>
                                  <h4 class="title fullname"><span id="fullname"></span><br />
                                     <a href="#"><small id="email"></small></a><br />
                                     <a href="#"><small id="contact_number"></small></a>
                                  </h4>
                                </div>
                                <p id="description" class="description text-center"></p>
                            </div>
                            <hr>
                            <div class="text-center">
                                <div class="row">
                                    <div class="col-md-3 col-md-offset-1">
                                        <h5><span id="country"></span><br /><small>Country</small></h5>
                                    </div>
                                    <div class="col-md-4">
                                        <h5><span id="state"></span><br /><small>State</small></h5>
                                    </div>
                                    <div class="col-md-3">
                                        <h5><span id="gender"></span><br /><small>Gender</small></h5>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="text-center">
                                <div class="row">
                                    <div class="col-md-3 col-md-offset-1">
                                        <h5>12<br /><small>Files</small></h5>
                                    </div>
                                    <div class="col-md-4">
                                        <h5>2GB<br /><small>Used</small></h5>
                                    </div>
                                    <div class="col-md-3">
                                        <h5>24,6$<br /><small>Spent</small></h5>
                                    </div>
                                </div>
                            </div> -->
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
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>First Name</label>
                                                <input type="text" class="form-control border-input" id="up_fname" name="up_fname" placeholder="First Name" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Last Name</label>
                                                <input type="text" class="form-control border-input" id="up_lname" name="up_lname" placeholder="Last Name" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Phone Number</label>
                                                <input type="text" class="form-control border-input" id="up_contact_number" name="up_contact_number" placeholder="Phone Number" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Gender</label>
                                                <select class="form-control border-input" id="up_gender" name="up_gender" >
                                                    <option value="" id="genderPill" selected></option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Email Address</label>
                                                <input type="email" class="form-control border-input" id="up_email" name="up_email" placeholder="Email" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Country</label>
                                                <input type="text" class="form-control border-input" id="up_country" name="up_country" placeholder="Country" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>State</label>
                                                <input type="text" class="form-control border-input" id="up_state" name="up_state" placeholder="State" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">DOB</label>
                                                <input type="date" class="form-control border-input" id="up_dob" name="up_dob" placeholder="Email">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>About Me</label>
                                                <textarea rows="2" class="form-control border-input" id="up_description" name="up_description" placeholder="Here can be your description" value=""></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <textarea rows="2" class="form-control border-input" id="up_address" name="up_address" placeholder="Here can be your address" value=""></textarea>
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
                                    console.log(data); 
                                    var firstname = data.firstname; var lastname = data.lastname; var fullname = firstname + " " +lastname;
                                    var email = data.email; var country = data.country.toUpperCase(); var state = data.state; var gender = data.gender; var dob = data.dob; var address = data.address; var description = data.description;
                                    var contact_number = data.contact_number; var isVerified = data.isVerified; var profile_img = data.profile_img; var created = data.created; var modified = data.modified;

                                    $("#fullname").html(fullname); $("#email").html(email); $("#contact_number").html(contact_number); $("#description").html(description); $("#country").text(country); $("#state").text(state); $("#genderPill").html(gender).val(gender); $("#gender").html(gender); $("#dataId").attr("data-id", _id);
                                });
                            }, first_interval);

                            setTimeout(function() {
                                $.getJSON("/api/client/read_single.php?_id=" + _id, function(data){
                                    // console.log(data); 
                                    var firstname = data.firstname; var lastname = data.lastname; var fullname = firstname + " " +lastname;
                                    var email = data.email; var country = data.country.toUpperCase(); var state = data.state; var gender = data.gender; var dob = data.dob; var address = data.address; var description = data.description;
                                    var contact_number = data.contact_number; var isVerified = data.isVerified; var profile_img = data.profile_img; var created = data.created; var modified = data.modified;

                                    $("#up_fname").val(firstname);$("#up_lname").val(lastname);$("#up_email").val(email);$("#up_contact_number").val(contact_number);$("#up_gender").val(gender);$("#up_country").val(country);$("#up_state").val(state);$("#up_dob").val(dob);$("#up_description").val(description);$("#up_address").val(address);$("#client_id").val(_id);
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
