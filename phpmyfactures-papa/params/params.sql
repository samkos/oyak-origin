use oyak; 
delete from pcfact_produits where clef not between 0 and 9999;  

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;


/*!40000 ALTER TABLE `pcfact_produits` DISABLE KEYS */;
/*
LOCK TABLES `pcfact_produits` WRITE;
INSERT INTO `pcfact_produits` VALUES (0,'0',1,'Impression Systématique de BL\r\n1=Oui\r\n0=Non','0.00','0.00','0','19.60','1.000','','ALL_BL',10006,0,NULL),(0,'PR421',1,'Imprimante Bon de Livraison','0.00','0.00','0','19.60','1.000','','PR_BL',10001,0,NULL),(0,'0.31',1,'Version de l\'application','0.00','0.00','0','19.60','1.000','','APP_VER',10004,0,NULL),(0,'PR432',1,'Imprimante Etiquette','0.00','0.00','0','19.60','1.000','','PR_ETIQ',10003,0,NULL),(0,'XXX',0,'Imprimante par defaut','0.00','0.00','0','0.00','0.000','','PR_DEF',10000,0,NULL),(0,'MiKTeX 2.5',1,'Version de LaTeX utilisée','0.00','0.00','0','19.60','1.000','','LATEX',10002,0,NULL);
UNLOCK TABLES;
*/
/*!40000 ALTER TABLE `pcfact_produits` ENABLE KEYS */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

