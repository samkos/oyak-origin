<?php include("../inc/conf.php"); ?>
<?php include("../inc/fonctions.php"); ?>
<?php
$nb_produit = 3000;
if ($action == "delete")
{
$req = mysql_query("delete from ".$prefixe_table."produits where id=\"$id_produit\"");
}

?>
<?php include("../inc/header.php"); 

if (!$filtre_clef) {$filtre_clef='*';}
if (!$filtre_fournisseur) {$filtre_fournisseur='*';}
if (!$filtre_param) {$filtre_param='*';}
if (!$filtre_ref) {$filtre_ref='*';}
if (!$filtre_titre) {$filtre_titre='*';}

$sql_filtre=str_replace("*","%","where clef > '9999'") ;
//print $sql_filtre;

$query="select count(titre) from ".$prefixe_table ."produits $sql_filtre";
//echo "query=$query <BR>";
$req = mysql_query($query);
$ligne = mysql_fetch_array($req);
$nb_params=$ligne[0];
//print "<BR> $nb_params;";
if ($nb_params==0) {
  system("param_init.bat > out.txt");
}


?>

<table border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#000000" width="770">
   <tr>
      <!-- <td bgcolor="#99CCCC" align="right" width="10%"><b>n�</b></td> -->
      <td bgcolor="#99CCCC" align="center" width="8%"><b>N�</b></td>
      <td bgcolor="#99CCCC" align="center" width="8%"><b>Nom</b></td>
      <td bgcolor="#99CCCC" align="center" width="50%"><b>Description</b></td>
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




$query="select titre,fournisseur,clef,description from ".$prefixe_table
							 ."produits $sql_filtre"
                       ."order by clef";
//echo "query=$query <BR>";
$req = mysql_query($query);

while($ligne = mysql_fetch_array($req))
{
  $fournisseur = $ligne["fournisseur"];
  $clef = $ligne["clef"];
  $param = $clef;
  $description = $ligne["description"];
  $titre = $ligne["titre"];
  $stock = $ligne["stock"];

  $params[$fournisseur]=$titre;

  $id_d = sprintf("%08s",$id);


  echo("<tr>
   <td bgcolor=\"#ffffff\" align=\"center\" width=\"8%\">$param</td>
   <td bgcolor=\"#ffffff\" align=\"center\" width=\"8%\">$fournisseur <BR> </td>
  <td bgcolor=\"#ffffff\" align=\"center\" width=\"8%\">$description</td>
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

print "]<BR>";


//print_r($params);

$files2update=glob("../*/*_MOD.*");
//print_r($files2update);

while ($file=array_pop($files2update)) {
 
  if (preg_match("/~$/",$file)==0) {
    $content = join(file($file),"<BR>");
    print "<BR> modification de $file <BR>";
    foreach($params as $key => $value) {
      //print "<BR> $key,$value";
      $content=preg_replace("/__".$key."__/",$value,$content);
    }
    $content_saved=join(split("<BR>",$content),"");
    $new_file=preg_replace("/MOD/","x",$file);
    $f=fopen($new_file."x","w");
    fwrite($f,$content_saved);
    fclose($f);
  }
}

?> 
</center>


<?php include("../inc/footer.php"); ?>