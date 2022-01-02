// var modal = document.getElementById("myModal");
var addPlanModal = document.getElementById("addPlanModal");
var updatePlanModal = document.getElementById("updatePlanModal");
var addPriorityModal = document.getElementById("addPriorityModal");
var readPlanModal = document.getElementById("readPlanModal");
var addPlanTypeModal = document.getElementById("addPlanTypeModal");
var updatePriorityModal = document.getElementById("updatePriorityModal");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
// span.onclick = function() {
//   modal.style.display = "none";
// }

$(document).on('click', '.addPriorityModalCloseBtn', function(){
  addPriorityModal.style.display = "none";
});

$(document).on('click', '.readPlanModalCloseBtn', function(){
  readPlanModal.style.display = "none";
});

$(document).on('click', '.updatePriorityModalCloseBtn', function(){
  updatePriorityModal.style.display = "none";
});

$(document).on('click', '.updatePlanModalCloseBtn', function(){
  updatePlanModal.style.display = "none";
});

$(document).on('click', '.addPlanModalCloseBtn', function(){
  addPlanModal.style.display = "none";
});

$(document).on('click', '.addPlanTypeModalCloseBtn', function(){
  addPlanTypeModal.style.display = "none";
});

