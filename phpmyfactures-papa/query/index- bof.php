<?php

include("../inc/conf.php");


if ($header) {
  include("../inc/header.php"); 
  print "<center>";
}

if ($header) {

  print "<BR> <a href='index.php?commande=06;C3426;3700008000671!8!0067!!12!8.00!*;0!16!0012!!109!7.0!*;&vendeur=1&header=1'>Test passage commande</a>";
  print "<BR> <a href='../admin/'>Retour a l'administration </a> <br />";
}

if ($commande) {
  $time=time();
  $query="insert into ".$prefixe_table."compteur values ('','$vendeur','$time')";
  if ($header) { print $query;}
  $req = mysql_query($query);

  $query="select compteur from  ".$prefixe_table."compteur where temps=$time";
  $req = mysql_query($query);
  while($ligne = mysql_fetch_array($req))
    { $compteur = $ligne["compteur"];}
		
  $compteur=sprintf("%05d",$compteur);
  $tag_date=date("Ymd");
  $nom_commande=sprintf("$compteur",$vendeur);
  $out_file="$commande_dir\\$tag_date".$nom_commande;
  if ($header) {print "<BR> sortie vers $out_file";}

  $fout=fopen($out_file,"w");
  $out_commande=sprintf("%02d;$commande",$vendeur);
  fwrite($fout,$out_commande);
  fclose($fout);
		
  if ($header) { print "<BR> commande = $commande <BR> compteur=$compteur";}
	
  $query="delete  from ".$prefixe_table."compteur where (compteur<".($compteur-5)." and vendeur='$vendeur')";
  if ($header) {print "<BR>".$query;}
  $req = mysql_query($query);
	

  print "0!$nom_commande";


   // recuperation nom Vendeur
  
  $req = mysql_query("select id,nom,prenom from ".$prefixe_table."vendeurs where id='$vendeur'");
  while($ligne = mysql_fetch_array($req))
    {
      $id = $ligne["id"];
      $nom = $ligne["nom"];
      $prenom = $ligne["prenom"];
      $vendeur_name ="$prenom $nom";
    }
	
  $articles=split(";",$commande);
  $client=array_shift($articles);
  $parametre=array_pop($articles);

   // recuperation nom client

  $req = mysql_query("select id,clef,societe,ville from ".$prefixe_table."clients where clef='$client'");
  while($ligne = mysql_fetch_array($req))
    {
      $client_name = $ligne[societe]."/".$ligne[ville];
      $client_name = ereg_replace("\*.*$","",$client_name);
    }
	


  // impression BL

  $start_bltex='
      \documentclass[a4paper]{article}
      %
      \setlength{\voffset}{-4.5cm}
      \setlength{\hoffset}{-1.6cm}

      \setlength{\oddsidemargin}{0pt}
      \setlength{\evensidemargin}{0.5cm}

      \setlength{\textwidth}{550pt}

      \setlength{\topmargin}{1cm}
      \setlength{\textheight}{26cm}

      \setlength{\headheight}{90pt}

      \setlength{\headsep}{0pt}
      \setlength{\parindent}{1cm}
      \setlength{\parskip}{0.2cm}

      \setlength{\marginparwidth}{0pt}
      \setlength{\marginparsep}{0pt}
      %
      \begin{document}';

  $in_bltex='

	    \begin{center}

                \begin{Large} \begin{bf} 
                       Bon de Livraison '."$nom_commande"." \\\\
                Date : ".date("d/m/y")." \\\\ Vendeur : $vendeur_name   \\\\ Client : $client_name \\\\ ".'
                \end{bf} \end{Large} 
              \\\\ \vspace{1cm} \\\\
	      \begin{tabular}|p{5cm}|p{2cm}|p{1.5cm}|}
	        \hline
	        \textbf{\small{D�signation}} &
	        \textbf{\small{Quant.}} & 
	        \textbf{\small{Prix}} &
	        \hline
	          ';

  

  if ($header) {
    print "<BR> vendeur=$vendeur_name <BR> client=$client_name  <BR> param=$parametre";
    print '	<BR>	<table border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#000000" width="770">';
  }

  while ($article=array_shift($articles)) {
    $col=split("!",$article);
    $code=$col[0];
    $racourci=$col[1];  			
    $fournisseur=$col[2];  			
    $date=$col[3];  			
    $quantite=$col[4];
    $prix=$col[5];
  
    
    $query="select titre from ".$prefixe_table."produits where barcode=\"$code\"";
    $req = mysql_query($query);
    //print $req;
    //print "query = ".$query."<BR>";
    while($ligne = mysql_fetch_array($req))
      {	
	//print_r($ligne);
	$produit = $ligne["titre"];
      }
    
    $query="select societe from ".$prefixe_table."fournisseurs where clef=\"$fournisseur\"";
    $query=sprintf("select societe from ".$prefixe_table."fournisseurs where clef=\"%d\"",$fournisseur);
    $req = mysql_query($query);
    //print $req;
    //print "query = ".$query."<BR>";


    while($ligne = mysql_fetch_array($req))
      {	
	//print_r($ligne);
	$societe = $ligne["societe"];
      }
   

    $in_bltex=$in_bltex
      ."$produit & $quantite & $prix \\\\ \n";
      
	  
    if ($header) {
	print "
  		 <tr>
  		   <td bgcolor='#ffffff' align='right'> $quantite</td>
  		   <td bgcolor='#ffffff' align='left'> $produit ($racourci)</td>
  		   <td bgcolor='#ffffff' align='left'> $code</td>
				 <td bgcolor='#ffffff' align='left'> $societe ($fournisseur)</td>
  		   <td bgcolor='#ffffff' align='right'> $date</td>
  		   <td bgcolor='#ffffff' align='right'> $prix</td>
  		</tr>";
      }
  }
  
  $in_bltex=$in_bltex.'
    \hline
    \end{tabular} ';

  $end_bltex = '\end{center} \end{document}';

  $nom="bl-$nom_commande";
  $file_out=fopen("$nom.tex","w");
  fwrite($file_out,$start_bltex.$in_bltex."\\\\  \vspace{3cm} \\\\".$in_bltex.$end_bltex);
  fclose($file_out);

  if ($header) {
    print "</table> <br /><br />";
  }


  system("compile.bat $nom > out" ,$result);

  mysql_close($connect_db);

}

	 
?>