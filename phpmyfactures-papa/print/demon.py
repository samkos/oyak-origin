import os
import string
import re
import time


dir_printTODO="/Oyak/Toprint"
dir_factureTODO="/Oyak/Work"
exe_print="\"c:/Program Files/Ghostgum/gsview/gsprint.exe\" "
exe_facture="\"c:/Program Files/EasyPHP1-8/www/phpmyfactures/factures/traite.bat\" ";

debug=01
msg=1

def probeFacture():

    files=os.listdir(dir_factureTODO)

    if files:
        if msg:
            print "tratement des factures en attente"
        commande=exe_facture
        os.system(commande)
    else:
        if msg:
            print "Pas de facture en attente"
        

def probePrint():

    files=os.listdir(dir_printTODO)

    nb=0
    for file in files:

        # mpression fichier
        filename=dir_printTODO+"/"+file
        commande = exe_print+" "+filename
        if debug:
            print commande
        os.system(commande)
        
        if msg:
            print "Impression de %s "%filename

        os.remove(filename)

    return

if debug:
    print "Demarrage Print Daemon"

if not(os.path.exists(dir_printTODO)):
    os.mkdir(dir_printTODO)
    
while 1:
#if 1:
    if msg:
        print "checking files pending..."
    probeFacture()
    probePrint()
    time.sleep(10)
