<!-- Mainly scripts -->
<script src="../../assets/js/jquery-3.1.1.min.js"></script>
<script src="../../assets/js/popper.min.js"></script>
<script src="../../assets/js/bootstrap.js"></script>
<script src="../../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="../../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>



<!-- Peity -->

<script src="../../assets/js/plugins/peity/jquery.peity.min.js"></script>
<script src="../../assets/js/demo/peity-demo.js"></script>


<!-- Custom and plugin javascript -->
<script src="../../assets/js/inspinia.js"></script>
<script src="../../assets/js/plugins/pace/pace.min.js"></script>
<script src="../../assets/js/plugins/toastr/toastr.min.js"></script>


<!-- FooTable -->
<script src="../../assets/js/plugins/footable/footable.all.min.js"></script>

<script>
    $(document).ready(function() {

        var lineData = {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [
                {
                    label: "Example dataset",
                    backgroundColor: "rgba(26,179,148,0.5)",
                    borderColor: "rgba(26,179,148,0.7)",
                    pointBackgroundColor: "rgba(26,179,148,1)",
                    pointBorderColor: "#fff",
                    data: [28, 48, 40, 19, 86, 27, 90]
                },
                {
                    label: "Example dataset",
                    backgroundColor: "rgba(220,220,220,0.5)",
                    borderColor: "rgba(220,220,220,1)",
                    pointBackgroundColor: "rgba(220,220,220,1)",
                    pointBorderColor: "#fff",
                    data: [65, 59, 80, 81, 56, 55, 40]
                }
            ]
        };

        var lineOptions = {
            responsive: true
        };


        var ctx = document.getElementById("lineChart").getContext("2d");
        new Chart(ctx, {type: 'line', data: lineData, options:lineOptions});



    });
</script>

<!-- Page-Level Scripts -->
<script>
    $(document).ready(function() {

        $('.footable').footable();
        $('.footable2').footable();

        var tst_rdy = '<?php 
            if(Session::exists('toust')) echo Session::flash('toust');
            else echo "0";
        ?>';
        
        if(tst_rdy !== "0")
        toastr.success(tst_rdy);


        

    });

</script>