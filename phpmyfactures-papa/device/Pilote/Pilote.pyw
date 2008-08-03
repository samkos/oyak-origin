# File: hello2.py

from Tkinter import *
import os

#curDir="C:\\Program Files\\EasyPHP1-8\\www\\phpmyfactures\\device\\Pilote\\"
rapiDir="bin\\"
regSymbol="hklm\\SOFTWARE\\Symbol Technologies, Inc.\\Profiles"
regWLAN1="hklm\COMM\NETWLAN1"
regHKLM="hklm"
outFileName="out.txt"

class IHM   :


    def __init__(self):

        self.debug=1

        # obtention d'une frame principale
        self.root = Tk()
        #self.root.wm_state(newstate="zoomed")

        # fenetre de Bouton
        self.boutonFrame=Frame(self.root)
        self.boutonFrame.pack(expand=0,fill=X)

        # fenetre de resultat
        self.resultFrame=Frame(self.root)
        self.resultFrame.pack(expand=1,fill=BOTH)

        self.scrollbar = Scrollbar(self.resultFrame)
        self.scrollbar.pack(side=RIGHT,expand=0,fill=Y)

        self.listbox = Listbox(self.resultFrame, yscrollcommand=self.scrollbar.set)
        self.listbox.pack(side=LEFT, expand=1, fill=BOTH)
        self.scrollbar.config(command=self.listbox.yview)

        # definition d'un bouton Wifi
        Bouton = Button(self.boutonFrame, text="Regler la Connection Wifi", command=self.launchMobileCompanion)
        # placement du Bouton dans la frame principale
        Bouton.pack(expand=1,fill=X)

        b1=self.addButtonReadReg(regSymbol)
        b2=self.addButtonReadReg(regWLAN1)
        b2=self.addButtonReadReg(regHKLM,pipe=0)

        # definition d'un bouton warm reboot
        Bouton = Button(self.boutonFrame, text="warm reboot", command=self.warmReboot)
        # placement du Bouton dans la frame principale
        Bouton.pack(expand=1,fill=X)

        # definition d'un bouton Copie Vendeur
        Bouton = Button(self.boutonFrame, text="Copie Vendeur", command=lambda x="fake":self.copieVendeur("vendeur"))
        # placement du Bouton dans la frame principale
        Bouton.pack(expand=1,fill=X)

        # definition d'un bouton Copie Vendeur
        Bouton = Button(self.boutonFrame, text="Copie Vendeur Singleton", command=lambda x="fake":self.copieVendeur("vendeur_singleton"))
        # placement du Bouton dans la frame principale
        Bouton.pack(expand=1,fill=X)

        # definition d'un bouton Installe appli
        Bouton = Button(self.boutonFrame, text="Installe Application", command=self.installAppli)
        # placement du Bouton dans la frame principale
        Bouton.pack(expand=1,fill=X)

        
        # definition d'un bouton Copie Vendeur
        Bouton = Button(self.boutonFrame, text="run Vendeur", command=self.runVendeur)
        # placement du Bouton dans la frame principale
        Bouton.pack(expand=1,fill=X)

        # definition d'un bouton Copie Vendeur
        Bouton = Button(self.boutonFrame, text="kill Vendeur", command=self.killVendeur)
        # placement du Bouton dans la frame principale
        Bouton.pack(expand=1,fill=X)


        # definition d'un bouton Installe appli
        Bouton = Button(self.boutonFrame, text="Installe Application", command=self.installAppli)
        # placement du Bouton dans la frame principale
        Bouton.pack(expand=1,fill=X)

        # definition d'un bouton del cache
        Bouton = Button(self.boutonFrame, text="Effacer Cache", command=lambda x="fake":self.delCache())
        # placement du Bouton dans la frame principale
        Bouton.pack(expand=1,fill=X)

        # definition d'un bouton del cache
        Bouton = Button(self.boutonFrame, text="Initialiser Cache", command=lambda x="fake":self.initCache())
        # placement du Bouton dans la frame principale
        Bouton.pack(expand=1,fill=X)


    def addButtonReadReg(self,reg,pipe=1):
        # definition d'un bouton lire WLAN valeur
        Bouton = Button(self.boutonFrame, text="Lire registre "+reg,
                        command=lambda : self.getWlanReg(reg,pipe))
        # placement du Bouton dans la frame principale
        Bouton.pack(expand=1,fill=X)
        return Bouton


    # execute une commande sur la device
    def run(self,what,pipe=0):
            commande="%s\\%s"%(rapiDir,what)
            if self.debug:
                print "EXEC : "+commande
            output=os.popen(commande)
            res=output.readlines()
            if pipe:
               return output.readlines()
            else:
               for r in res:
                   print r

    # fonction appelee par l'appui sur le bouton MobileCompanion
    def launchMobileCompanion(self):
        self.run("prun nictt")

    # fonction appelee par l'appui sur le bouton warmReboot
    def warmReboot(self):
        self.run("preboot")

    # fonction appelee par l'appui sur le bouton Lire registre WLAN
    def copieVendeur(self,name):
        pput="pput.exe -f ..\\appli\\%s.pyw \\Oyak\\vendeur.pyw"%name
        self.run(pput,pipe=0)
        pput="pput.exe -f ..\\appli\\%s.pyw \\Application\\Oyak\\vendeur.pyw"%name
        self.run(pput,pipe=0)
        print "vendeur copié"
        
    def runVendeur(self):
        prun="prun.exe \\Python25\\python /nopcceshell \\Oyak\\vendeur.pyw"
        output=self.run(prun,pipe=0)

    def killVendeur(self):
        prun="pkill.exe python"
        output=self.run(prun,pipe=0)

    def installAppli(self):
        self.installReg()
        self.copieVendeur("vendeur_singleton")
        
    def installReg(self):
        pput="pput.exe -f \"..\\a copier\\Application\\AppCenter.reg\"  \\Application\\AppCenter.reg"
        self.run(pput,pipe=0)
        print "fichier registre Appcenter copié"
        
    def initCache(self):
        self.run("pput.exe  -f c:\\Oyak\\*.bak \\Oyak\\")



    def delCache(self):
        self.run("pdel.exe  \\Oyak\\*.bak")

    # fonction appelee par l'appui sur le bouton Lire registre WLAN
    def getWlanReg(self,reg,outFile=0,pipe=0):
        print reg,outFile,pipe
        lisReg="pregdmp \""+reg+"\""
        self.run(lisReg,pipe)
        
    def affiche(self,output):
            self.listbox.delete(0,END)
            out=file("out.txt","r")
            for line in output:
                toprint=line.replace("\t","   ")
                self.listbox.insert(END,toprint[:-1])
                out.write(toprint[6:])
                print toprint[6:]
            out.close()

    def start(self):
       # demarrage de la boucle d'evenements
       self.active=1
       self.root.mainloop()

    
app=IHM()

app.start()




