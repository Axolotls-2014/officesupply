<html>
<head>
	<h2>register here...</h2>
	
</head>
<body>
<form id="form1">
	First Name:<input type="text" name="fname" id="field1"><br>
	Last Name:<input type="text" name="lname"><br>
	Designation:<input type="text" name="Designation"><br>
	Email:<input type="text" name="email"><br>
	<input type="submit" name="submit" value="submit" id="btn">
</form>

	<script type="text/javascript">
	$(document).ready(function(){

		$("#form1").validate({
		   rules: {
		     field1: "required"
		   },
		   messages: {
		     field1: "Please specify your name"

		   }
		})

		$('#btn').on('click', function() {
		    $("#form1").valid();
		});
	});
	</script>

	<script type="text/javascript">
		$(document).ready(function(e){
			$('#form1').submit(function(e){

			})	
		});
	</script>
</body>


</html>

<!DOCTYPE html>
<html>
<head>
	<title></title>

	<!-- include all css files. -->
</head>
<body>
	<table>
		<tr>
			<td>
					
			</td>
			<td>
				
			</td>
		</tr>
		<tr>
			<td>
				
			</td>
			<td>
				
			</td>
		</tr>
		<tr>
			<td>
				
			</td>
			<td>
				
			</td>
		</tr>
		<tr>
			<td colspan="2">

			</td>
		</tr>

	</table>


</body>


<!-- include all js files -->
<script src=" https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>

</html>