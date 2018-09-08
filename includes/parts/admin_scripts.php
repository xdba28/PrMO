<?php 
include_once '../../includes/parts/modals.php'; 
require_once "../../functions/account-verifier.php";
?>

<!-- Mainly scripts -->
<script src="../../assets/js/jquery-3.1.1.min.js"></script>
<script src="../../assets/js/popper.min.js"></script>
<script src="../../assets/js/bootstrap.js"></script>
<script src="../../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="../../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Peity -->
<script src="../../assets/js/plugins/peity/jquery.peity.min.js"></script>
<script src="../../assets/js/demo/peity-demo.js"></script>

<!-- Chosen -->
<script src="../../assets/js/plugins/chosen/chosen.jquery.js"></script>


<!-- Input Mask-->
<script src="../../assets/js/plugins/jasny/jasny-bootstrap.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="../../assets/js/inspinia.js"></script>
<script src="../../assets/js/plugins/pace/pace.min.js"></script>

<!-- Toast -->
<script src="../../assets/js/plugins/toastr/toastr.min.js"></script>

<!-- FooTable -->
<script src="../../assets/js/plugins/footable/footable.all.min.js"></script>

<!-- Steps -->
<script src="../../assets/js/plugins/steps/jquery.steps.min.js"></script>

<!-- Jquery Validate -->
<script src="../../assets/js/plugins/validate/jquery.validate.min.js"></script>


<!-- Typehead -->
<script src="../../assets/js/plugins/typehead/bootstrap3-typeahead.min.js"></script>


<!-- iCheck -->
<script src="../../assets/js/plugins/iCheck/icheck.min.js"></script>


<!-- Ladda -->
<script src="../../assets/js/plugins/ladda/spin.min.js"></script>
<script src="../../assets/js/plugins/ladda/ladda.min.js"></script>
<script src="../../assets/js/plugins/ladda/ladda.jquery.min.js"></script>


<!--External-->
<script src="../../assets/dropify/js/dropify.min.js"></script>


    <script>
        Dropzone.options.dropzoneForm = {
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 2, // MB
            dictDefaultMessage: "<strong>Drop files here or click to upload. </strong></br> (This is just a demo dropzone. Selected files are not actually uploaded.)"
        };

        $(document).ready(function(){

            var editor_one = CodeMirror.fromTextArea(document.getElementById("code1"), {
                lineNumbers: true,
                matchBrackets: true
            });

            var editor_two = CodeMirror.fromTextArea(document.getElementById("code2"), {
                lineNumbers: true,
                matchBrackets: true
            });

            var editor_two = CodeMirror.fromTextArea(document.getElementById("code3"), {
                lineNumbers: true,
                matchBrackets: true
            });

            var editor_two = CodeMirror.fromTextArea(document.getElementById("code4"), {
                lineNumbers: true,
                matchBrackets: true
            });


            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });

       });
    </script>

<!-- Page-Level Scripts -->


    <script>
        $(document).ready(function(){
            $("#wizard").steps();
            $("#form").steps({
                bodyTag: "fieldset",
                onStepChanging: function (event, currentIndex, newIndex)
                {
                    // Always allow going backward even if the current step contains invalid fields!
                    if (currentIndex > newIndex)
                    {
                        return true;
                    }

                    // Forbid suppressing "Warning" step if the user is to young
                    if (newIndex === 3 && Number($("#age").val()) < 18)
                    {
                        return false;
                    }

                    var form = $(this);

                    // Clean up if user went backward before
                    if (currentIndex < newIndex)
                    {
                        // To remove error styles
                        $(".body:eq(" + newIndex + ") label.error", form).remove();
                        $(".body:eq(" + newIndex + ") .error", form).removeClass("error");
                    }

                    // Disable validation on fields that are disabled or hidden.
                    form.validate().settings.ignore = ":disabled,:hidden";

                    // Start validation; Prevent going forward if false
                    return form.valid();
                },
                onStepChanged: function (event, currentIndex, priorIndex)
                {
                    // Suppress (skip) "Warning" step if the user is old enough.
                    if (currentIndex === 2 && Number($("#age").val()) >= 18)
                    {
                        $(this).steps("next");
                    }

                    // Suppress (skip) "Warning" step if the user is old enough and wants to the previous step.
                    if (currentIndex === 2 && priorIndex === 3)
                    {
                        $(this).steps("previous");
                    }
                },
                onFinishing: function (event, currentIndex)
                {
                    var form = $(this);

                    // Disable validation on fields that are disabled.
                    // At this point it's recommended to do an overall check (mean ignoring only disabled fields)
                    form.validate().settings.ignore = ":disabled";

                    // Start validation; Prevent form submission if false
                    return form.valid();
                },
                onFinished: function (event, currentIndex)
                {
                    var form = $(this);

                    // Submit form input
                    form.submit();
                }
            }).validate({
                        errorPlacement: function (error, element)
                        {
                            element.before(error);
                        },
                        rules: {
                            confirm: {
                                equalTo: "#password"
                            }
                        }
                    });
       });
    </script>
	
	<script>
	$(function() {
		$('.dropify').dropify();

		var drEvent = $('#dropify-event').dropify();
		drEvent.on('dropify.beforeClear', function(event, element) {
			return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
		});

		drEvent.on('dropify.afterClear', function(event, element) {
			alert('File deleted');
		});

		$('.dropify-fr').dropify({
			messages: {
				default: 'Glissez-déposez un fichier ici ou cliquez',
				replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
				remove: 'Supprimer',
				error: 'Désolé, le fichier trop volumineux'
			}
		});
	});
	</script>
	
	<script>
		function refreshPage(){
			window.location.reload();
		} 
	</script>	
	
	<script>
		$(document).ready(function () {
			$('#popOver0').popover();
			$('#popOver1').popover();
			$('#popOver2').popover();
			$('#popOver3').popover();
			$('#popOver4').popover();
			$('#popOver5').popover();
			$('#popOver6').popover();
			$('#popOver7').popover();
			$('#popOver8').popover();
			$('#popOver9').popover();
			$('#popOver10').popover();
			$('#popOver11').popover();
			$('#popOver12').popover();
			$('#popOver13').popover();
			$('#popOver14').popover();
			
			if(newuser == true){
				$('#new-user-modal').modal('show');
				
			}

        $('.footable').footable();
        $('.footable2').footable();
		$("#typeahead").typeahead({
			source: ["Job Order","Procurement Aid","Head Secretariat", "Director", "Staff"]
		});
		
        var tst_rdy = '<?php 
            if(Session::exists('toust')) echo Session::flash('toust');
            else echo "0";
        ?>';
        
        if(tst_rdy !== "0")
        toastr.success(tst_rdy);

        $('.chosen-select').chosen({width: "100%"});

			$('.i-checks').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green',
			});
					
			$('input').focus(function(){
			  $(this).parents('.form-group').addClass('focused');
			});

			$('input').blur(function(){
			  var inputValue = $(this).val();
			  if ( inputValue == "" ) {
				$(this).removeClass('filled');
				$(this).parents('.form-group').removeClass('focused');  
			  } else {
				$(this).addClass('filled');
			  }
			})          

		});

	</script>