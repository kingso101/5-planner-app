$(document).ready(function(){
 
    // show html form when 'update plan' button was clicked
    $(document).on('click', '#updateModalBtn', function(){
        // get plan id
		var _id = $(this).attr('data-id');
		// read one record based on given plan id
		$.getJSON("/api/plan/read_single.php?_id=" + _id, function(data){
		    // $("#radio1").prop("checked", true);
		    $("#updatePlanModal").css("display", "block");
		    var plan_type = data.plan_type;
		    var goal = data.goal;
		    var from_date = data.from_date;
		    var to_date = data.to_date;
		    var description = data.description;
		    var resources = data.resources;
		    var plan_interval = data.plan_interval;
		    var reward = data.reward;
		    var priority_label = data.priority_label;
		    var priority_date = data.priority_date;
		    var isCompleted = data.isCompleted;
		    var created = data.created;
		    
		    // load list of categories
		    // store 'update plan' html to this variable
			var edit_one_plan_html=`
				<form id="editPlanForm">
					<div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Goal <i data-bs-toggle="tooltip" data-bs-placement="top" title="Please input goal for this plan." class="ti-help-alt ti-help-alt-pill"></i></label>
                                <input type="text" class="form-control border-input" id="up_goal" name="up_goal" placeholder="Goal" value="` + goal + `">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Plan Type <i data-bs-toggle="tooltip" data-bs-placement="top" title="Please choose plan type." class="ti-help-alt ti-help-alt-pill"></i></label>
                                <input type="text" class="form-control border-input" id="up_plan_type" name="up_plan_type" placeholder="Plan Type" value="` + plan_type + `">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>From <i data-bs-toggle="tooltip" data-bs-placement="top" title="Please input start date for this plan." class="ti-help-alt ti-help-alt-pill"></i></label>
                                <input type="date" class="form-control border-input" id="up_from_date" name="up_from_date" placeholder="From" value="` + from_date + `">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>To <i data-bs-toggle="tooltip" data-bs-placement="top" title="Please input end date for this plan." class="ti-help-alt ti-help-alt-pill"></i></label>
                                <input type="date" class="form-control border-input" id="up_to_date" name="up_to_date" value="` + to_date + `" placeholder="To">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description <i data-bs-toggle="tooltip" data-bs-placement="top" title="Please describe the reason for this goal." class="ti-help-alt ti-help-alt-pill"></i></label>
                                <textarea rows="5" class="form-control border-input" id="up_description" name="up_description" placeholder="Please describe the reason for this goal" value="` + description + `">` + description + `</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Resources <i data-bs-toggle="tooltip" data-bs-placement="top" title="Please enter skills or resources needed to achieve this goal" class="ti-help-alt ti-help-alt-pill"></i></label>
                                <textarea rows="5" class="form-control border-input" id="up_resources" name="up_resources" placeholder="Please enter skills or resources needed to achieve this goal" value="` + resources + `">` + resources + `</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        	<label style="float:left;">
                    			Priorities 
                    			<i data-bs-toggle="tooltip" data-bs-placement="top" title="Please select interval for this plan." class="ti-help-alt ti-help-alt-pill"></i>
                    		</label>
                            <div class="form-group">
                                <div class="tabs">
					                <label class="tab">
					                    <input type="radio" id="radio1" name="up_plan_int" class="tab-input" value="1">
					                    <div class="tab-box">1 Month</div>
					                </label>
					                <label class="tab">
					                    <input type="radio" id="radio6" name="up_plan_int" class="tab-input" value="6">
					                    <div class="tab-box">6 Months</div>
					                </label>
					                <label class="tab">
					                    <input type="radio" id="radio12" name="up_plan_int" class="tab-input" value="12">
					                    <div class="tab-box">Next Year</div>
					                </label>
					                <label class="tab">
					                    <input type="radio" id="radio24" name="up_plan_int" class="tab-input" value="24">
					                    <div class="tab-box">2 Years</div>
					                </label>
					                <label class="tab">
					                    <input type="radio" id="radio60" name="up_plan_int" class="tab-input" value="60">
					                    <div class="tab-box">5 Years</div>
					                </label>
					            </div>
					            <label class="tab" style="float:right;">
				                    <a id="editPlanIntBtn" href="javascript:void(0);" class="tab-box" data-id='` + data._id + `'>Edit</a>
				                </label>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="card" style="border:1px solid lightgrey;">
                        <div class="content">
                        	<div class="row">
		                        <div class="col-md-12">
		                            <label style="float:left;">
		                    			First Reward 
		                    			<i data-bs-toggle="tooltip" data-bs-placement="top" title="Please add reward for this plan." class="ti-help-alt ti-help-alt-pill"></i>
		                    		</label>
		                    		<div class="form-group">
		                                <input type="text" class="form-control border-input" id="up_reward" name="up_reward" placeholder="Please enter skills or resources needed to achieve this goal" value="` + reward + `">
		                            </div>
		                        </div>
		                    </div>
		                    <div class="row">
		                        <div class="col-md-12">
		                        	<div class="form-group">
		                                <label class="tab" style="float:right;">
						                    <a id="addPlanPriorityBtn" href="javascript:void(0);" class="tab-box addPlanPriorityBtn" data-id='` + data._id + `'>
						                    	<i data-bs-toggle="tooltip" data-bs-placement="top" title="Please add priority for this plan." class="ti-plus ti-help-alt-pill"></i>
						                    	Add Priorities
						                    </a>
						                </label>
		                            </div>
		                        </div>
		                    </div>
		                    <div style="border-top:1px solid lightgrey;width:100%;" class="clearfix"></div>
		                    <div class="row">
		                        <div class="col-md-12">
		                            <label id="labelForPriorityLabel" style="float:left;">` + priority_label + `</label>
		                            <label class="tab" style="float:right;margin-left:10px;">
					                    <a id="addPlanPriorityBtn" href="javascript:void(0);" class="tab-box addPlanPriorityBtn" data-id='` + data._id + `'><i class="ti-pencil"></i></a>
					                </label> 
		                            <label id="labelForPriorityDate" style="float:right;">` + priority_date + `</label>
		                        </div>
		                    </div>
		                    <div style="border-top:1px solid lightgrey;width:100%;" class="clearfix"></div><br><br>

		                    <input type="hidden" id="up_plan_int_id" name="up_plan_int_id" value="`+_id+`">
                        </div>
                    </div>
                    <div class="row" style="float:right;">
	                	<div class="text-center" style="display:inline-block;">
		                    <button type="button" class="btn btn-danger btn-fill updatePlanModalCloseBtn" style="background-color:#ec3b3b;color:#fff;">Close</button>
		                </div>
		                <div class="text-center" style="display:inline-block;">
		                    <button type="submit" name="subUpdate" id="subUpdate" style="background-color:#2478d3;color:#fff;" class="btn btn-info btn-fill">Save</button>
		                </div>
	                </div>
                    <div class="clearfix"></div>
                </form>`;

				// inject to 'page-content' of our app
				$("#update_div").html(edit_one_plan_html);
				
				if(plan_interval == 1){
		        	$('#radio1').prop('checked', true);
		        }
		        else if(plan_interval == 6){
			  		$('#radio6').prop('checked', true);
				}
		        else if(plan_interval == 12){
		          	$('#radio12').prop('checked', true);
		        }
		        else if(plan_interval == 24){
		          	$('#radio24').prop('checked', true);
		        }
		        else if(plan_interval == 60){
		          	$('#radio60').prop('checked', true);
		        }
			});
		});	
    });

	$(document).on('click', '#editPlanIntBtn', function(){
		var _id = $(this).attr('data-id');
		$.getJSON("/api/plan/read_single.php?_id=" + _id, function(data){
			// console.log(data);
			$("#updatePriorityModal").css("display", "block");
		    var edit_plan_int_html=`
		    <form id="editPlanIntForm">
                <div class="row">
                    <div class="col-md-12">
                    	<label style="float:left;">
                			Priorities 
                			<i class="ti-help-alt ti-help-alt-pill"></i>
                		</label>
                        <div class="form-group">
                            <div class="tabs">
				                <label class="tab">
				                    <input type="radio" id="up_radio1" name="up_plan_interval" class="tab-input" value="1">
				                    <div class="tab-box">1 Month</div>
				                </label>
				                <label class="tab">
				                    <input type="radio" id="up_radio6" name="up_plan_interval" class="tab-input" value="6">
				                    <div class="tab-box">6 Months</div>
				                </label>
				                <label class="tab">
				                    <input type="radio" id="up_radio12" name="up_plan_interval" class="tab-input" value="12">
				                    <div class="tab-box">Next Year</div>
				                </label>
				                <label class="tab">
				                    <input type="radio" id="up_radio24" name="up_plan_interval" class="tab-input" value="24">
				                    <div class="tab-box">2 Years</div>
				                </label>
				                <label class="tab">
				                    <input type="radio" id="up_radio60" name="up_plan_interval" class="tab-input" value="60">
				                    <div class="tab-box">5 Years</div>
				                </label>
				                <input type="hidden" id="up_plan_id" name="up_plan_id" value="`+_id+`">
				            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="row" style="float:right;">
                	<div class="text-center" style="display:inline-block;">
	                    <button type="button" class="btn btn-danger btn-fill updatePriorityModalCloseBtn" style="background-color:#ec3b3b;color:#fff;">Close</button>
	                </div>
	                <div class="text-center" style="display:inline-block;">
	                    <button type="submit" name="save" id="save" style="background-color:#2478d3;color:#fff;" class="btn btn-info btn-fill">Save</button>
	                </div>
                </div>
                <div class="clearfix"></div>
            </form>`;
	        $("#update_plan_int_div").html(edit_plan_int_html);
		});
	});

	// trigger when registration form is submitted
	$(document).on('submit', '#editPlanIntForm', function(e){
		e.preventDefault();
	 	var _id = $('#up_plan_id').val();
	 	var up_plan_interval = $('input:radio[name="up_plan_interval"]:checked').val(); 
	 	var isChecked = $('input:radio[name="up_plan_interval"]:checked').val()?true:false; 

        if (isChecked == "" || _id == "") {
            toastr.error('Form inputs cannot be empty!');
        }else{
        	// get form data
		    var obj = { "up_plan_interval":up_plan_interval, "_id":_id };
		    var form_data = JSON.stringify(obj);
		 	// alert(form_data);
		 	// submit form data to api
		    $.ajax({
		        url: "../../../api/plan/update-annex.php",
		        type : "PUT",
		        dataType: 'json',
		        contentType : 'application/json',
		        data : form_data,
		        beforeSend: function(){
                    setTimeout(function () {
				        $('#save').html('Loading...');
				    }, 100); 
                },
		        success : function(result) {
		        	// alert(result.message);
		        	setTimeout(function(){
		            	toastr.success(result.message);
		            	$('#save').delay(3000).html('Save');
					   	// window.location.reload(true);
					}, 4000);
		        },
		        error: function(xhr, resp, text){
		            // on error, tell the user sign up failed
		            setTimeout(function () {
		            	toastr.error("Unable to update plan interval.");
				        $('#save').html('Save');
				    }, 4000); 
		        }
		    });
		    return false;
        }
	});

	// trigger when registration form is submitted
	$(document).on('submit', '#editPlanForm', function(e){
		e.preventDefault();
	 	var plan_id = $('#up_plan_int_id').val();
	 	var up_goal = $('#up_goal').val();
	 	var up_plan_type = $('#up_plan_type').val();
        var up_from_date = $('#up_from_date').val();
        var up_to_date = $('#up_to_date').val();
        var up_description = $('#up_description').val();
        var up_resources = $('#up_resources').val();
        var up_reward = $('#up_reward').val();
        var up_plan_int = $('input:radio[name="up_plan_int"]:checked').val(); 
        var isChecked = $('input:radio[name="up_plan_int"]:checked').val()?true:false; 
        // alert(up_profile_img_base64);
        // alert(plan_id);

        if (up_goal == "" || up_plan_type == "" || up_from_date == "" || up_to_date == "" || up_description == "" || up_resources == "" || isChecked == "" || up_reward == "" || plan_id == "") {
            // $('#errorAlert').slideDown(300).delay(5000).slideUp(300).html("Form inputs cannot be empty.");
            toastr.error('Form inputs cannot be empty!');
        }else{
        	// get form data
		    var obj = { "up_goal":up_goal, "up_plan_type":up_plan_type, "up_from_date":up_from_date, "up_to_date":up_to_date, "up_description":up_description, "up_resources":up_resources, "up_plan_int":up_plan_int, "up_reward":up_reward, "_id":plan_id };
		    var form_data = JSON.stringify(obj);
		 	// submit form data to api
		    $.ajax({
		        url: "../../../api/plan/update.php",
		        type : "PUT",
		        dataType: 'json',
		        contentType : 'application/json',
		        data : form_data,
		        beforeSend: function(){
                    setTimeout(function () {
				        $('#subUpdate').html('Loading...');
				    }, 100); 
                },
		        success : function(result) {
		        	setTimeout(function(){
		            	toastr.success(result.message);
		            	$('#subUpdate').delay(3000).html('Save');
					   	// window.location.reload(true);
					}, 4000);
		        },
		        error: function(xhr, resp, text){
		            // on error, tell the user sign up failed
		            setTimeout(function () {
		            	toastr.error("Unable to update plan");
				        $('#subUpdate').html('Save');
				    }, 4000); 
		        }
		    });
		    return false;
        }


		// function to make form values to json format
		$.fn.serializeObject = function(){
		 
		    var o = {};
		    var a = this.serializeArray();
		    $.each(a, function() {
		        if (o[this.name] !== undefined) {
		            if (!o[this.name].push) {
		                o[this.name] = [o[this.name]];
		            }
		            o[this.name].push(this.value || '');
		        } else {
		            o[this.name] = this.value || '';
		        }
		    });
		    return o;
		};

});