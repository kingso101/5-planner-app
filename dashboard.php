<?php 
    require_once 'partials/header.inc.php'; 
?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-5">
                                        <div class="icon-big icon-warning text-center">
                                            <i class="ti-server"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-7">
                                        <div class="numbers">
                                            <p>Total Plans</p>
                                            <span class="planNum"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-reload"></i> Updated now
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-5">
                                        <div class="icon-big icon-success text-center">
                                            <i class="ti-check-box"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-7">
                                        <div class="numbers">
                                            <p>Completed</p>
                                            <span class="countCompletedPlans"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-calendar"></i> Last day
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-5">
                                        <div class="icon-big icon-danger text-center">
                                            <i class="ti-timer"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-7">
                                        <div class="numbers">
                                            <p>Ongoing</p>
                                            <span class="countOngoingPlans"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-timer"></i> In the last hour
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-5">
                                        <div class="icon-big icon-info text-center">
                                            <i class="ti-star"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-7">
                                        <div class="numbers">
                                            <p>Priorities</p>
                                            <span class="countPriorities"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-reload"></i> Updated now
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button id="add_plan_modal" data-id="<?= $client_id; ?>" class="btn btn-info btn-fill btn-st">
                        <i class="ti-plus"></i>
                        Add Plan
                    </button>

                </div><br>

                <div id="planbody"></div>
                <script>
                    $(document).ready(function(){
                        function printData(){
                          window.print();
                        }

                        $("#print").click(function(){
                          printData();
                        });

                        var _id = "<?= $client_id; ?>";
                        // Refreshes every 2 seconds
                        var interval = 2000;
                        $(function() {
                            setInterval(function() {
                                $.getJSON("/api/plan/read_single_person_plan.php?_id="+_id, function(data){
                                    var sn = 0;
                                    var read_plan_html = "";
                                    if (data.records == "") {
                                        $("#planbody").html(`<div class="row">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="content">
                                                        No plan(s) created yet.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`);
                                        return false;
                                    }
                                    $.each(data.records, function(key, val) {
                                        sn++;
                                        read_plan_html+=`<div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="header">
                                                    <h4 class="title">` + val.goal + ` (` + val.plan_type + `)</h4>
                                                </div>
                                                <div class="content">
                                                <ul class="list-unstyled team-members">
                                                    <li>
                                                        <div class="row">
                                                            <div class="col-xs-9">
                                                                ` + val.description + `
                                                                <br />
                                                                <span class="text-muted"><small>` + val.from_date + `</small></span>
                                                                <span class="text-primary" style="color:#000;"><small>To</small></span>
                                                                <span class="text-success"><small>` + val.to_date + `</small></span>
                                                            </div>

                                                            <div class="col-xs-3 text-right">
                                                                <btn type="button" class="btn btn-sm btn-success btn-icon mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent actionBtn btn-sm btn btn-primary waves-effect waves-light update-one-plan-button updateModalBtn" id="updateModalBtn" data-target="#updatePlanModal" data-id='` + val._id + `'>
                                                                    <i class="ti-pencil"></i>
                                                                </btn>
                                                                <btn type="button" class="btn btn-sm btn-success btn-icon mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent actionBtn btn-sm btn btn-primary waves-effect waves-light read-o  ne-plan-button readModalBtn" id="readModalBtn" data-id='` + val._id + `'>
                                                                    <i class="ti-search"></i>
                                                                </btn>
                                                                <btn class="btn btn-sm btn-success btn-icon deleteModalBtn" id="deleteModalBtn" data-id='` + val._id + `'>
                                                                    <i class="ti-trash"></i>
                                                                </btn>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>`;
                                    });
                                    // end card
                                    read_plan_html+=`</div></div>`;
                                    // inject to 'page-content' of our app
                                    $("#planbody").html(read_plan_html);
                                });
                            }, interval);
                        });
                    });
                </script>
            </div>
        </div>
       <!--  <script>
            
        </script> -->
<?php require_once 'partials/footer.inc.php'; ?>