
<p class="bg-info">L'image doit être au format JPG, et de moins de 2Mo</p>
<form action="image.php" method="post" enctype="multipart/form-data">
<div class="form-group container containerfile">
<label for="monfichier">Envoyez votre image</label>
    <input type="file" name="monfichier" /><br />
</div>
<div class="form-group">
<label for="NomdelaBox">Entrez le nom de la Box</label>
	<input type="text" class="form-control" name="NomdelaBox" /><br />
</div>
 <div class="form-group">
    <button class="btn btn-success" type="submit" value=""/><i class="fa fa-youtube-play" aria-hidden="true"></i> Créer la vignette</button> 
    </div>
</form>