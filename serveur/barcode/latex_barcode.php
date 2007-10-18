<?php include("../inc/conf.php"); ?>
<?php include("../inc/fonctions.php"); ?>



<?php include("../inc/header.php"); 

/**
 * Increases the max. allowed time to run a script
 */
@set_time_limit(300);

print "$action ...<br />";

if ($action=="print") {
	 foreach (array_keys($GLOBALS) as $var) {
   				 if (preg_match("/^choisis([0-9]{1,2})/",$var,$reg)) {
					 		print $GLOBALS[$var]." : ".$GLOBALS["quantite".$reg[1]]."x".$GLOBALS["produit".$reg[1]]."<BR>";
							$barcodes[$reg[1]]=$GLOBALS["choisis".$reg[1]];
							$quantites[$reg[1]]=$GLOBALS["quantite".$reg[1]];
							$produits[$reg[1]]=$GLOBALS["produit".$reg[1]];
						}
	}
//  $GLOBALS[$var]=$_SESSION[$var];
   print "----------------------------------";
	 //print_r($barcodes);												
	 $ftex=fopen($tex_file,"w");
 	 $fpython=fopen($python_file,"w");							

   fwrite($ftex,
'\documentclass{article}

\usepackage[greek,francais]{babel}
\usepackage[latin1]{inputenc}
\usepackage[T1]{fontenc}
\usepackage{graphicx}

\begin{document}
\begin{tabular}{ccccccccc}
');

	 fwrite($fpython,"codebarlist = [\\");
	 
	 $nb_lignes=0;
   foreach (array_keys($barcodes) as $key) {
	   for ($i=1;$i<=$quantites[$key];$i++) {
		    fwrite($ftex, sprintf("\includegraphics{%s.eps} & \\hspace{1cm} & ",$barcodes[$key]) );
		 }
		 fwrite ($ftex," \\\\ \n ");
	   for ($i=1;$i<=$quantites[$key];$i++) {
		 		fwrite($ftex,sprintf(" %s  & \\hspace{1cm} & ",$produits[$key]) );
		 }
	   fwrite ($ftex," \\\\\n \\vspace{2cm} \\\\ \n" );
			
			$python_line=sprintf('
			    ("%s", "%s", "%s"),',$barcodes[$key],$price,$produits[$key], $barcodes[$key]);			
		  fwrite ($fpython, $python_line);
			
			$nb_lignes=$nb_lignes+1;
			
			if ($nb_lignes==3) {
			  	   fwrite ($ftex," \\\\\n  \\end{tabular} \\ \\eject \n" );
						 fwrite ($ftex," \\begin{tabular}{ccccccccc} \n");
						 $nb_lignes=0;
			}
		}
		
		fwrite ($ftex,"\\end{tabular} \\end{document}\n");
    fwrite($fpython,"];
		");
	 
	 fclose($ftex);
	 fclose($fpython);
	 
	 system("compile.bat ",$status);
	 print "resultat-> $status";

	 system("print.bat  ",$status);
	 print "resultat-> $status";
}



?>

<script language="JavaScript" type="text/javascript">





function loadPage() {

 document.location.href = "../welcome/index.php";
}

//loadPage();



</script>



 
<br>
<?php include("../inc/footer.php"); ?>
