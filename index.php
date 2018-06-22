<?php
########################
#                      #
# █████  ██  ██ █████  #
# ██  ██ ██  ██ ██  ██ #
# ██  ██ ██  ██ ██  ██ #
# █████  ██████ █████  #
# ██     ██  ██ ██     #
# ██     ██  ██ ██     #
# ██     ██  ██ ██     #
#                      #
########################
#
#
##################################################################
#                                                                #
# ██████ ██  ██ ██  ██  ████  ██████  ████   ████  ██  ██  ████  #
# ██     ██  ██ ███ ██ ██  ██ █ ██ █   ██   ██  ██ ███ ██ ██   █ #
# █████  ██  ██ ██████ ██       ██     ██   ██  ██ ██████   ██   #
# ██     ██  ██ ██ ███ ██  ██   ██     ██   ██  ██ ██ ███ █   ██ #
# ██      ████  ██  ██  ████   ████   ████   ████  ██  ██  ████  #
#                                                                #
##################################################################

function separatRGB($color){
    $color=str_replace('#','',$color);
    if (strlen($color)==3){
        $color=$color[0].$color[0].$color[1].$color[1].$color[2].$color[2];
    }
    $RGB=array();
    $RGB['r']=hexdec(substr($color, 0,2));
    $RGB['g']=hexdec(substr($color, 2,2));
    $RGB['b']=hexdec(substr($color, 4,2));  
    return $RGB;
}
function getURL(){
    $url = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] : 'https://'.$_SERVER["SERVER_NAME"];
    $url .= $_SERVER["SCRIPT_NAME"];
    return $url;
}

function drawShape($string='',$shape='',&$image,$x,$y,$sizex,$sizey,$color){
	
	if ($shape){
		$shape=randomChar($shape);
		if ($shape=='1'){
			#filled rectangle
			imagefilledrectangle($image,$x,$y,$x+$sizex,$y+$sizey,$color);
		}elseif ($shape=='2'){
			#filled ellipse
			imagefilledellipse ( $image , $x+($sizex/2),$y+($sizey/2)  , $sizex ,$sizey ,$color);
		}elseif ($shape=='3'){
			#empty rectangle
			imagerectangle($image,$x,$y,$x+$sizex,$y+$sizey,$color);
		}elseif ($shape=='4'){
			#empty ellipse
			imageellipse ($image,$x+($sizex/2),$y+($sizey/2)  , $sizex ,$sizey ,$color);
		}

	}
	
	if (!empty($string)){
		#add char from string
		imagettftext ($image,min($sizex,$sizey),1,$x,$y,$color,'assets/DejaVuSans.ttf',randomChar($string));
	}
}
function randomChar($string){
	$nb=mb_strlen($string)-1;
	$nb=rand(0,$nb);
	return  mb_substr($string,$nb,1);
}

function randomColor(){
	if (!defined('SAVE')){	define('SAVE',false);}
	$color='';
	for($i=1;$i<=3;$i++){
		$c=dechex(rand(0,255));
		if (strlen($c)==1){$c='0'.$c;}
		$color.=$c;
	}
	return $color;
}

function randomNb($nb1,$nb2){
	if (!defined('SAVE')){	define('SAVE',false);}	
	return rand($nb1,$nb2);
}

function normalizeColor($str){
	$color=preg_replace('#[^0-9A-Fa-f]#','',$str);
	$nb=strlen($color);
    if ($nb==3){
        $color=$color[0].$color[0].$color[1].$color[1].$color[2].$color[2];
    }elseif($nb>6){
    	$color=substr($color, 0,6);
    }elseif($nb<3){
    	$color.=str_repeat('0', 3-$nb);
    }
    return $color;
}
function returnColor($type=null,$index=0){
	global $palette;
	if ($type==0){
		#random color
		return $palette[array_rand($palette)];
	}elseif(!empty($palette[$index])){
		return $palette[$index];
	}else{return $palette[count($palette)-1];}
}
function secure($data=null){
    if (is_string($data)){
        return strip_tags($data);
    }else if (is_array($data)){
        return array_map('secure',$data);
    }
    return $data;
}

$formes=[
		'carré_plein'=>'■',
		'carré_vide'=>'□',
		'rond_plein'=>'●',
		'rond_vide'=>'◯',
		'losange_plein'=>'◆',
		'losange_vide'=>'◇',
		'pentagones_plein'=>'⬟⭓',
		'pentagones_vide'=>'⬠⭔',
		'triangles_plein'=>'▲◀▼▶',
		'triangles_vides'=>'△▷▽◁',
		'chiffres'=>'0123456789',
		'lettres_majuscules'=>'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
		'lettres_minuscules'=>'abcdefghijklmnopqrstuvwxyz',
		'flèches'=>'↖↘↗↙←↑→↓',
		'étoile'=>'★',
		'crâne'=>'☠',
		'risque_biologique'=>'☣',
		'radiation'=>'☢',
		'coeur'=>'♥',
		'trèfle'=>'♣',
		'pique'=>'♠',
		'carreau'=>'♦',
	];

if (!empty($_GET)){
	###############################
	#                             #
	#  ████  ██  ██  ████  ██████ #
	#   ██   ███ ██   ██   █ ██ █ #
	#   ██   ██████   ██     ██   #
	#   ██   ██ ███   ██     ██   #
	#  ████  ██  ██  ████   ████  #
	#                             #
	###############################
	

	
	if (!is_dir('pics')){mkdir('pics/',0666);}	
	if (isset($_GET['w']) && intval($_GET['w'])>0){$w=intval($_GET['w']);}else{$w=512;}
	if (isset($_GET['h']) && intval($_GET['h'])>0){$h=intval($_GET['h']);}else{$h=512;}
	if (isset($_GET['back'])){$back=normalizeColor($_GET['back']);}else{$back=randomColor();}
	if (isset($_GET['c1'])){$c1=normalizeColor($_GET['c1']);}else{$c1=randomColor();}
	if (isset($_GET['c2'])){$c2=normalizeColor($_GET['c2']);}else{$c2=randomColor();}
	if (isset($_GET['type'])){$type=intval($_GET['type']);}else{$type=randomNb(0,10);}
	if (isset($_GET['max_size'])){$max_size=intval($_GET['max_size']);}else{$max_size=randomNb(round(max($w,$h)/5),round(max($w,$h)/4));}
	if (isset($_GET['min_size'])){$min_size=intval($_GET['min_size']);}else{$min_size=randomNb(2,5);}
	if (isset($_GET['shape'])){$shape=$_GET['shape'];}else{$shape='';}
	if (isset($_GET['string'])){$string=urldecode($_GET['string']);}else{$string='';}
	if (!empty($_GET['borders_only'])){$borders_only=1;}else{$borders_only=0;}
	if (!empty($_GET['emboss'])){$emboss=1;}else{$emboss=0;}
	if (!empty($_GET['mean'])){$mean=1;}else{$mean=0;}
	if (isset($_GET['blur'])){$blur=intval($_GET['blur']);}else{$blur=0;}
	if (isset($_GET['pixel'])){$pixel=intval($_GET['pixel']);}else{$pixel=0;}
	

	if (empty($string)&&empty($shape)){
		if (rand(1,2)==1){			
			$shape='1234';
		}else{
			$string=$formes[array_rand($formes)];
		}
	}
	if ($min_size<2){$min_size=2;}
	if ($max_size>$w||$max_size>$h){$max_size=min($w,$h);}
	if ($min_size>$w||$min_size>$h){$min_size=min($w,$h);}

	if (isset($_GET['urlonly'])){
		echo getURL();
		echo '?w='.$w.'&h='.$h.'&max_size='.$max_size.'&min_size='.$min_size.'&type='.$type.'&back='.$back.'&c1='.$c1.'&c2='.$c2.'&shape='.$shape;
		exit;
	}


	$filename='pics/'.implode('-', [$w,$h,$c1,$c2,$type,$max_size,$min_size,$shape,$back]).'.png';
	$get=implode('&', [$w,$h,$c1,$c2,$type,$max_size,$min_size,$shape,$back,urlencode($string)]);
	if (!defined('SAVE')){define('SAVE',!isset($_GET['dontsave']));}
	if (is_file($filename)){
		header ("Content-type: image/png");
		exit(file_get_contents($filename));
	}

	$unit=min($min_size,$max_size);
	$color_steps=round($h/$unit);
	$c1=separatRGB($c1);
	$c2=separatRGB($c2);
	$back=separatRGB($back);
	$add=[
		'r'=>round(($c2['r']-$c1['r'])/$color_steps),
		'g'=>round(($c2['g']-$c1['g'])/$color_steps),
		'b'=>round(($c2['b']-$c1['b'])/$color_steps),
	];
	if (!defined('SAVE')){define('SAVE',!isset($_GET['dontsave']));}# allow the preview generation without saving all tests !
	

	######################################
	#                                    #
	#  ████  ██████  ████  █████  ██████ #
	# ██   █ █ ██ █ ██  ██ ██  ██ █ ██ █ #
	#   ██     ██   ██████ █████    ██   #
	# █   ██   ██   ██  ██ ██  ██   ██   #
	#  ████   ████  ██  ██ ██  ██  ████  #
	#                                    #
	######################################
	$image = @imagecreatetruecolor($w, $h) or die ("Erreur lors de la création de l'image");
	# background

	$back=imagecolorallocate ($image, $back['r'], $back['g'], $back['b']);
	imagefilledrectangle ( $image , 0,0  , $w ,$h , $back );

	# color palette
	for ($step=1;$step<=$color_steps;$step++){
		$c1['r']=max(0,$c1['r']+$add['r']);
		$c1['g']=max(0,$c1['g']+$add['g']);
		$c1['b']=max(0,$c1['b']+$add['b']);
		$palette[$step]=ImageColorAllocate ($image, $c1['r'], $c1['g'], $c1['b']);
	}
	$palette_nb=count($palette);

	# draw shapes
	if (empty($type)){
		# Noise
		for ($y=0;$y<=$h;$y=$y+$unit){		
			for ($x=0;$x<$w;$x=$x+$unit){
				drawShape($string,$shape,$image,$x,$y,$unit,$unit,returnColor());
			}			
		}
	}	

	if ($type==1){
		# x distorded Noise
		$size=$min_size;
		for ($y=0;$y<=$h;$y=$y+$unit){		
			for ($x=0;$x<$w;$x=$x+$size){				
				drawShape($string,$shape,$image,$x,$y,$size,$unit,returnColor());
				$size=rand($min_size,$max_size);
			}
		}
	}
	if ($type==2){
		# y distorded Noise
		$size=$min_size;
		for ($x=0;$x<=$w;$x=$x+$unit){		
			for ($y=0;$y<$h+$size;$y=$y+$size){				
				drawShape($string,$shape,$image,$x,$y,$unit,$size,returnColor());
				$size=rand($min_size,$max_size);
			}
		}
	}
	if ($type==3){
		# distorded Noise
		$size=$min_size;
		for ($x=0;$x<=$w;$x=$x+$size){		
			for ($y=0;$y<$h+$size;$y=$y+$size){				
				drawShape($string,$shape,$image,$x,$y,$size,$size,returnColor());
				$size=rand($min_size,$max_size);
			}
		}
	}
	if ($type==4&&empty($string)){
		# horizontal lines	no gradient
		for ($y=0;$y<$h;$y=$y+$size){
			$size=rand($min_size,$max_size);
			$color_index=max(round(($y*$palette_nb)/$h),1);
			drawShape($string,$shape,$image,0,$y,$w,$size,returnColor(0,$color_index));
		}
	}
	if ($type==5&&empty($string)){
		# vertical lines	no gradient	
		for ($x=0;$x<$w;$x=$x+$size){
			$size=rand($min_size,$max_size);
			$color_index=max(round(($x*$palette_nb)/$w),1);
			drawShape($string,$shape,$image,$x,0,$size,$h,returnColor(0,$color_index));
		}
	}

	if ($type==6){
		# x distorded gradient
		$size=$min_size;
		for ($y=0;$y<=$h;$y=$y+$unit){		
			for ($x=0;$x<$w;$x=$x+$size){	
				$color_index=max(round(($x*$palette_nb)/$w),1);			
				drawShape($string,$shape,$image,$x,$y,$size,$unit,returnColor(1,$color_index));
				$size=rand($min_size,$max_size);
			}
		}
	}
	if ($type==9&&!empty($string)){
		# horizontal string gradient
		for ($y=0;$y<=$h;$y=$y+$unit){
		$color_index=max(round(($y*$palette_nb)/$w),1);		
			for ($x=0;$x<$w;$x=$x+$unit){

				drawShape($string,$shape,$image,$x,$y,$unit,$unit,returnColor(1,$color_index));
			}			
		}
	}
	if ($type==10&&!empty($string)){
		# vertical string gradient
		for ($x=0;$x<=$w;$x=$x+$unit){
		$color_index=max(round(($x*$palette_nb)/$h),1);		
			for ($y=0;$y<$h+$unit;$y=$y+$unit){
				drawShape($string,$shape,$image,$x,$y,$unit,$unit,returnColor(1,$color_index));
			}			
		}
	}
	if ($type==4&&!empty($string)){
		# horizontal string gradient
		for ($y=0;$y<=$h;$y=$y+$unit){	
			for ($x=0;$x<$w;$x=$x+$unit){
				drawShape($string,$shape,$image,$x,$y,$unit,$unit,returnColor());
			}			
		}
	}
	if ($type==5&&!empty($string)){
		# vertical string gradient
		for ($x=0;$x<=$w;$x=$x+$unit){		
			for ($y=0;$y<$h+$unit;$y=$y+$unit){
				drawShape($string,$shape,$image,$x,$y,$unit,$unit,returnColor());
			}			
		}
	}
	if ($type==7){
		# y distorded gradient
		$size=$min_size;
		for ($x=0;$x<=$w;$x=$x+$unit){		
			for ($y=0;$y<$h+$size;$y=$y+$size){	
				$color_index=max(round(($y*$palette_nb)/$h),1);			
				drawShape($string,$shape,$image,$x,$y,$unit,$size,returnColor(1,$color_index));
				$size=rand($min_size,$max_size);
			}
		}
	}
	if ($type==8){
		# distorded gradient
		$size=$min_size;
		for ($x=0;$x<=$w;$x=$x+$size){		
			for ($y=0;$y<$h+$size;$y=$y+$size){				
				$color_index=max(round(($y*$palette_nb)/$h),1);			
				drawShape($string,$shape,$image,$x,$y,$size,$size,returnColor(1,$color_index));
				$size=rand($min_size,$max_size);
			}
		}
	}
	if ($type==9&&empty($string)){
		# horizontal lines	 gradient
		for ($y=0;$y<$h;$y=$y+$size){
			$size=rand($min_size,$max_size);
			$color_index=max(round(($y*$palette_nb)/$h),1);
			drawShape($string,$shape,$image,0,$y,$w,$size,returnColor(1,$color_index));
			
		}
	}
	if ($type==10&&empty($string)){
		# vertical lines	 gradient	
		for ($x=0;$x<$w;$x=$x+$size){
			$size=rand($min_size,$max_size);
			$color_index=max(round(($x*$palette_nb)/$w),1);
			drawShape($string,$shape,$image,$x,0,$size,$h,returnColor(1,$color_index));
		}
	}

	if ($blur>0){
		for ($b=0;$b<=$blur;$b++){imagefilter($image, IMG_FILTER_GAUSSIAN_BLUR, $blur);}
	}

	if ($pixel){
		imagefilter($image, IMG_FILTER_PIXELATE, $pixel);
	}
	if ($borders_only){
		imagefilter($image, IMG_FILTER_EDGEDETECT);
	}
	if ($emboss){
		imagefilter($image, IMG_FILTER_EMBOSS);
	}
	if ($mean){
		imagefilter($image, IMG_FILTER_MEAN_REMOVAL);
	}

	header ("Content-type: image/png");
	if (SAVE){
		ImagePng($image,$filename);
		chmod($filename,0644);
	}
	ImagePng($image);
	imagedestroy( $image );
}


################################
#                              #
# ██  ██ ██████ █     █ ████   #
# ██  ██ █ ██ █ ██   ██  ██    #
# ██  ██   ██   ███ ███  ██    #
# ██████   ██   ███████  ██    #
# ██  ██   ██   ██ █ ██  ██    #
# ██  ██   ██   ██   ██  ██ ██ #
# ██  ██  ████  ██   ██ ██████ #
#                              #
################################
?><!DOCTYPE html>
<html>
<head>
	<title>GenPic</title>
	<link href="assets/goofi.php?family=Abel" rel="stylesheet"> 
	<link rel="stylesheet" type="text/css" href="assets/style.css">
	<link rel="stylesheet" type="text/css" href="assets/mui.min.css">
	<link rel="stylesheet" type="text/css" href="assets/icons.css">
	<link rel="icon" type="image/png" href="assets/favicon.png">
</head>
<body>
	<header style="background-image:url('index.php?w=1200&h=200')">
		<div class="title"><img src="assets/favicon.png"/> GenPic</div>
	</header>
	<h1> Générateur d'images de fond </h1>
	<div id="wrapper" >
		
		

		<section class="tools mui-panel">
			<ul class="mui-tabs__bar">
				<li class="mui--is-active"><a data-mui-toggle="tab" data-mui-controls="pane-default-1" class="icon-resize-full"> Tailles</a></li>
				<li><a data-mui-toggle="tab" data-mui-controls="pane-default-2" class="icon-eyedropper"> Couleurs</a></li>
				<li><a data-mui-toggle="tab" data-mui-controls="pane-default-3">⬟ Motifs</a></li>
				<li><a data-mui-toggle="tab" data-mui-controls="pane-default-4" class="icon-barcode"> Modèle</a></li>
				<li><a data-mui-toggle="tab" data-mui-controls="pane-default-5" >◐ Filtre</a></li>
			</ul>
			<div class="mui-tabs__pane mui--is-active" id="pane-default-1">
				
				
				<li class="mui-textfield">
					<label for="w"><span class="icon-resize-horizontal"></span> Largeur</label><input type="text" name="w" id="w" placeholder="512px par défaut">
				</li>
				<li class="mui-textfield">
					<label for="h"><span class="icon-resize-vertical"></span> Hauteur</label><input type="text" name="h" id="h" placeholder="512px par défaut">
				</li>
				<li class="mui-textfield" title="Vide = aléatoire">
					<label for="max_size"><span class="icon-resize-full"></span> Taille max des points</label><input type="text" name="max_size" id="max_size" placeholder="aléatoire">
				</li>
				<li class="mui-textfield" title="Vide = aléatoire">
					<label for="min_size"><span class="icon-resize-small"></span> Taille min des points</label><input type="text" name="min_size" id="min_size" placeholder="aléatoire">
				</li>
			</div>
			<div  class="mui-tabs__pane" id="pane-default-2">
				<li class="mui-textfield" title="Vide = aléatoire">
					<label for="back"><span class="icon-eyedropper"></span> Couleur de fond</label><input class="jscolor{refine:false,required:false}" type="text" name="back" id="back"  placeholder="aléatoire">
				</li>
				<li class="mui-textfield" title="Vide = aléatoire">
					<label for="c1"><span class="icon-eyedropper"></span> Couleur 1</label><input class="jscolor{refine:false,required:false}" type="text" name="c1" id="c1" placeholder="aléatoire">
				</li>
				<li class="mui-textfield" title="Vide = aléatoire">
					<label for="c2"><span class="icon-eyedropper"></span> Couleur 2</label><input class="jscolor{refine:false,required:false}" type="text" name="c2" id="c2" placeholder="aléatoire">
				</li>
			</div>
			<div  class="mui-tabs__pane" id="pane-default-3">
				<div class="formes">
					<h3>⬛ Formes</h3>						
					<input type="checkbox" name="shape_check" value="1" id="rectangle"/>
					<label for="rectangle" title=" rectangles">⬛</label>
					<input type="checkbox" name="shape_check" value="2" id="disque"/>
					<label for="disque" title="disque" >⬤</label>
					<input type="checkbox" name="shape_check" value="3" id="rectangle_vide"/>
					<label for="rectangle_vide" title="rectangle vide" >□</label>
					<input type="checkbox" name="shape_check" value="4" id="ellipses"/>
					<label for="ellipses" title="ellipses">◯</label>
				</div>
				<div class="chars">
					<h3>★ Caractères</h3>
					<?php
						foreach ($formes as $key => $value) {
							echo'
								<input type="checkbox" name="string_check" value="'.$value.'" id="'.$key.'"/>
								<label for="'.$key.'" title="'.str_replace('_', ' ', $key).'">'.mb_substr($value,0,1).'</label>
								
							';
						}
					?>
				</div>
				<div class="chars mui-textfield">
					<h3>★ Autres caractères</h3>
					<input type="text" name="string_chars" placeholder="tapez les caractères à utiliser"/> 
					
				</div>
			</div>			
	
			<div class="mui-tabs__pane types" id="pane-default-4">
				
						<li><label><input type="radio" name="type" value="-1">aléatoire</label></li>
						<li><label><input type="radio" name="type" value="0">bruit homogène</label></li>
						<li><label><input type="radio" name="type" value="1">bruit distordu en X</label></li>
						<li><label><input type="radio" name="type" value="2">bruit distordu en Y</label></li>
						<li><label><input type="radio" name="type" value="3">bruit distordu</label></li>
						<li><label class="nochars" ><input type="radio" name="type" value="4">lignes horizontales</label></li>
						<li><label class="nochars" ><input type="radio" name="type" value="5">lignes verticales</label></li>
						<li><label class="nochars" ><input type="radio" name="type" value="9">lignes horizontales en dégradé</label></li>
						<li><label class="nochars" ><input type="radio" name="type" value="10">lignes verticales en dégradé</label></li>
						<li><label><input type="radio" name="type" value="6">dégradé distordu en X</label></li>
						<li><label><input type="radio" name="type" value="7">dégradé distordu en Y</label></li>
						<li><label><input type="radio" name="type" value="8">dégradé distordu</label></li>

			</div>	
			<div class="filters mui-tabs__pane types" id="pane-default-5">
				
						<li>Net <input type="range" name="blur" value="0" defaultValue="0" min="0" step="1" max="10"> Flou</li>
						<li>Normal <input type="range" name="pixel" value="0" defaultValue="0" min="1" step="1" max="20"> Pixélisé</li>
						<li><label><input type="checkbox" name="borders_only" value="1" id="borders_only"/> Bords seulement</label></label>
						<li><label><input type="checkbox" name="mean" value="1" id="mean"/> Ajouter des bords</label></li>
						<li><label><input type="checkbox" name="emboss" value="1" id="emboss"/> Embosser</label></li>
			</div>
		</section>
		<section class="view mui-panel">
			<img id="result" src="assets/fond_img.png"/>
			<button class="mui-btn" id="generate"><span class="icon-cog-alt"></span> Générer</button>
			<div id="link" class="mui-btn" ></div>
		</section>



	</div>
	<footer>GenPic - générateur d'images <a href="http://github.com/broncowdd/genpic" class="icon-github"> libre et opensource</a> par <a href="http://warriordudimanche.net">Bronco</a> (utilise <a href="http://muicss.com">muicss</a> et <a href="http://jscolor.com">jscolor</a>)</footer>
	<script type="text/javascript">url="<?php echo getURL();?>";</script>
	<script type="text/javascript" src="assets/VanillaJS.js"></script>
	<script type="text/javascript" src="assets/jscolor.js"></script>
	<script type="text/javascript" src="assets/events.js"></script>
	<script type="text/javascript" src="assets/mui.min.js"></script>
</body>
</html>