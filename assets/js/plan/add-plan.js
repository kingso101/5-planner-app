$(document).ready(function(){
 
    // show html form when 'update plan' button was clicked
    $(document).on('click', '#add_plan_modal', function(){
		// display the modal box
		$("#addPlanModal").css("display", "block");
		var client_id = $(this).attr('data-id');
	    
	    // load list of categories
	    // store 'update plan' html to this variable
		var add_plan_html=`
			<form id="addPlanForm">
				<div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Goal <i class="ti-help-alt ti-help-alt-pill"></i></label>
                            <input type="text" class="form-control border-input" id="goal" name="goal" placeholder="Type Goal...">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Plan Type <i class="ti-help-alt ti-help-alt-pill"></i></label>
                            <input type="text" class="form-control border-input" id="plan_type" name="plan_type" placeholder="Choose Plan Type">
                        </div>
                    </div>
                </div>
                <input type="hidden" id="client_id" name="client_id" value="`+client_id+`">
                <input type="hidden" id="add_key_id" name="add_key_id" value="">
                <div class="row">
                    <div class="col-md-6">
                    	<label style="float:left">Plan Period <i class="ti-help-alt ti-help-alt-pill"></i></label>
                        <div class="form-group">
                            <label style="float:right">From <i class="ti-help-alt ti-help-alt-pill"></i></label>
                            <input type="date" class="form-control border-input" id="from_date" name="from_date" placeholder="From">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label style="float:right">To <i class="ti-help-alt ti-help-alt-pill"></i></label>
                            <input type="date" class="form-control border-input" id="to_date" name="to_date" placeholder="To">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Description <i class="ti-help-alt ti-help-alt-pill"></i></label>
                            <textarea rows="5" class="form-control border-input" id="description" name="description" placeholder="Please describe the reason for this goal"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Resources <i class="ti-help-alt ti-help-alt-pill"></i></label>
                            <textarea rows="5" class="form-control border-input" id="resources" name="resources" placeholder="Please enter skills or resources needed to achieve this goal"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    	<label style="float:left;">
                			Priorities 
                			<i class="ti-help-alt ti-help-alt-pill"></i>
                		</label>
                        <div class="form-group">
                            <div class="tabs">
				                <label class="tab">
				                    <input type="radio" id="radio-a1" name="plan_int_new" class="tab-input" value="1">
				                    <div class="tab-box">1 Month</div>
				                </label>
				                <label class="tab">
				                    <input type="radio" id="radio-a6" name="plan_int_new" class="tab-input" value="6">
				                    <div class="tab-box">6 Months</div>
				                </label>
				                <label class="tab">
				                    <input type="radio" id="radio-a12" name="plan_int_new" class="tab-input" value="12">
				                    <div class="tab-box">Next Year</div>
				                </label>
				                <label class="tab">
				                    <input type="radio" id="radio-a24" name="plan_int_new" class="tab-input" value="24">
				                    <div class="tab-box">2 Years</div>
				                </label>
				                <label class="tab">
				                    <input type="radio" id="radio-a60" name="plan_int_new" class="tab-input" value="60">
				                    <div class="tab-box">5 Years</div>
				                </label>
				            </div>
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
	                    			<i class="ti-help-alt ti-help-alt-pill"></i>
	                    		</label>
	                    		<div class="form-group">
	                                <input type="text" class="form-control border-input" id="reward" name="reward" placeholder="Please enter skills or resources needed to achieve this goal">
	                            </div>
	                        </div>
	                    </div>
	                    <div class="row">
	                        <div class="col-md-12">
	                        	<div class="form-group">
	                                <label class="tab" style="float:right;">
					                    <a id="addNewPlanPriorityBtn" data-id="`+client_id+`" href="javascript:void(0);" class="tab-box addNewPlanPriorityBtn" >
					                    	<i id="ti-plus" class="ti-plus ti-help-alt-pill"></i>
					                    	Add Priorities
					                    </a>
					                </label>
	                            </div>
	                        </div>
	                    </div>
	                    
	                    <div style="border-top:1px solid lightgrey;width:100%;" class="clearfix"></div>
	                    <div id="addNewPriorityModal" class="card none">
		                    <div class="header">
		                        <h4 class="title">New Priority</h4>
		                    </div>
		                    <div class="content" id="add_new_plan_priority_div">
		                    	<div class="row">
				                    <div class="col-md-12">
				                        <div class="form-group">
				                            <label>Priority:</label>
				                            <textarea rows="5" class="form-control border-input" id="add_new_priority_label" name="add_new_priority_label" placeholder="Priority Label"></textarea>
				                        </div>
				                    </div>
				                </div>
				                <div class="row">
				                    <div class="col-md-12">
				                        <div class="form-group">
				                            <label>Date:</label>
				                            <input type="date" class="form-control border-input" id="add_new_priority_date" name="add_new_priority_date" placeholder="Priority Date">
				                        </div>
				                    </div>
				                </div>
				                <i class="tab" style="float:right;">
				                    Note: You will receive an email on this date reminding you of this priority
				                </i><br>
				                <div class="clearfix"></div>
		                    </div>
		                </div>
	                    <div style="border-top:1px solid lightgrey;width:100%;" class="clearfix"></div><br><br>
                    </div>
                </div>
                <div class="row" style="float:right;">
                	<div class="text-center" style="display:inline-block;">
	                    <button type="button" class="btn btn-danger btn-fill addPlanModalCloseBtn" style="background-color:#ec3b3b;color:#fff;">Close</button>
	                </div>
	                <div class="text-center" style="display:inline-block;">
	                    <button type="submit" name="addPlan" id="addPlan" style="background-color:#2478d3;color:#fff;" class="btn btn-info btn-fill">Save</button>
	                </div>
                </div>
                <div class="clearfix"></div>
            </form>`;
			// inject to 'page-content' of our app
			$("#add_div").html(add_plan_html);
		});
    });
	
	// Add new priority label
	$(document).on('click', '#addNewPlanPriorityBtn', function(){
		setTimeout(function () {
	        // $("#addNewPriorityModal").toggle("slow");
	        $("#addNewPriorityModal").show("slow");
			$("#ti-plus").toggleClass("ti-minus ti-plus");
			// $("#ti-plus").addClass('ti-minus');
	    }, 5000); 
	});

	// trigger when registration form is submitted
	$(document).on('submit', '#addPlanForm', function(e){
		e.preventDefault();
	 	var goal = $('#goal').val();
	 	var client_id = $('#client_id').val();
	 	var key_id = $('#add_key_id').val();
	 	var plan_type = $('#plan_type').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var description = $('#description').val();
        var resources = $('#resources').val();
        var reward = $('#reward').val();
        var plan_int_new = $('input:radio[name="plan_int_new"]:checked').val(); 
        var priority_label = $('#add_new_priority_label').val();
        var priority_date = $('#add_new_priority_date').val();
        var isChecked = $('input:radio[name="plan_int_new"]:checked').val()?true:false; 

        // var check = $( "#ti-plus" ).hasClass( "block" ).toString();
        // alert("Hmmmm "+check);
        if (goal == "" || plan_type == "" || from_date == "" || to_date == "" || description == "" || resources == "" || isChecked == "" || reward == "" || priority_label == "" || priority_date == "" || client_id == "" || key_id == "" ) {
            toastr.error('Form inputs cannot be empty!');
        }else{
        	// get form data
		    var obj = { "goal":goal, "plan_type":plan_type, "from_date":from_date, "to_date":to_date, "description":description, "resources":resources, "plan_interval":plan_int_new, "reward":reward, "priority_label":priority_label, "priority_date":priority_date, "client_id":client_id, "key_id":key_id  };
		    var form_data = JSON.stringify(obj);
		 	// submit form data to api
		 	// alert(form_data);
		    $.ajax({
		        url: "../../../api/plan/create.php",
		        type : "PUT",
		        dataType: 'json',
		        contentType : 'application/json',
		        data : form_data,
		        beforeSend: function(){
                    setTimeout(function () {
				        $('#addPlan').html('Loading...');
				    }, 100); 
                },
		        success : function(result) {
		        	setTimeout(function(){
		            	toastr.success(result.message);
		            	$('#addPlan').delay(3000).html('Save');
		            	$("#addPlanForm")[0].reset();
					}, 4000);
		        },
		        error: function(error){
		            // on error, tell the user sign up failed
		            setTimeout(function () {
		            	toastr.error("Unable to create plan");
				        $('#addPlan').html('Save');
				        $("#addPlanForm")[0].reset();
				    }, 4000); 
				    console.log(error);
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