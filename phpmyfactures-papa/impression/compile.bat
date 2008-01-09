
PATH=c:\program Files\MiKTeX 2.5\miktex\bin;C:\WINDOWS\system32;c:\windows;c:\program Files\ghostgum\gsview
latex "\nonstopmode\input" all.tex
dvips all
rem copy all.ps c:\Oyak\facture.ps
rem copy all.ps c:\Oyak\ToPrint\facture.ps
rem gsprint all.ps
