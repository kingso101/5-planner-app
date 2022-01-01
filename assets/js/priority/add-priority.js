$(document).ready(function(){
	// Add new priority label
	$(document).on('click', '.addNewPlanPriorityBtn', function(){
		// alert('Hey you and me');
		var client_id = $(this).attr('data-id');
		$("#addPriorityModal").css("display", "block");
		var key_id = generate();
	    var add_plan_priority_html=`
	    <form id="addPriorityForm">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Priority:</label>
                        <textarea rows="5" class="form-control border-input" id="add_priority_label" name="add_priority_label" placeholder="Priority Label" value=""></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Date:</label>
                        <input type="date" class="form-control border-input" id="add_priority_date" name="add_priority_date" placeholder="Priority Date" value="">
                    </div>
                </div>
            </div>
            <i class="tab" style="float:right;">
                Note: You will receive an email on this date reminding you of this priority
            </i><br>
            <input type="hidden" id="key_id" name="key_id" value="`+key_id+`">
            <input type="hidden" id="client_id" name="client_id" value="`+client_id+`">
            <div class="clearfix"></div>
            <div class="clearfix"></div>
            <div class="row" style="float:right;">
            	<div class="text-center" style="display:inline-block;">
                    <button type="button" class="btn btn-danger btn-fill addPriorityModalCloseBtn" style="background-color:#ec3b3b;color:#fff;">Close</button>
                </div>
                <div class="text-center" style="display:inline-block;">
                    <button type="submit" name="add_new" id="add_new" style="background-color:#2478d3;color:#fff;" class="btn btn-info btn-fill">Save</button>
                </div>
            </div>
            <div class="clearfix"></div>
        </form>`;
        $("#add_plan_priority_div").html(add_plan_priority_html);
	});
	
	// Add priority label and date
	$(document).on('submit', '#addPriorityForm', function(e){
		e.preventDefault();
	 	var key_id = $('#key_id').val();
	 	var client_id = $('#client_id').val();
	 	var priority_label = $('#add_priority_label').val();
	 	var priority_date = $('#add_priority_date').val();

        if (priority_label == "" || priority_date == "" || client_id == "" || key_id == "") {
            toastr.error('Form inputs cannot be empty!');
        }else{
        	// get form data
		    var obj = { "priority_label":priority_label, "priority_date":priority_date, "client_id":client_id, "key_id":key_id };
		    var form_data = JSON.stringify(obj);
		 	// alert(form_data);
		 	// submit form data to api
		    $.ajax({
		        url: "../../../api/priority/create.php",
		        type : "PUT",
		        dataType: 'json',
		        contentType : 'application/json',
		        data : form_data,
		        beforeSend: function(){
                    setTimeout(function () {
				        $('#add_new').html('Loading...');
				    }, 100); 
                },
		        success : function(result) {
		        	// alert(result.message);
		        	setTimeout(function(){
		        		if (result.status == false) {
		        			toastr.error(result.message);
		        		}else{
		        			toastr.success(result.message);
		        			$('#add_new_priority_label').val(priority_label);
					        $('#add_new_priority_date').val(priority_date);
					        $('#add_key_id').val(key_id);
					        $('#addPriorityModal').css("display", "none");
						}
		        		$('#add_new').delay(3000).html('Save');
		        		$("#addPriorityForm")[0].reset();
					}, 4000);
		        },
		        error: function(error){
		            // on error, tell the user sign up failed
		            setTimeout(function () {
		            	toastr.error("Unable to create priority.");
				        $('#add_new').html('Save');
				    }, 4000); 
				    console.log(error);
		        }
		    });
		    return false;
        }
	});

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