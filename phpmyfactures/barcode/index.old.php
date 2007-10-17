<?php include("../inc/conf.php"); ?>
<?php include("../inc/fonctions.php"); ?>
<?php

if ($action == "delete")
{
$req = mysql_query("delete from ".$prefixe_table."produits_cat where id=\"$id_cat\"");
$req = mysql_query("delete from ".$prefixe_table."produits where id_cat=\"$id_cat\"");
}

?>
<?php include("../inc/header.php"); ?>

<table border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#000000" width="770">
   <tr>
      <td bgcolor="#99CCCC" align="center" width="50%"><b>Titre</b></td>
	  <td bgcolor="#99CCCC" align="center" width="50%" colspan="3"><b>Actions</b></td>
   </tr>
<?php

$req = mysql_query("select id,titre from ".$prefixe_table."produits_cat order by titre");
while($ligne = mysql_fetch_array($req))
{
$id = $ligne["id"];
$titre = $ligne["titre"];

echo("<tr>
   <td bgcolor=\"#ffffff\" align=\"center\" width=\"50%\">$titre</td>
   <td bgcolor=\"#ffffff\" align=\"center\" width=\"25%\"><a href=\"imprimer_barcode.php?id_cat=$id\">Imprimer</a></td>
   <td bgcolor=\"#ffffff\" align=\"center\" width=\"25%\"><a href=\"modifier_barcode.php?id_cat=$id\">Modifier</a></td>
</tr>");
}

?>
</table>

<br>

<center>
<a href="ajouter_cat.php">Ajouter une catégorie</a> - 
<a href="/<?php echo("$prefixe_dossier"); ?>tva/">Gérer les taux de TVA</a> - 
<a href="/<?php echo("$prefixe_dossier"); ?>index.php">Retour</a>
</center>

<?php include("../inc/footer.php"); ?>