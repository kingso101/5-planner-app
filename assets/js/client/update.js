$(document).ready(function(){
 
    // Code to submit profile data
    $(document).on('submit', '#profileDetailsForm', function(e){
        e.preventDefault();
        // alert("Hey submit");
        var _id = $('#client_id').val();
        var up_fname = $('#up_fname').val();
        var up_lname = $('#up_lname').val();
        var up_email = $('#up_email').val();

        if (up_fname == "" || up_lname == "" || up_email == "" || _id == "") {
            toastr.error('Form inputs cannot be empty!');
        }else{
            // get form data
            var obj = { "up_fname":up_fname, "up_lname":up_lname, "up_email":up_email, "_id":_id };
            var form_data = JSON.stringify(obj);
            // alert(form_data);
            // submit form data to api
            $.ajax({
                url: "../../../api/client/update-annex.php",
                type : "PUT",
                dataType: 'json',
                contentType : 'application/json',
                data : form_data,
                beforeSend: function(){
                    setTimeout(function () {
                        $('#subProfile').html('Loading...');
                    }, 100); 
                },
                success : function(result) {
                    // alert(result.message);
                    setTimeout(function(){
                        toastr.success(result.message);
                        $('#subProfile').delay(3000).html('Update Profile');
                        // window.location.reload(true);
                    }, 4000);
                },
                error: function(xhr, resp, text){
                    // on error, tell the user sign up failed
                    setTimeout(function () {
                        toastr.error("Unable to update plan interval.");
                        $('#subProfile').html('Update Profile');
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

});