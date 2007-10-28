PATH=c:\Program Files\EasyPHP1-8\php\;c:\Program Files\EasyPHP1-8\php\extensions\;c:\program Files\ghostgum\gsview;
cd   "c:\Program Files\EasyPHP1-8\www\phpmyfactures\factures"
php.exe traite.php >> out_php
copy c:\Oyak\facture.ps c:\Oyak\ToPrint\facture.ps
echo "impression en cours"
