<?php include("../inc/conf.php"); ?>
<?php include("../inc/fonctions.php"); ?>
<?php

if ($action == "delete")
{
$req = mysql_query("delete from ".$prefixe_table."clients where id=\"$id_client\"");
}

?>
<?php include("../inc/header.php"); ?>

<table border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#000000" width="770">
   <tr>
      <td bgcolor="#99CCCC" align="center" width="14%"><b>Clef</b></td>
	  <td bgcolor="#99CCCC" align="center" width="23%"><b>Nom</b></td>
	  <td bgcolor="#99CCCC" align="center" width="23%"><b>Ville</b></td>
	  <td bgcolor="#99CCCC" align="center" width="40%" colspan="3"><b>Actions</b></td>
   </tr>
<?php

$nb_client = 30;
if(!$start) 
{$start=0;}

$req = mysql_query("select id,clef,societe,ville from ".$prefixe_table."clients order by societe,ville,clef limit $start,$nb_client");
while($ligne = mysql_fetch_array($req))
{
$id = $ligne["id"];
$clef = $ligne["clef"];
$nom = $ligne["societe"];
$ville = $ligne["ville"];

$id_d = sprintf("%08s",$id);

echo("<tr>
   <!--  <td bgcolor=\"#ffffff\" align=\"center\" width=\"15%\">#$id_d</td> -->
	 <td bgcolor=\"#ffffff\" align=\"left\" width=\"15%\">$clef</td> 
   <td bgcolor=\"#ffffff\" align=\"left\" width=\"23%\">$nom</td>
   <td bgcolor=\"#ffffff\" align=\"left\" width=\"23%\">$ville</td>
   <td bgcolor=\"#ffffff\" align=\"center\" width=\"20%\"><a href=\"#null\" onclick=\"javascript:if(confirm('Etes-vous sûr de vouloir supprimer ce client ?')) document.location.replace('index.php?action=delete&id_client=$id');\">Supprimer</a></td>
   <td bgcolor=\"#ffffff\" align=\"center\" width=\"20%\"><a href=\"modifier_client.php?id_client=$id\">Modifier</a></td>
</tr>");
}

?>
</table>

<br>

<center>[ <?php

$result=mysql_query("select count(*) from ".$prefixe_table."clients");
$row=mysql_fetch_row($result);

if ($start == "0")
{
echo"<b>1</b> ";
}
else
{
echo"<a href=\"index.php?start=0\">1</a> ";
}

for($index=1;($index*$nb_client)<$row[0];$index++) 
{
   $pg = $index+1;
   if(($index*$nb_client)!=$start) 
   {
   print(" - <a href=\"index.php?start=".($index*$nb_client)."\">");
   echo"$pg";
   print("</a>");
   }
   else
   {
   echo" - <b>$pg</b>";
   }
}

?> ]</center>

<br>

<center>
<a href="ajouter_client.php">Ajouter un client</a> - 
<a href="/<?php echo("$prefixe_dossier"); ?>welcome/index.php">Retour</a>
</center>

<?php include("../inc/footer.php"); ?>