<?php include("../inc/conf.php"); ?>
<?php include("../inc/fonctions.php"); ?>
<?php
$nb_produit = 3000;
if ($action == "delete")
{
$req = mysql_query("delete from ".$prefixe_table."produits where id=\"$id_produit\"");
}

?>
<?php include("../inc/header.php"); ?>

<table border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#000000" width="770">
   <tr>
      <!-- <td bgcolor="#99CCCC" align="right" width="10%"><b>n�</b></td> -->
      <td bgcolor="#99CCCC" align="center" width="8%"><b>N�</b></td>
      <td bgcolor="#99CCCC" align="center" width="8%"><b>Nom</b></td>
      <td bgcolor="#99CCCC" align="center" width="50%"><b>Description</b></td>
      <td bgcolor="#99CCCC" align="center" width="50%"><b>Fichiers concern�s</b></td>
      <td bgcolor="#99CCCC" align="center" width="50%"><b>Valeur</b></td>
	  <td bgcolor="#99CCCC" align="center" width="18%" colspan="3"><b>Actions</b></td>
   </tr>
<?php


   // recuperation nom fournisseur
	 
	 $req = mysql_query("select id,societe from ".$prefixe_table."fournisseurs");
	 while($ligne = mysql_fetch_array($req))
	 {
	   $id = $ligne["id"];
	   $societe = $ligne["societe"];
		 $fournisseur_nom[$id]="$societe";
		}



if(!$start) 
{$start=0;}

$files2update=array();
$params=array();

if (!$filtre_clef) {$filtre_clef='*';}
if (!$filtre_fournisseur) {$filtre_fournisseur='*';}
if (!$filtre_param) {$filtre_param='*';}
if (!$filtre_ref) {$filtre_ref='*';}
if (!$filtre_titre) {$filtre_titre='*';}

$sql_filtre=str_replace("*","%","where id_cat=\"$id_cat\" and clef like '$filtre_param' and barcode like '$filtre_ref' and fournisseur like '$filtre_fournisseur' and titre like '$filtre_titre' and not(clef < 10000)" );
//print $sql_filtre;

$query="select id,titre,stock,fournisseur,clef,description from ".$prefixe_table
							 ."produits $sql_filtre"
                       ."order by titre,barcode limit $start,$nb_produit";
//echo "query=$query <BR>";
$req = mysql_query($query);
while($ligne = mysql_fetch_array($req))
{
  $id = $ligne["id"];
 $barcode = $ligne["barcode"];
 $fournisseur = $ligne["fournisseur"];
 $clef = $ligne["clef"];
 $param = $clef;
 $prix_vente_ht = $ligne["prix_vente_ht"];
 $descriptions = split("--DESCRIPTION--",$ligne["description"]);
 $description=array_pop($descriptions);
 $fichiers=array_pop($descriptions);
 $prix_plancher_ht = $ligne["prix_plancher_ht"];
 $titre = $ligne["titre"];
 $stock = $ligne["stock"];


 $params[$fournisseur]=$titre;
 if ($fichiers) {
   array_push($files2update,$fichiers);
 }

$id_d = sprintf("%08s",$id);


echo("<tr>
   <!--  <td bgcolor=\"#ffffff\" align=\"center\" width=\"10%\">$id</td> -->
   <td bgcolor=\"#ffffff\" align=\"center\" width=\"8%\">$param</td>
   <td bgcolor=\"#ffffff\" align=\"center\" width=\"8%\">$fournisseur <BR> </td>
  <td bgcolor=\"#ffffff\" align=\"center\" width=\"8%\">$description</td>
   <td bgcolor=\"#ffffff\" align=\"center\" width=\"8%\">$fichiers</td>
   <td bgcolor=\"#ffffff\" align=\"left\" width=\"50%\">$titre</td>
   <td bgcolor=\"#ffffff\" align=\"center\" width=\"9%\"><a href=\"modifier_param.php?clef_param=$clef\">Modifier</a></td>
</td>
</tr>");


}

?>

</table>

<br>

<center>[ <?php

$result=mysql_query("select count(*) from ".$prefixe_table."produits $sql_filtre");
$row=mysql_fetch_row($result);

if ($start == "0")
{
echo"<b>1</b> ";
}
else
{
echo"<a href=\"index.php?start=0\">1</a> ";
}

for($index=1;($index*$nb_produit)<$row[0];$index++) 
{
   $pg = $index+1;
   if(($index*$nb_produit)!=$start) 
   {
   print(" - <a href=\"index.php?start=".($index*$nb_produit)."\">");
   echo"$pg";
   print("</a>");
   }
   else
   {
   echo" - <b>$pg</b>";
   }
}



echo "] <BR> <BR> <a href='params/index.php?create=1'>Cr�er l'environnement</a>";

print_r($params);
print_r($files2update);

$files2update=glob("../query/*_MOD.*");
print_r($files2update);

while ($file=array_pop($files2update)) {
  $content = join(file($file),"<BR>");
  print "<BR> modification de $file <BR> <LEFT> $content";
  foreach($params as $key => $value) {
    print "<BR> $key,$value";
    $content=preg_replace("/__".$key."__/",$value,$content);
  }
  print $content."</LEFT>";
  $content_saved=join(split("<BR>",$content),"");
  $new_file=preg_replace("/MOD/","x",$file);
  $f=fopen($new_file."x","w");
  fwrite($f,$content_saved);
  fclose($f);
}

?> 
</center>


<?php include("../inc/footer.php"); ?>