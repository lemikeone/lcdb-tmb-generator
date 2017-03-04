<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="mystyle.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>


<body>

<?php

function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){ 
        // creating a cut resource
        $cut = imagecreatetruecolor($src_w, $src_h); 

        // copying relevant section from background to the cut resource 
        imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h); 
        
        // copying relevant section from watermark to the cut resource 
        imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h); 
        
        // insert cut resource to destination image 
        imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct); 
    } 


move_uploaded_file($_FILES['monfichier']['tmp_name'], 'uploads/' . basename($_FILES['monfichier']['name']));

$source = imagecreatefromjpeg("uploads/" .$_FILES['monfichier']['name']. ""); // La photo est la source

$logo = imagecreatefrompng("logo3.png"); // logo
$cadre = imagecreatefrompng("cadre.png"); // cadre

$destination = imagecreatetruecolor(1280, 720); // On crée la miniature vide

// Les fonctions imagesx et imagesy renvoient la largeur et la hauteur d'une image
$largeur_source = imagesx($source);
$hauteur_source = imagesy($source);
$largeur_destination = imagesx($destination);
$hauteur_destination = imagesy($destination);
$largeur_logo = imagesx($logo);
$hauteur_logo = imagesy($logo);
$largeur_cadre = imagesx($cadre);
$hauteur_cadre = imagesy($cadre);

include 'resizeandcrop.php';

// On crée la miniature
imagecopyresampled($destination, $thumb, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, 1280, 720);

// On met le logo (source) dans l'image de destination (la photo)
imagecopymerge_alpha($destination, $logo, 1100, 20, 0, 0, $largeur_logo, $hauteur_logo, 100);
$blanc = imagecolorallocate($destination, 255, 255, 255);

// Font path and size
$font = 'Aller_Std_Bd.ttf';
$font_size = 85;
$angle = 0;
$text = htmlspecialchars($_POST['NomdelaBox']);

// Get Bounding Box Size
$text_box = imagettfbbox($font_size,$angle,$font,$text);

// Get your Text Width and Height
$text_width = $text_box[2]-$text_box[0];
$text_height = $text_box[7]-$text_box[1];


// On reduit la taille de la police si il depasse une certaine taille
while ( $text_width > 900) {
	$font_size--;
	$text_box = imagettfbbox($font_size,$angle,$font,$text);
	$text_width = $text_box[2]-$text_box[0];
	$text_height = $text_box[7]-$text_box[1];
}

// Calculate coordinates of the text (on ne se sert pas de y)
$x = ($largeur_destination/2) - ($text_width/2);
$y = ($hauteur_destination/2) - ($text_height/2);

// Calculate coordinates of the cadre
$largeurcadresurmesure = $text_width+150;
$xcadre = ($largeur_destination/2) - ($largeurcadresurmesure/2);

// We add the green layout for the text
imagecopymerge_alpha($destination, $cadre, $xcadre, 485, 0, 0, $largeurcadresurmesure, $hauteur_cadre, 100);

// On insere le texte
imagettftext($destination, $font_size, $angle, $x, 640, $blanc, $font, $text);

// On affiche l'image de destination qui a été fusionnée avec le logo
imagejpeg($destination, 'uploads/vignettes/vignette.jpg');
?>

<div class="fond">
<br>
<div class="text-center"><img src="logo-page.png" width="300px"></div>
<br>
<div class="container containerm">
<div class="text-center">
<h1>Créateur de vignette</h1>
</div>
<div class="text-center">
<?php 

echo '<br/><img class="img-thumbnail" src="uploads/vignettes/vignette.jpg" max-width="400">';
echo '<br/><br/><a class="btn btn-success" download="vignette-youtube" href="uploads/vignettes/vignette.jpg" role="button"><i class="fa fa-arrow-circle-down" aria-hidden="true"></i> Télécharger la vignette</a> <a class="btn btn-default" href="index.php" role="button"><i class="fa fa-undo" aria-hidden="true"></i> Créer une autre vignette</a>';

?>
<br/><br/><br/>
</div>
</div>
</div>


</body>
</html>