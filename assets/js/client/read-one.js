// $(document).ready(function(){
//     var interval = 2000;
//     var _id = $("#client_id").val();
//     alert(_id);
//     $(function() {
//         setInterval(function() {
//             $.getJSON("/api/client/read_single.php?_id=" + _id, function(data){
//                 console.log(data); 
//                 var firstname = data.firstname; var lastname = data.lastname; var fullname = firstname + " " +lastname;
//                 var email = data.email; var country = data.country.toUpperCase(); var state = data.state; var gender = data.gender; var dob = data.dob; var address = data.address; var description = data.description;
//                 var contact_number = data.contact_number; var isVerified = data.isVerified; var profile_img = data.profile_img; var created = data.created; var modified = data.modified;

//                 $("#fullname").html(fullname); $("#email").html(email); $("#contact_number").html(contact_number); $("#description").html(description); $("#country").text(country); $("#state").text(state); $("#genderPill").html(gender).val(gender);

//                 $("#up_fname").val(firstname);$("#up_lname").val(lastname);$("#up_email").val(email);$("#up_contact_number").val(contact_number);$("#up_gender").val(gender);$("#up_country").val(country);$("#up_state").val(state);$("#up_dob").val(dob);$("#up_description").val(description);$("#up_address").val(address);$("#client_id").val(_id);
//             });
//         }, interval);
//     });
    
// });