<html>
<head>
<script>
function test(selectBox) {
	var op = selectBox.options[selectBox.selectedIndex];
	var optgroup = op.parentNode;
	alert("selected option text is:  " + op.text + " \noptGroup label is:  " + optgroup.label);
}
</script>
</head>
<body>
<select name="nodeTypes" id="nodeTypes" onChange="test(this);">
    <option value="" selected>Please select ...</option>
    <optgroup label="Label1" id="Label1">
		<option value="1">One</option>
		<option value="2">Two</option>
		<option value="3">Three</option>
    </optgroup>
    <optgroup label="Label2" id="Label2">
		<option value="4">Four</option>
		<option value="5">Five</option>
		<option value="5">Six</option>
    </optgroup>
  </select>
</body>


<php?



?>
