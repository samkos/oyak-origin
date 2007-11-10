import os
import string
import re
import time,datetime
import sys

timeTouchFile="/Oyak/ToPrint/PrintDemon.txt"
timeOK=30

dir_printTODO="/Oyak/ToPrint"
dir_workTODO="/Oyak/Work"
dir_factureTODO="/facprint"
exe_print="\"c:\Program Files\Ghostgum\gsview\gsprint.exe\" -printer \"test1\""
exe_facture="\"c:/Program Files/EasyPHP1-8/www/phpmyfactures/factures/traite.bat\" ";

debug=01
msg=1

now=datetime.datetime.now()
timestamp="%s%s"%(now.strftime("%Y%m%d"),now.strftime("%H%M%S"))

#sys.stdout = open("c:/Oyak/print.log","a")

def probeFacture():
    global timestamp
    
    files=os.listdir(dir_factureTODO)

    if files:
        if msg:
            print "%s"%timestamp+":"+"traitement des factures en attente"
        commande=exe_facture
        os.system(commande)
    else:
        if msg:
            print "%s"%timestamp+":"+"Pas de facture en attente"
        

def probePrint():
    global timestamp

    files=os.listdir(dir_printTODO)

    nb=0
    for file in files:

        if not(file=="PrintDemon.txt"):
            # mpression fichier
            filename=dir_printTODO+"/"+file
            commande = exe_print+" "+filename
            if debug:
                print "%s"%timestamp+":"+commande
            os.system(commande)
        
            if msg:
                print "%s"%timestamp+":"+ "Impression de %s "%filename

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
    global timestamp,timeTouchFile,timeOK

    if not(os.path.exists(timeTouchFile)):
        return 0

    try:
        f=open(timeTouchFile,"r")
        l=f.readlines()
        timestamp_old=eval(l[0])
        timestamp=eval(timestamp)
        #print timestamp_old,timestamp,timestamp_old-timestamp
        diff = timestamp-timestamp_old
        #print diff
        if diff<timeOK:
            return 1
        else:
            return 0
    except:
        return 0
    
if not(os.path.exists(dir_printTODO)):
    os.mkdir(dir_printTODO)

if not(os.path.exists(dir_factureTODO)):
    os.mkdir(dir_factureTODO)

if not(os.path.exists(dir_workTODO)):
    os.mkdir(dir_workTODO)

if checkRunning():
    print "%s"%timestamp+":"+"Demon OK"
else:
    touchDate()
    
    if debug:
        print "%s"%timestamp+":"+"Demarrage Print Daemon"

    while 1:
        touchDate()
        if msg:
            print "%s"%timestamp+":"+"checking files pending..."
        probeFacture()
        probePrint()
        time.sleep(10)
