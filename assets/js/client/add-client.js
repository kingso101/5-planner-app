// $(document).ready(function(){
// 	// Code to submit profile data
//     $(document).on('submit', '#signup_form', function(e){
//         e.preventDefault();
//         alert("Hey submit");
//         var firstname = $('#firstname').val();
//         var lastname = $('#lastname').val();
//         var email = $('#email').val();
//         var password = $('#password').val();

//         f (firstname == "" || lastname == "" || email == "" || password == "") {
//             toastr.error('Form inputs cannot be empty!');
//         }else{
//             // get form data
//             var obj = { "firstname":firstname, "lastname":lastname, "password":password, "email":email };
//             var form_data = JSON.stringify(obj);
//             alert(form_data);
//             // submit form data to api
//             $.ajax({
//                 url: "../../../api/client/create.php",
//                 type : "PUT",
//                 dataType: 'json',
//                 contentType : 'application/json',
//                 data : form_data,
//                 beforeSend: function(){
//                     setTimeout(function () {
//                         $('#reg').html('Loading...');
//                     }, 100); 
//                 },
//                 success : function(result) {
//                     setTimeout(function() {
//                         if(result.status == "Done"){
//                             window.location.href = "./login";
//                             $('#reg').text('Sign Up');
//                         }else{
//                             toastr.error("Registration failed. Email or password is incorrect.");
//                             $('#reg').text('Sign Up');
//                         }
//                     }, 4000);
//                 },
//                 error: function(error){
//                 	console.log(result);
//                     // on error, tell the user sign up failed
//                     setTimeout(function () {
//                         toastr.error("Unable to update plan interval.");
//                         $('#reg').html('Sign Up');
//                     }, 4000); 
//                 }
//             });
//             return false;
//         }
//     });

// 	// function to make form values to json format
// 	$.fn.serializeObject = function(){
	 
// 	    var o = {};
// 	    var a = this.serializeArray();
// 	    $.each(a, function() {
// 	        if (o[this.name] !== undefined) {
// 	            if (!o[this.name].push) {
// 	                o[this.name] = [o[this.name]];
// 	            }
// 	            o[this.name].push(this.value || '');
// 	        } else {
// 	            o[this.name] = this.value || '';
// 	        }
// 	    });
// 	    return o;
// 	};

// });