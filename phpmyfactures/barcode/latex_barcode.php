<?php include("../inc/conf.php"); ?>
<?php include("../inc/fonctions.php"); ?>



<?php include("../inc/header.php"); 

/**
 * Increases the max. allowed time to run a script
 */
@set_time_limit(300);

$nb_per_line=3;
$nb_per_page=8;
$format="|c|c|c|";

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
  '\documentclass[a4paper]{article}
   %
   \usepackage{graphicx}
   %
   \setlength{\voffset}{-6.2cm}
   \setlength{\hoffset}{-3.3cm}

   \setlength{\oddsidemargin}{0pt}
   \setlength{\evensidemargin}{0.5cm}

   \setlength{\textwidth}{550pt}

   \setlength{\topmargin}{1cm}
   \setlength{\textheight}{27cm}

   \setlength{\headheight}{90pt}

   \setlength{\headsep}{0pt}
   \setlength{\parindent}{1cm}
   \setlength{\parskip}{0.2cm}

   \setlength{\marginparwidth}{0pt}
   \setlength{\marginparsep}{0pt}
   %
   \begin{document}

   \begin{small}
   \begin{tabular}{'.$format.'}
   \hline');

   fwrite($fpython,"codebarlist = [\\");
	 
   $nb_lignes=0;



// capture info code barre

$sql_query = "select id,titre,stock,barcode from ".$prefixe_table."produits  ";

print "<BR> $sql_query <BR>";
$req = mysql_query("$sql_query ");

$nb=0;

 foreach (array_keys($barcodes) as $key) {
   for ($i=1;$i<=$quantites[$key];$i++) {
     $python_line=sprintf('
			    ("%s", "%s", "%s"),',$barcodes[$key],$produits[$key],"99.99", $barcodes[$key]); 
     fwrite ($fpython, $python_line);
     fwrite ($fpython, $python_line);

     fwrite($ftex, sprintf("
                            \includegraphics[height=3.2 cm,width=6.5 cm]{%s.eps}  ",$barcodes[$key]) );
     $nb=$nb+1;
     if ($nb<$nb_per_line) {
       fwrite($ftex,"&");
     }
     else {
       $nb=0;
       $nb_lignes=$nb_lignes+1;
       fwrite($ftex," \\\\ \\hline  ");
     }
  
     if ($nb_lignes==$nb_per_page) {
       fwrite ($ftex,' \\ \hline \end{tabular} \eject \n' );
       fwrite ($ftex,'\begin{tabular}{'.$format.'} \hline');
       $nb_lignes=0;
     }
   }
 }
}	
fwrite ($ftex,"\\hline \\end{tabular}    \\end{small} \\end{document}\n");
fwrite($fpython,"];
		");

fclose($ftex);
fclose($fpython);

//system("compile.bat > work/compile.out ",$status);
system("compile.bat  ",$status);
print "resultat-> $status";





?>

<script language="JavaScript" type="text/javascript">





function loadPage() {

 document.location.href = "../welcome/index.php";
}

//loadPage();



</script>



 
<br>
<?php include("../inc/footer.php"); ?>
