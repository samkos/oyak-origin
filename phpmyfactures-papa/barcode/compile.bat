PATH=c:\program Files\MiKTeX 2.5\miktex\bin;C:\WINDOWS\system32;c:\windows;c:\python24
cd "work"
copy ..\create_barcodes.py .
copy ..\bookland.py .
python create_barcodes.py
latex "\nonstopmode\input{barcodes.tex}"
dvips barcodes
copy barcodes.ps c:\Oyak\barcodes.ps
ps2pdf barcodes.ps barcodes.pdf
rem del *.eps  *.aux 	
copy barcodes.pdf c:\Oyak\barcodes.pdf
rem gsprint barcodes.ps
cd ..


