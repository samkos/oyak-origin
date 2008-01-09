<?php include("../inc/conf.php"); ?>
<?php include("../inc/fonctions.php"); ?>
<?php

$exe_print="\"c:\\Program Files\\Ghostgum\\gsview\\gsprint.exe\"   ";
$exe_python="c:\\Python24\\python.exe ..\\print\\demon.pyw";

//$dir_imprime=""\Oyak\work\*";
$dir_imprime="\impprint\*";
$dir_imprime="test\*";

$header=1;
$nb_lignes_imprime=18;

include("../inc/header.php");
$debug=0;

// lecture des masques
$dir=".";

$preambule=join("",file("$dir/preambule.tex"));
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

    if ($what=="TXT") {
      $text=array_shift($champs);
      $out=$out.'  \null   
                   \vspace{'.$y.'.cm}   \hspace{'.$x.'.cm}   
                   \begin{minipage}[t]{\linewidth}';
      $out=$out." $text ";
      $out=$out.'    
                   \vspace{-'.$y.'.cm}   \hspace{-'.$x.'.cm}   ';
      $out=$out.'                               \end{minipage}
            ';
    }

  }
  
  return $out;
}



  		

?>
