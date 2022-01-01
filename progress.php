<?php 
    require_once 'partials/header.inc.php'; 
?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Progress</h4>
                            </div>
                            <div class="content">
                                <div class="card" style="border:1px solid lightgrey;">
                                    <div class="content">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="text-center">
                                                    <b>Progress </b>
                                                </div>
                                                <div class="text-center">
                                                    <div class="row">
                                                        <div class="col-md-3 col-md-offset-1">
                                                            <h5><span class="planNum"></span><br /><small>Number of Goal Plans</small></h5>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <h5><span class="countCompletedPlans"></span><br /><small>Completed Plans</small></h5>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <h5><span class="countOngoingPlans"></span><br /><small>Ongoing Plans</small></h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="planbodyPro"></div>
                <script>
                    $(document).ready(function(){
                        // refreshTable();
                        // Refreshes every 2 seconds
                        var interval = 2000;
                        $(function() {
                            setInterval(function() {
                                var _id = <?= $client_id; ?>;
                                var planNum = <?= $planNum; ?>;
                                var countCompletedPlans = <?= $countCompletedPlans; ?>;
                                var countOngoingPlans = <?= $countOngoingPlans; ?>;
                                $(".planNum").html(planNum);
                                $(".countCompletedPlans").html(countCompletedPlans);
                                $(".countOngoingPlans").html(countOngoingPlans);

                                        
                                $.getJSON("/api/plan/read_single_person_plan_analytics.php?_id="+_id, function(data){
                                    var sn = 0;
                                    var read_plan_html = "";
                                    var countCompletedPriority = "";
                                    var from_date = [];
                                    var to_date = [];
                                    $.map( data.records, function( val, index ){
                                        // for serial numbering
                                        sn++;
                                        var plan_id = val.plan_id;
                                        var key_id = val.key_id;
                                        from_date = val.from_date;
                                        to_date = val.to_date;
                                        var time_left = diffDaysLeft(new Date(from_date), new Date(to_date));
                                        var time_used = diffDaysLeft(new Date(from_date), new Date());

                                        if (time_left == "" || time_used == "" || key_id == "" || plan_id == "") {
                                                toastr.error('Form inputs cannot be empty!');
                                        }else{
                                            // get form data
                                            var obj = { "time_left":time_left, "time_used":time_used, "key_id":key_id, "plan_id":plan_id };
                                            var data = JSON.stringify(obj);
                                            // submit form data to api
                                            $.ajax({
                                                url: "../../../api/analytics/update.php",
                                                type : "PUT",
                                                dataType: 'json',
                                                contentType : 'application/json',
                                                data : data,
                                                success : function(result) {
                                                    // console.log(result);
                                                },
                                                error: function(error){
                                                    // console.log(error);
                                                }
                                            });
                                        }

                                        read_plan_html+=`<div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="header">
                                                    <h4 class="title">` + val.goal + ` (` + val.plan_type + `) </h4>
                                                </div>
                                                <div class="content">
                                                    <ul class="list-unstyled team-members">
                                                        <li>
                                                            <div class="row">
                                                                <div class="col-xs-9">
                                                                    <span class="text-muted"><small>` + val.from_date + `</small></span>
                                                                    <span class="text-primary" style="color:#000;"><small>To</small></span>
                                                                    <span class="text-success"><small>` + val.to_date + `</small></span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    <div class="row text-center">
                                                        <div class="col-md-4">
                                                            <h5><span>Number of Intervals: </span><span class="intNum">`+val.no_of_intervals+`</span></h5>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <h5><span>Total Number of Priorities: </span><span class="countTotalPriorities">`+val.total_priorities+`</span></h5>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <h5><span>Time Used: </span><span class="timeUsed">`+val.time_used+` days</span></h5>
                                                        </div>
                                                    </div>
                                                    <div class="row text-center">
                                                        <div class="col-md-4">
                                                            <h5><span>Intervals Completed: </span><span class="intNumCompleted">`+val.intervals_completed+`</span></h5>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <h5><span>Total Number of Priorities Done: </span><span class="countCompletedPriority">`+val.total_priorities_done+`</span></h5>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <h5><span>Time Left: </span><span class="timeLeft">`+val.time_left+` days</span></h5>
                                                        </div>
                                                        <br>
                                                        <div id="showTime"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        `;
                                    });
                                    // end card
                                    read_plan_html+=`</div>`;
                                    // inject to 'page-content' of our app
                                    $("#planbodyPro").html(read_plan_html);
                                });
                            }, interval);
                        });
                    });
                </script><br>
            </div>
        </div>


<?php 
    require_once 'partials/footer.inc.php'; 
?>
