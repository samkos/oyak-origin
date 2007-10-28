import os
import string
import re
import time,datetime

timeTouchFile="/Oyak/ToPrint/PrintDemon.txt"

dir_printTODO="/Oyak/ToPrint"
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

        if not(file=="PrintDemon.txt"):
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


def touchDate():
    now=datetime.datetime.now()
    timestamp="%s%s"%(now.strftime("%Y%m%d"),now.strftime("%H%M%S"))

    f=open(timeTouchFile,"w")
    f.write(timestamp)
    f.write("\nNe pas effacer!!!!!!!! fichier de controle de l'impression")
    f.close()


def checkRunning():
    now=datetime.datetime.now()
    timestamp="%s%s"%(now.strftime("%Y%m%d"),now.strftime("%H%M%S"))
    
    f=open(timeTouchFile,"r")
    l=f.readlines()
    timestamp_old=eval(l[0])
    timestamp=eval(timestamp)
    #print timestamp_old,timestamp,timestamp_old-timestamp
    diff = timestamp-timestamp_old
    #print diff
    if diff<70:
        return 1
    else:
        return 0
    die

if not(os.path.exists(dir_printTODO)):
    os.mkdir(dir_printTODO)

if checkRunning():
    print "Demon OK"
else:
    touchDate()
    
    if debug:
        print "Demarrage Print Daemon"

    while 1:
        touchDate()
        if msg:
            print "checking files pending..."
        probeFacture()
        probePrint()
        time.sleep(10)
