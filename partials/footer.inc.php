        <!-- Add Plan type Modal -->
        <div style="display: none;" id="addPlanTypeModal" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="card">
                    <span id="addPlanTypeModalCloseBtn" class="addPlanTypeModalCloseBtn">&times;</span>
                    <div class="header">
                        <h4 class="title">What is this plan for?</h4>
                    </div>
                    <div class="content" id="add_plan_type_div"></div>
                </div>
            </div>
        </div>
        <!-- Add Plan Modal -->
        <div style="display: none;" id="addPlanModal" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="card">
                    <span id="addPlanModalCloseBtn" class="addPlanModalCloseBtn">&times;</span>
                    <div class="header">
                        <h4 class="title"><span id="title">Add</span> Plan</h4>
                    </div>
                    <div class="content" id="add_div"></div>
                </div>
            </div>
        </div>
        <!-- Update Plan Modal -->
        <div style="display: none;" id="updatePlanModal" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="card">
                    <span id="updatePlanModalCloseBtn" class="close updatePlanModalCloseBtn">&times;</span>
                    <div class="header">
                        <h4 class="title">Edit Plan</h4>
                    </div>
                    <div class="content" id="update_div"></div>
                </div>
            </div>
        </div>
        <!-- Update Priority Modal -->
        <div style="display: none;" id="updatePriorityModal" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="card">
                    <span id="updatePriorityModalCloseBtn" class="updatePriorityModalCloseBtn">&times;</span>
                    <div class="header">
                        <h4 class="title">Edit Plan Intervals</h4>
                    </div>
                    <div class="content" id="update_plan_int_div"></div>
                </div>
            </div>
        </div>
        <!-- Add Priority Modal -->
        <div style="display: none;" id="addPriorityModal" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="card">
                    <span id="addPriorityModalCloseBtn" class="addPriorityModalCloseBtn">&times;</span>
                    <div class="header">
                        <h4 class="title">New Priority</h4>
                    </div>
                    <div class="content" id="add_plan_priority_div"></div>
                </div>
            </div>
        </div>
        <!-- Read Plan Modal -->
        <div style="display: none;" id="readPlanModal" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="card">
                    <span id="readPlanModalCloseBtn" class="readPlanModalCloseBtn">&times;</span>
                    <div class="header">
                        <h4 class="title">Read Plan</h4>
                    </div>
                    <div class="content" id="read_plan_div"></div>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div class="container-fluid">
                <div class="copyright pull-left">
                    <span align="center">
                        <b style="color: #2452ae;">F.</b>
                        <b style="color: #cfc200;">I.</b>
                        <b style="color: #089842;">V.</b>
                        <b style="color: #ec3b3b;">E</b> 
                        Planner
                    </span>
                    &copy; <script>document.write(new Date().getFullYear())</script>, made with <i class="fa fa-heart heart"></i> by <a href="https://github.com/kingso101">Okeke Kingsley</a>
                </div>
            </div>
        </footer>
    </div>
</div>

</body>

    <!--   Core JS Files   -->
    <script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

	<!--  Checkbox, Radio & Switch Plugins -->
	<script src="assets/js/bootstrap-checkbox-radio.js"></script>

	<!--  Charts Plugin -->
	<script src="assets/js/chartist.min.js"></script>

    <!--  Notifications Plugin    -->
    <script src="assets/js/bootstrap-notify.js"></script>

    <!--  Google Maps Plugin    -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>

    <!-- Paper Dashboard Core javascript and methods for Demo purpose -->
	<script src="assets/js/paper-dashboard.js"></script>

	<!-- Paper Dashboard DEMO methods, don't include it in your project! -->
	<script src="assets/js/demo.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/client/update.js"></script>
    <script src="assets/js/client/add-client.js"></script>
    <script src="assets/js/plan/add-plan.js"></script>
    <script src="assets/js/plan/read-one.js"></script>
    <script src="assets/js/plan/update.js"></script>
    <script src="assets/js/plan/delete.js"></script>
    <script src="assets/js/priority/add-priority.js"></script>
    <script src="assets/js/priority/read-one.js"></script>
    <script src="assets/js/priority/update.js"></script>
    <script src="assets/js/priority/delete.js"></script>
	<script type="text/javascript">
    	$(document).ready(function(){
            var pathname = window.location.pathname;
            pathname = pathname.substring(pathname.lastIndexOf("/")+1);
            // alert(pathname);
            if(pathname === '.' || pathname === "/" || pathname === "" || pathname === "dashboard") {
                demo.initChartist();

                $.notify({
                    icon: 'ti-gift',
                    message: "Welcome to <b>Five Planner Dashboard</b> - People who have five year plans achieve more."

                },{
                    type: 'success',
                    timer: 4000
                });
            }

            // Get the modal 
            var dialog = document.getElementById("dialog");
            var closeBtn = document.getElementsByClassName("closeBtn");

            function showdiv() {
              dialog.style.display = "block";
              // alert("hello You");
            }

            function closediv() {
                dialog.style.display = "none";
            }

            var interval = 2000;
            $(function() {
                setInterval(function() {
                    var planNum = <?= $planNum; ?>;
                    var priorityNum = <?= $priorityNum; ?>;
                    var countCompletedPlans = <?= $countCompletedPlans; ?>;
                    var countOngoingPlans = <?= $countOngoingPlans; ?>;
                    $(".planNum").html(planNum);
                    $(".countPriorities").html(priorityNum);
                    $(".countCompletedPlans").html(countCompletedPlans);
                    $(".countOngoingPlans").html(countOngoingPlans);
                }, interval);
            });
    	});
	</script>

</html>