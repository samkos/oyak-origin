<?php include("../inc/conf.php"); ?>
<?php include("../inc/fonctions.php"); ?>
<?php

$exe_print="\"c:\\Program Files\\Ghostgum\\gsview\\gsprint.exe\"   ";
$exe_python="c:\\Python24\\python.exe ..\\print\\demon.pyw";

//$dir_imprime=""\Oyak\work\*";
$dir_imprime="\impprint\*";
$dir_imprime="test\*";

$header="";
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
    copy ("all.ps", "c:/Oyak/imprime.ps");
  }

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

  $find=array();
  $replace=array();
  
  $lines=file($file);

  $nb_ligne=0;

  foreach ($lines as $line) {
    $champs = split("!",$line);
    $x=array_shift($champs);
    $y=array_shift($champs);
    $what=array_shift($champs);

    $out=$out.'\put('.$x.','.(29-$y).'){' ;

    // texte simple
    if ($what=="TXT") {
      $text=array_shift($champs);
      $out=$out."$text";
    }

    // tableau
    if ($what=="TAB") {
      $intitules=split("=",array_shift($champs));
      $tailles=split("=",array_shift($champs));

      $out=$out."\n".'\begin{tabular}{';
      while ($taille=array_shift($tailles)) {
	$out=$out."|p{".$taille."cm}";
      }
      $out=$out."|}\n";
      $out=$out.'\hline '."\n";
      $nb_int=0;
      while ($intitule=array_shift($intitules)) {
	if ($nb_int) { $out = $out.'&';}
	$nb_int=$nb_int+1;
	$out=$out.'\textbf{'.$intitule.'} ';
	  }
      $out=$out.'\\\\ '."\n".'\hline'."\n";

      $nb_lin=0;
      while ($line=array_shift($champs)) {
	if ($nb_lin) { $out=$out.'\\\\ '."\n";}
	$nb_lin=$nb_lin+1;

	$cells=split("=",$line);
	$nb_int=0;

	while ($cell=array_shift($cells)) {
	  $bord="|";
	  if ($nb_int) { $out = $out.'&'; $bord="";}
	  $nb_int=$nb_int+1;
	  $out=$out.'\multicolumn{1}{'.$bord.'l|}{'.$cell.'} ';
	}
	
      }

      $out=$out.'\\\\ '."\n".'\hline'."\n";

      $out=$out.'\end{tabular}';
      

    }


    $out=$out."}\n";
  }
  
  $out=$out.$footer."\n\n";

  return $out;
}



  		

?>
