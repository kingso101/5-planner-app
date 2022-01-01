<?php 
require_once(__DIR__ .'/../partials/header.inc.php');
require_once(__DIR__ .'/../partials/sideBar.inc.php');
require_once(__DIR__ .'/csv.php');

?>

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="page-title-box">
                                    <h4 class="page-title">Plan </h4>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Plan </li>
                                    </ol>
            
                                    <!-- <div class="state-information d-none d-sm-block">
                                        <div class="state-graph">
                                            <div id="header-chart-1"></div>
                                            <div class="info">Balance $ 2,317</div>
                                        </div>
                                        <div class="state-graph">
                                            <div id="header-chart-2"></div>
                                            <div class="info">Item Sold 1230</div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="page-content-wrapper">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card m-b-20">
                                        <div class="card-body">
                                        	<a href="plan/pdf.php" target="_blank" type="button" class="mr-1 mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent actionBtn btn-sm btn btn-primary waves-effect waves-light float-left" ><i class="fa fa-fw fa-file-pdf-o"></i>PDF</a>
            								<a download="plan-details" href="<?= $csv_response; ?>" type="button" class="mr-1 mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent actionBtn btn-sm btn btn-primary waves-effect waves-light float-left" ><i class="fa fa-fw fa-file-excel-o"></i>CSV</a>
            								<a href="javascript:void(0);" type="button" class="mr-1 mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent actionBtn btn-sm btn btn-primary waves-effect waves-light float-left" id="print"><i class="fa fa-print"></i>PRINT</a>

            								<!-- <button type="button" class="m-b-30 mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent actionBtn btn-sm btn btn-primary waves-effect waves-light float-right" data-toggle="modal" data-target=".addTransactModal" >Add Transaction</button> -->

                                            <div class="table-rep-plugin">
                                                <div class="table-responsive b-0" data-pattern="priority-columns">
                                                    <table id="tech-companies-1" class="table  table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th>S/N</th>
                                                            <th style="width: 20%">Date</th>
                                                            <th style="width: 5%">From Acc</th>
                                                            <th style="width: 5%">To Acc</th>
                                                            <th style="width: 5%">Amount</th>
                                                            <th style="width: 5%">Status</th>
                                                            <th style="width: 20%">Transaction Type </th>
                                                            <th style="width: 40%">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="transferbody">
	                                                    <script>
	                                                        $(document).ready(function(){
	                                                        	function printData(){
												                  window.print();
												                }

												                $("#print").click(function(){
												                  // alert("Hey");
												                  printData();
												                });
                
	                                                        	// refreshTable();
	                                                        	// Refreshes every 2 seconds
		                                                        var interval = 2000;
		                                                        $(function() {
																    setInterval(function() {
																        $.getJSON("/api/transfer/read.php", function(data){
			                                                                console.log(data);  
			                                                                var sn = 0;
			                                                                var read_transfer_html = "";
			                                                                $.each(data.records, function(key, val) {
			                                                                    // for serial numbering
			                                                                    sn++;
			                                                                    var transaction_type = capitalize(val.transaction_type);
			                                                                    var amount = parseInt(val.amount);
			                                                                    var status = capitalize(val.status);
			                                                                    var to_account = capitalize(val.to_account);
			                                                                    // amount = amount.toLocaleString();
			                                                                    // creating new table row per record
			                                                                    if (to_account == "Nil") {
			                                                                    	to_account = val.account_number;
			                                                                    }else{
			                                                                    	to_account = val.to_account;
			                                                                    }

			                                                                    if (status == "Pending") {
																					status = '<span style="color: #fff;" class="badge badge-warning">'+status+'</span>';
																	            }

																	            if(status == "Completed") {
																	            	status = '<span style="color: #fff;" class="badge badge-success">'+status+'</span>';
																	            }

			                                                                    read_transfer_html+=`<tr>
			                                                                        <th scope="row">` + sn + `</th>
			                                                                        <td>` + val.created + `</td>
			                                                                        <td>` + val.from_account + `</td>
			                                                                        <td>` + to_account + `</td>
			                                                                        <td>$` + moneyFormat(amount) + `</td>
			                                                                        <td>` + status + `</td>
			                                                                        <td>` + transaction_type + `</td>
			                                                                        <td class="btnBasket">
			                                                                            

																						<button type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent actionBtn btn-sm btn btn-primary waves-effect waves-light read-one-transfer-button" data-toggle="modal" data-target=".readTransferModal" data-id='` + val._id + `'><span class='glyphicon glyphicon-eye-open'></span>&nbsp;Read</button>

			                                                                            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent actionBtn btn-sm btn btn-info update-transfer-button" data-toggle="modal" data-target=".editTransactModal" data-id='` + val._id + `'>
																						  <span class='glyphicon glyphicon-edit'></span>&nbsp;Edit
																						</button>

			                                                                            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent actionBtn btn-sm btn btn-danger delete-transfer-button" data-id='` + val._id + `'>
																						  <span class='glyphicon glyphicon-remove'></span>&nbsp;Delete
																						</button>
			                                                                        </td>
			                                                                    </tr>`;
			                          //                                           $("td.money_format").each(function () {
																	            //     $(this).text($(this).text().toLocaleString('en-US'));
																	            // })
			                                                                });
			                                                                // end table
			                                                                read_transfer_html+=`</tbody></table>`;
			                                                                // inject to 'page-content' of our app
			                                                                $("#transferbody").html(read_transfer_html);
			                                                            });
																    }, interval);
																});
	                                                        });
	                                                    </script>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <!-- Read modal -->
        									<div class="col-sm-6 col-md-3 m-t-30">
        										<div class="modal fade readTransferModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
						                            <div class="modal-dialog modal-sm">
						                                <div class="modal-content">
						                                    <div class="modal-header">
						                                        <h5 class="modal-title mt-0" id="mySmallModalLabel">Read Transaction</h5>
						                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						                                    </div>
						                                    <div class="modal-body" id="readModal">
						                                        <script>
			                                                		$(document).ready(function(){
			                                                			$(document).on('click', '.read-one-transfer-button', function(){
			                                                				var _id = $(this).attr('data-id');
			                                                				var status = "completed";
			                                                				// alert(_id);
			                                                				if (_id == "" || status == "") {
							                                                    toastr.error("Form inputs can't be empty!");
							                                                }else{
							                                                    var obj = { "status":status, "_id":_id };
							                                                    var form_data = JSON.stringify(obj);
							                                                    // console.log(form_data);
							                                                    $.ajax({
							                                                        url: "/api/transfer/mark_as_read.php",
							                                                        type : "POST",
							                                                        contentType : "application/json",
							                                                        data : form_data,
							                                                        // dataType: 'json',
							                                                        success: function(data){
							                                                            // alert(data);
							                                                            // toastr.success(data.message);
							                                                            // console.log(data);
							                                                        },
							                                                        error: function(result){
							                                                            setTimeout(function() {
							                                                            	console.log(result);
							                                                                // toastr.error("Error! Unable to perform transaction.");
							                                                                // $("#submit_ext_transfer").html("Transfer");
							                                                            }, 3000);
							                                                        }
							                                                    });
							                                                }

			                                                				$.getJSON("/api/transfer/read_single.php?_id=" + _id, function(data){
			                                                					var amount = parseInt(data.amount);
			                                                					var status = capitalize(data.status);
			                                                					var to_account = capitalize(data.to_account);
			                                                					var transaction_type = capitalizeFirstLetters(data.transaction_type);
			                                                                    // amount = amount.toLocaleString();

			                                                					if (to_account == "Nil") {
			                                                                    	to_account = data.account_number;
			                                                                    }else{
			                                                                    	to_account = data.to_account;
			                                                                    }

			                                                                    if (status == "Pending") {
																					status = '<span style="color: #fff;" class="badge badge-warning">'+status+'</span>';
																	            }

																	            if(status == "Completed") {
																	            	status = '<span style="color: #fff;" class="badge badge-success">'+status+'</span>';
																	            }

																	            var read_one_transfer_html=`
																	            <div class="col-md-12 col-lg-12 col-xl-12">
												                                    <div class="card m-b-30">
												                                        <div class="card-body">
												                                            <a href="javascript:void(0);" class="card-link">From Account: ` + data.from_account + `</a></br>
												                                            <a href="javascript:void(0);" class="card-link">To Account: ` + to_account+ `</a></br>
												                                            <a href="javascript:void(0);" class="card-link">Amount: $` + moneyFormat(amount) + `</a></br>
												                                            <a href="javascript:void(0);" class="card-link">Created: ` + data.created + `</a>
												                                            <p class="card-text"><b>Status: </b>` + status + `</p>
												                                            <p class="card-text"><b>Transaction Type: </b>` + transaction_type + `</p>
												                                        </div>
												                                    </div>
												                                </div>`;
						                                                        $("#readModal").html(read_one_transfer_html);
			                                                				});
			                                                			});
			                                                		});
			                                                	</script>
						                                    </div>
						                                </div><!-- /.modal-content -->
						                            </div><!-- /.modal-dialog -->
						                        </div><!-- /.modal -->
                                            </div>

                                            <!-- Edit modal -->
        									<div class="col-sm-6 col-md-3 m-t-30">
        										<div class="modal fade editTransactModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-sm">
                                                        <div class="modal-content">
						                                    <div class="modal-header">
						                                        <h5 class="modal-title mt-0" id="mySmallModalLabel">Edit Transaction</h5>
						                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						                                    </div>
						                                    <div class="modal-body" >
						                                        <script>
			                                                		$(document).ready(function(){
			                                                			$(document).on('click', '.update-transfer-button', function(){
			                                                				var _id = $(this).attr('data-id');
			                                                				// alert(_id);
			                                                				$.getJSON("/api/transfer/read_single.php?_id=" + _id, function(data){
			                                                					// console.log(data);
			                                                					var client_id = data.client_id;
			                                                					var amount = data.amount;
																			    var action = data.action;
																			    var transaction_note = data.transaction_note;
																			    
																			    $("#up_id").val(_id);
																			    $("#up_client_id").val(client_id);
			                                                					$("#up_amount").val(amount);
            																	$("#up_action_option").val(action).text(action);
            																	$("#up_transaction_note").val(transaction_note);
            																	
			                                                				});
			                                                			});
																		

																		// trigger when update form is submitted
																		$(document).on('submit', '#update_transact_form', function(e){
																			e.preventDefault();
																			// alert('Hey');
																		 	var up_id = $('#up_id').val();
																		 	var up_client_id = $('#up_client_id').val();
																		 	var up_amount = $('#up_amount').val();
																	        var up_action = $('#up_action').val();
																	        var up_transaction_note = $('#up_transaction_note').val();

																	        if (up_amount == "" || up_action == "" || up_transaction_note == "" || up_id == "" || up_client_id == "") {
																	            // $('#errorAlert').slideDown(300).delay(5000).slideUp(300).html("Form inputs cannot be empty.");
																	            toastr.error('Form inputs cannot be empty!');
																	        }else{
																	        	// get form data
																			    var obj = { "up_amount":up_amount, "up_action":up_action, "up_transaction_note":up_transaction_note, "_id":up_id, "up_client_id":up_client_id };
																			    var form_data = JSON.stringify(obj);

																			 	// alert(form_data);
																			 	// submit form data to api
																			    $.ajax({
																			        url: "../api/transfer/update.php",
																			        type : "PUT",
																			        dataType: 'json',
																			        contentType : 'application/json',
																			        data : form_data,
																			        beforeSend: function(){
																                        setTimeout(function () {
																					        $('#update_transaction').text('Loading...');
																					    }, 100); 
																                    },
																			        success : function(result) {
																			        	// alert(result.message);
																			        	
																			        	setTimeout(function(){
																			            	toastr.success(result.message);
																			            	$('#update_transaction').delay(3000).html('Update');
																						   	// window.location.reload(true);
																						}, 4000);
																	            		// $("#update_transact_form")[0].reset();
																	            		// document.getElementById("up_dummy").innerHTML = "";
																			        },
																			        error: function(xhr, resp, text){
																			            // on error, tell the user sign up failed
																			            setTimeout(function () {
																			            	toastr.error("Unable to update transaction");
																					        $('#send_message').text('Update');
																					    }, 4000); 
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

			                                                			$(document).on('click', '.delete-transfer-button', function(){
																	        // get the product id
																			var _id = $(this).attr('data-id');
																			// bootbox for good looking 'confirm pop up'
																			swal({
																			  title: "Are you sure?",
																			  text: "Once deleted, you will not be able to recover record data!",
																			  icon: "warning",
																			  buttons: true,
																			  dangerMode: true,
																			})
																			.then((willDelete) => {
																			  	if (willDelete) {
																				    swal("Success! Your record data has been deleted!", {
																				      	icon: "success",
																				    });
																				    // send delete request to api / remote server
																				    $.ajax({
																				        url: "../api/transfer/delete.php?_id=" + _id,
																				        type : "DELETE",
																				        dataType : 'json',
																				        data : JSON.stringify({ _id: _id }),
																				        success : function(result) {
																				            // re-load list of products
																				            setTimeout(function(){
																				            	toastr.success(result.message);
																							   	// window.location.reload(true);
																							}, 2000);
																				        },
																				        error: function(xhr, resp, text) {
																				            // console.log(xhr, resp, text);
																				        }
																				    });
																				} else {
																				    swal("Your record data is safe!");
																				}
																			});
																	    });
			                                                		});
			                                                	</script>

			                                                	<form id="update_transact_form" class="needs-validation" enctype="multipart/form-data" novalidate>
			                                                		<div class="form-row">
								                                        <div class="col-md-12">
								                                            <div class="form-group">
							                                                    <label>Amount</label>
							                                                    <input type="text" class="form-control" name='up_amount' id="up_amount" value="" placeholder="Type something"/>
							                                                </div>
								                                        </div>
								                                    </div>

								                                    <div class="form-row">
								                                        <div class="col-md-12">
								                                            <div class="form-group">
							                                                    <label>Transaction Type</label>
							                                                    <select id="up_action" name="up_action" class="custom-select form-control">
																            		<option id="up_action_option" value="" ></option>
										                                            <option value="credit">Credit</option>
								                                                    <option value="debit">Debit</option>
										                                        </select>
							                                                </div>
								                                        </div>
								                                    </div>

								                                    <div class="form-row">
								                                        <div class="col-md-12">
								                                            <div class="form-group">
							                                                    <label>Transaction Note</label>
							                                                    <div>
							                                                        <textarea class="form-control" rows="2" name='up_transaction_note' id="up_transaction_note" placeholder="` + data.transaction_note + `">` + data.transaction_note + `</textarea>
							                                                    </div>
							                                                </div>
								                                        </div>
								                                    </div>

								                                    <div class="form-row">
								                                        <div class="col-md-6">
								                                            <div class="form-group">
								                                                <tr>
																		            <td><input id="up_id" name="up_id" value="" type="hidden" /></td>
																		        </tr>
																		        <tr>
																		            <td><input id="up_client_id" name="up_client_id" value="" type="hidden" /></td>
																		        </tr>
								                                            </div>
								                                        </div>
										                            </div>
										                            </br></br>

					                                                <div class="form-group">
					                                                    <div>
					                                                        <button type="submit" class="btn btn-primary waves-effect waves-light float-right" id="update_transaction" name="update_transaction">
					                                                            Submit
					                                                        </button>
					                                                    </div>
					                                                </div>
					                                            </form>
						                                    </div>
						                                </div><!-- /.modal-content -->
						                            </div>
                                                </div><!-- /.modal-dialog -->
                                            </div>

                                            <!-- Add modal -->
        									<div class="col-sm-6 col-md-3 m-t-30">
        										<div class="modal fade addTransactModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
						                                    <div class="modal-header">
						                                        <h5 class="modal-title mt-0" id="mySmallModalLabel">Add Transaction</h5>
						                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						                                    </div>
						                                    <div class="modal-body" >
						                                        <script>
			                                                		$(document).ready(function(){
			                                                			// trigger when add form is submitted
																		$(document).on("submit", "#add_transaction_form", function(e){
																			e.preventDefault();
																			// alert('Hey');
																		 	var add_admin_id = $("#admin_id").val();
																		 	var add_amount = $("#add_amount").val();
																		 	// var add_balance = $('#add_balance').val();
																	        var add_action = $("#add_action").val();
																	        var add_transaction_note = $("#add_transaction_note").val();
																	        // alert(up_profile_img_base64);

																	        if (add_admin_id == "" || add_amount == "" || add_action == "" || add_transaction_note == "" ) {
                																toastr.error("Form inputs cannot be empty!");
																	        }else{
																	        	var obj = { "amount":add_amount, "action":add_action, "transaction_note":add_transaction_note, "admin_id":add_admin_id };
																		    	var form_data = JSON.stringify(obj);
														                        // alert(form_data);
														                        
														                        $.ajax({
														                            url: "/api/transfer/create.php",
														                            type : "POST",
																			        contentType : "application/json",
																			        data : form_data,
														                            // dataType: 'json',
														                            beforeSend: function(){
														                                setTimeout(function () {
																					        $('#add_transaction').html("Loading...");
																					    }, 100);
														                            },
														                            success: function(data){
														                                // alert(data);
														                                $("#add_transaction_form")[0].reset();
																	            		setTimeout(function(){
																			            	toastr.success(data.message);
																			            	$('#add_transaction').delay(3000).html("Add");
																						}, 3000);
														                            },
														                            error: function(jqXHR, textStatus, errorThrown){
																				        setTimeout(function () {
																			            	toastr.error("Error! Unable to add transaction.");
																					        $("#add_transaction").html("Add");
																					    }, 4000); 
																			        }
														                        });
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
			                                                	</script>

			                                                	<form id="add_transaction_form" class="needs-validation" novalidate>
			                                                		<div class="form-row">
								                                        <div class="col-md-12">
								                                            <div class="form-group">
							                                                    <label>Amount</label>
							                                                    <input type="number" class="form-control" name='add_amount' id="add_amount" />
							                                                </div>
								                                        </div>
								                                    </div>

								                                    <div class="form-row">
								                                        <div class="col-md-12">
								                                            <div class="form-group">
							                                                    <label>Transaction Type</label>
							                                                    <select id="add_action" name="add_action" class="custom-select form-control">
																            		<option value="">Select Type</option>
										                                            <option value="credit">Credit</option>
								                                                    <option value="debit">Debit</option>
										                                        </select>
							                                                </div>
								                                        </div>
								                                    </div>

								                                    <input type="hidden" name="admin_id" id="admin_id" value="<?= $_SESSION['_id']; ?>">

								                                    <!-- <input type="hidden" name="add_balance" id="add_balance" value="0"> -->

								                                    <div class="form-row">
								                                        <div class="col-md-12">
								                                            <div class="form-group">
							                                                    <label>Transaction Note</label>
							                                                    <div>
							                                                        <textarea class="form-control" rows="2" name='add_transaction_note' id="add_transaction_note" placeholder="Type transaction note here.."></textarea>
							                                                    </div>
							                                                </div>
								                                        </div>
								                                    </div>
																	
																	<div class="form-group">
					                                                    <div>
					                                                        <button type="submit" class="btn btn-primary wavet-effect waves-light float-right" id="add_Transaction" name="add_Transaction">
					                                                            Add
					                                                        </button>
					                                                    </div>
					                                                </div>
					                                            </form>
						                                    </div>
						                                </div><!-- /.modal-content -->
						                            </div>
                                                </div><!-- /.modal-dialog -->
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->   
                        </div>
                        <!-- end page content-->

                    </div> <!-- container-fluid -->

                </div> <!-- content -->

<?php require_once(__DIR__ .'/../partials/footer.inc.php'); ?>