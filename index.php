<h1> Excel Form</h1>
<form method="POST" action="action.php" enctype="multipart/form-data">
<div><label>Start id</label>
<input  name="startid" type="text"></div><br>
<div><label>Sezona</label>
<input name="season"></div>
<div><br>
<label>Upload excel file</label>
<input type="file" name="fileToUpload"></div><br><br>
<textarea placeholder="Description" name="description"style="width:500px;height:200px;margin-bottom:20px"></textarea><br>
<button type="Submit" name="submit">Submit</button>
</form>