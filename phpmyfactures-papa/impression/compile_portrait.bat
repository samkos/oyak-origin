
PATH=c:\program Files\MiKTeX 2.6\miktex\bin;C:\WINDOWS\system32;c:\windows;c:\program Files\ghostgum\gsview
latex "\nonstopmode\input" all_portrait.tex
dvips all_portrait
copy all.ps c:\Oyak\facture.ps
copy all.ps c:\Oyak\ToPrint\facture.ps
rem gsprint all.ps
