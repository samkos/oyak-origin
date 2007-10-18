PATH=c:\program Files\MiKTeX 2.5\miktex\bin;C:\WINDOWS\system32;c:\windows;c:\program Files\ghostgum\gsview
latex "\nonstopmode\input" all.tex
dvips all
ps2pdf all.ps all.pdf
copy all.ps c:\Oyak\facture.ps																				
copy all.pdf c:\Oyak\facture.pdf
rem gsprint all.ps
