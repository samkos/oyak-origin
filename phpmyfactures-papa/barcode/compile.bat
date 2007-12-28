PATH=c:\program Files\MiKTeX 2.5\miktex\bin;C:\WINDOWS\system32;c:\windows;c:\python24
cd "work"
copy ..\create_barcodes.py .
copy ..\bookland.py .
python create_barcodes.py
latex "\nonstopmode\input{barcodes.tex}"
dvips barcodes
rem copy barcodes.ps c:\Oyak\barcodes.ps
rem copy barcodes.ps c:\Oyak\ToPrint\PR1\barcodes.ps
cd ..


