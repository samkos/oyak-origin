<?php

include("../inc/conf.php");


if ($header) {
  include("../inc/header.php"); 
  print "<center>";
}

if ($header) {

  print "<BR> <a href='index.php?commande=C1;3700006000291!6!29!25/10/1973!1!2.5;3700006000291!6!29!01/08/2006!10!2.7;&vendeur=1&header=1'>Test passage commande</a>";
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
	
  mysql_close($connect_db);

  print "0!$nom_commande";


}

	 
?>