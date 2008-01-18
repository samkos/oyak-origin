<?php include("../inc/conf.php"); ?>
<?php include("../inc/fonctions.php"); ?>
<?php

$exe_print="\"c:\\Program Files\\Ghostgum\\gsview\\gsprint.exe\"   ";
$exe_python="c:\\Python24\\python.exe ..\\print\\demon.pyw";

//$dir_imprime=""\Oyak\work\*";
$dir_imprime="\impprint\*";
//$dir_imprime="test\*";

$header="";
$orientation="portrait";
$nb_lignes_imprime=18;

include("../inc/header.php");
$debug=0;

// lecture des masques
$dir=".";

$preambule=join("",file("$dir/preambule.tex"));
$header=join("",file("$dir/header.tex"));
$footer=join("",file("$dir/footer.tex"));
$conclusion=join("",file("$dir/conclusion.tex"));
$printer="default";
$copies=1;


$i=0;
$filenames=glob($dir_imprime);
if ($filenames) {
  $file_out=fopen("all.tex","w");
  fwrite($file_out,$preambule);

  foreach ($filenames as $filename) {
    if ($i>0) {
      fwrite($file_out,"\\clearpage");
    }
    $i=$i+1;
    echo "<BR> Traitement impression $filename................................................";
    $out=make_imprime($filename);
    fwrite($file_out,$out);
    echo "<BR> Effacement $filename NON FAIT   NON FAIT.......................................";
    //unlink($filename);
  }

  fwrite($file_out,$conclusion);
  fclose($file_out);

  system("compile.bat > out",$status);
  //print "res=$status";
  print "<BR> $i crées<BR> ";

  @mkdir ("c:/Oyak/ToPrint",0755);
  if ($printer=="default") {
    copy ("all.ps", "c:/Oyak/ToPrint/imprime.ps");
    copy ("all.ps", "c:/Oyak/imprime.ps");
  }
  else {
    @mkdir ("c:/Oyak/ToPrint/$printer",0755);
    copy ("all.ps", "c:/Oyak/ToPrint/$printer/imprime.ps");
    copy ("all.ps", "c:/Oyak/imprime.ps");  }

}
else {
  print "pas de imprime en attente <BR>";
}


print "<BR> <a href='../admin/index.php>  Retour Administration\n";




function make_imprime ($file) {
  global $debug, $header, $footer,$body,$body_vide,
    $nb_lignes_imprime,$footer2,$header2,$printer,$copies;
  

  $document="";
  $nb_ligne=0;
  $total=0;
  $out=$header;

  $hline="\n".' \hline'."\n";
  
  $lines=file($file);

  $nb_ligne=0;

  foreach ($lines as $line) {
    $champs = split("!",$line);
    $what=array_shift($champs);

    if (ereg("^Z0,1",$what))  { 
      // nom de l'imprimante, nombre d'impression, type de document
      $printer=array_shift($champs);
      $copies=array_shift($champs);
      $document=array_shift($champs);
			$orientation=array_shift($champs);
    }
    else {


      $x=array_shift($champs);
      $y=array_shift($champs);

      $out=$out.'\put('.$x.','.(29-$y).'){' ;

      // texte simple
      if ($what=="TXT") {
	$text=array_shift($champs);
	$out=$out."$text";
      }

      // tableau
      if ($what=="TAB") {
	$tailles=split("=",array_shift($champs));

	$out=$out."\n".'\begin{tabular}{';

	$col=1;
	while ($taille=array_shift($tailles)) {
	  $format_cell[$col]="p{".$taille."cm}";
	  $out=$out.$format_cell[$col];
	  $col=$col+1;
	}
	$out=$out."}\n";

	for ($c=0;$c<$col-2;$c++) {
	  $out = $out." & ";
	}
	$out=$out.'\\\\';

	$nb_lin=0;
	while ($line=array_shift($champs)) {
	  $cells=split("=",$line);
	  $nb_int=0;

	  $col=1;
	  while ($cell=array_shift($cells)) {

	    $fields=split(";",$cell);
	    $texte=array_shift($fields);
	    $format=array_shift($fields);
	    $masque='\multicolumn{1}{'.$format_cell[$col].'}{%s}';
		
	    // y a-t-il un format associe a la scene?
	    if ($format) {		
	      $cadrage=substr($format,0,1);	  
	      $bords  =substr($format,1,1);	  
	      $couleur=substr($format,2,1);	  
	      $font   =substr($format,3,1);	  
	      $nb_cols=substr($format,4,2);
	      if (!$nb_cols) {$nb_cols=1;}	  

	      //$out=$out."\n\n %===> texte=$texte; format=$format; cadrage='$cadrage'; bords='$bords'; couleur='$couleur'; font='$font'; nb_cols='$nb_cols';\n";
	    
	      $bord_left=""; $bord_right="";
	      switch($bords) {
	      case "g": $bord_left="|";                  break; 
	      case "d": $bord_right="|";                 break; 
	      case "c": $bord_left="|"; $bord_right="|"; break;
	      default : $bord_left=""; $bord_right="";    
	      }

	    
	      if ($cadrage=="d") {
		$cadrage="r";
	      }
	    
	      $bord_left=""; $bord_right="";
	      switch($bords) {
	      case "g": $bord_left="|";                  break; 
	      case "d": $bord_right="|";                 break; 
	      case "c": $bord_left="|"; $bord_right="|"; break;
	      default : $bord_left=""; $bord_right="";    
	      }

	      switch($font) {
	      case "g": $in='\textbf{%s}'; break; 
	      case "i": $in='\textsl{%s}'; break;
	      default : $in='%s';    
	      }

	      $masque='\multicolumn{'.$nb_cols.'}{'.$bord_left.$cadrage.$bord_right.'}{'.$in.'}';

	      switch($cadrage) {
	      case "T": $masque=$hline; break; 
	      case "t": $masque='\cline{'.$col.'-'.($col+$nb_cols-1).'}';  break;
	      case ".": $cadrage="l"; break;
	      default : $cadrafe="l";
	      }

	    }
		
	    if ($col>1) { $out = $out.'&';}
	    $col=$col+1;
	    $out=$out.sprintf($masque,$texte);
	  }

	  if ($masque!=$hline) {
	    $out=$out.'\\\\ '."\n";
	  }
	}


	$out=$out.'\end{tabular}';
      

      }


      $out=$out."}\n";

    }
  }
  
  $out=$out.$footer."\n\n";


  $out=ereg_replace("__PETIT__","{\\tiny ",$out);
  $out=ereg_replace("__GRAS__","\\textbf{ ",$out);
  $out=ereg_replace("__GRIS__","\\colorbox[gray]{0.8}{ ",$out);
  $out=ereg_replace("__petit__","}",$out);
  $out=ereg_replace("__gras__","}",$out);
  $out=ereg_replace("__gris__","}",$out);
	
	if (strstr($orientation,"PAYSAGE")) {
		 $out='\begin{landscape}'."\n".$out."\n".'\end{landscape}';
  }
	$out=$out.$orientation;
	
  return $out;
}



  		

?>
