$(document).ready(function(){
	// Add new priority label
	$(document).on('click', '.addPlanPriorityBtn', function(){
		var _id = $(this).attr('data-id');
		$.getJSON("/api/plan/read_single.php?_id=" + _id, function(data){
			$("#addPriorityModal").css("display", "block");
			// console.log(data);
			var priority_label = data.priority_label;
		    var priority_date = data.priority_date;
		    var key_id = data.key_id;
		    var add_plan_priority_html=`
		    <form id="updatePriorityForm">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Priority:</label>
                            <textarea rows="5" class="form-control border-input" id="add_priority_label" name="add_priority_label" placeholder="Priority Label" value="` + priority_label + `">` + priority_label + `</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Date:</label>
                            <input type="date" class="form-control border-input" id="add_priority_date" name="add_priority_date" placeholder="Priority Date" value="` + priority_date + `">
                        </div>
                    </div>
                </div>
                <i class="tab" style="float:right;">
                    Note: You will receive an email on this date reminding you of this priority
                </i><br>
                <input type="hidden" id="add_plan_id" name="add_plan_id" value="`+_id+`">
                <input type="hidden" id="add_key_id" name="add_key_id" value="`+key_id+`">
                <div class="clearfix"></div>
                <div class="clearfix"></div>
                <div class="row" style="float:right;">
                	<div class="text-center" style="display:inline-block;">
	                    <button type="button" class="btn btn-danger btn-fill addPriorityModalCloseBtn" style="background-color:#ec3b3b;color:#fff;">Close</button>
	                </div>
	                <div class="text-center" style="display:inline-block;">
	                    <button type="submit" name="update_new" id="update_new" style="background-color:#2478d3;color:#fff;" class="btn btn-info btn-fill">Save</button>
	                </div>
                </div>
                <div class="clearfix"></div>
            </form>`;
	        $("#add_plan_priority_div").html(add_plan_priority_html);
		});
	});
	
	// Add priority label and date
	$(document).on('submit', '#updatePriorityForm', function(e){
		e.preventDefault();
	 	var _id = $('#add_plan_id').val();
	 	var key_id = $('#add_key_id').val();
	 	var add_priority_label = $('#add_priority_label').val();
	 	var add_priority_date = $('#add_priority_date').val();

        if (add_priority_label == "" || add_priority_date == "" || key_id == "" || _id == "") {
            toastr.error('Form inputs cannot be empty!');
        }else{
        	// get form data
		    var obj = { "add_priority_label":add_priority_label, "add_priority_date":add_priority_date, "key_id":key_id };
		    var form_data = JSON.stringify(obj);
		 	// alert(form_data);
		 	// submit form data to api
		    $.ajax({
		        url: "../../../api/priority/update.php",
		        type : "PUT",
		        dataType: 'json',
		        contentType : 'application/json',
		        data : form_data,
		        beforeSend: function(){
                    setTimeout(function () {
				        $('#update_new').html('Loading...');
				    }, 100); 
                },
		        success : function(result) {
		        	// alert(result.message);
		        	setTimeout(function(){
		        		if (result.status == false) {
		        			toastr.error(result.message);
		        		}else{
		        			toastr.success(result.message);
		        			$('#labelForPriorityLabel').text(add_priority_label);
					        $('#labelForPriorityDate').text(add_priority_date);
		        		}
		        		$('#update_new').delay(3000).html('Save');
					}, 4000);
		        },
		        error: function(error){
		            // on error, tell the user sign up failed
		            setTimeout(function () {
		            	toastr.error("Unable to update priority.");
				        $('#update_new').html('Save');
				    }, 4000); 
				    // console.log(error);
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