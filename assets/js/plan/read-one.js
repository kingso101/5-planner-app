$(document).ready(function(){
    // Read new priority label
    function printData(){
      window.print();
    }

	$(document).on('click', '#readModalBtn', function(){
		var _id = $(this).attr('data-id');
		$.getJSON("/api/plan/read_single.php?_id=" + _id, function(data){
			// console.log(data);
			$("#readPlanModal").css("display", "block");
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
		    var read_plan_html=`
		    <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <b>Goal:</b> `+goal+`
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <b>Plan Period: </b> ` + from_date + ` to ` + to_date + `
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <b>Description: </b> ` + description + `
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <b>Resources: </b> ` + resources + `
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                	<b style="float:left;">
            			Priorities
            		</b>
                    <div class="form-group">
                        <div class="tabs">
			                <label class="tab">
			                    <input type="radio" id="radio-a" name="up_plan_int" class="tab-input" value="1">
			                    <div class="tab-box">1 Month</div>
			                </label>
			                <label class="tab">
			                    <input type="radio" id="radio-b" name="up_plan_int" class="tab-input" value="6">
			                    <div class="tab-box">6 Months</div>
			                </label>
			                <label class="tab">
			                    <input type="radio" id="radio-b" name="up_plan_int" class="tab-input" value="12">
			                    <div class="tab-box">Next Year</div>
			                </label>
			                <label class="tab">
			                    <input type="radio" id="radio-d" name="up_plan_int" class="tab-input" value="24">
			                    <div class="tab-box">2 Years</div>
			                </label>
			                <label class="tab">
			                    <input type="radio" id="radio-e" name="up_plan_int" class="tab-input" value="60">
			                    <div class="tab-box">5 Years</div>
			                </label>
			            </div>
                    </div>
                </div>
            </div>
            <div class="card" style="border:1px solid lightgrey;">
                <div class="content">
                	<div class="row">
                        <div class="col-md-12">
                        	<div class="form-group">
	                        	<b>First Reward: </b> ` + reward + `
	                    	</div>
                        </div>
                    </div><br>
                    <div style="border-top:1px solid lightgrey;width:100%;" class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <label style="float:left;">` + priority_label + `</label> 
                            <label style="float:right;">` + priority_date + `</label> 
                        </div>
                    </div><br>
                    <div style="border-top:1px solid lightgrey;width:100%;" class="clearfix"></div><br><br>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="clearfix"></div>
            <div class="row" style="float:right;">
            	<div class="text-center" style="display:inline-block;">
                    <button type="button" class="btn btn-danger btn-fill readPlanModalCloseBtn" style="background-color:#ec3b3b;color:#fff;">Back</button>
                </div>
                <div class="text-center" style="display:inline-block;">
                    <button type="submit" name="print_plan" id="print_plan" style="background-color:#2478d3;color:#fff;" class="print_plan btn btn-info btn-fill">Print</button>
                </div>
            </div>
            <div class="clearfix"></div>`;
	        $("#read_plan_div").html(read_plan_html);
	        if(plan_interval == 1){
	        	$('#radio-a').prop('checked', true);
	        }
	        else if(plan_interval == 6){
		  		$('#radio-b').prop('checked', true);
			}
	        else if(plan_interval == 12){
	          	$('#radio-c').prop('checked', true);
	        }
	        else if(plan_interval == 24){
	          	$('#radio-d').prop('checked', true);
	        }
	        else if(plan_interval == 60){
	          	$('#radio-e').prop('checked', true);
	        }
		});
		$(document).on('click', '.print_plan', function(){
	      	// alert("Hey");
	      	printData();
	    });
	});
});