$(document).ready(function(){
 
    // will run if the delete button was clicked
    $(document).on('click', '.deleteModalBtn', function(){
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
			        url: "../api/plan/delete.php?_id=" + _id,
			        type : "DELETE",
			        dataType : 'json',
			        data : JSON.stringify({ _id: _id }),
			        success : function(result) {
			            // re-load list of products
			            toastr.success(result.message);
			   //          setTimeout(function(){
						//    	window.location.reload(true);
						// }, 2000);
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