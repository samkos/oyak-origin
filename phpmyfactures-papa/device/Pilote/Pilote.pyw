# File: hello2.py

from Tkinter import *
import os

rapiDir="bin\\"
regSymbol="hklm\\SOFTWARE\\Symbol Technologies, Inc.\\Profiles"
regWLAN1="hklm\COMM\NETWLAN1"
regHKLM="hklm"
outFileName="out.txt"

class IHM   :


    def __init__(self):

        self.debug=0

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
        Bouton = Button(self.boutonFrame, text="Copie Vendeur", command=self.copieVendeur)
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
            commande=rapiDir+what
            if self.debug:
                print commande
            if pipe==0:
               os.system(commande)
            else:
               output=os.popen(commande)
               return output.readlines()

    # fonction appelee par l'appui sur le bouton MobileCompanion
    def launchMobileCompanion(self):
        self.run("prun nictt")

    # fonction appelee par l'appui sur le bouton warmReboot
    def warmReboot(self):
        self.run("preboot")

    # fonction appelee par l'appui sur le bouton Lire registre WLAN
    def copieVendeur(self):
        pput='bin\pput.exe ..\\appli\\vendeur.pyw \\Oyak\\vendeur.pyw'
        print pput
        output=self.run(pput,pipe=1)
        self.affiche(output)
        


    # fonction appelee par l'appui sur le bouton Lire registre WLAN
    def getWlanReg(self,reg,outFile=0,pipe=0):
        print reg,dir(reg),outFile,pipe
        lisReg="pregdmp \""+reg+"\""
        print lisReg
        if outFile:
           self.run(lisReg+" > "+outFileName)
        elif pipe:
           output=self.run(lisReg,pipe=1)
           self.affiche(output)

    def affiche(self,output):
            self.listbox.delete(0,END)
            out=file("out.txt","w")
            for line in output:
                toprint=line.replace("\t","   ")
                self.listbox.insert(END,toprint[:-1])
                out.write(toprint[6:])
            out.close()

    def start(self):
       # demarrage de la boucle d'evenements
       self.active=1
       self.root.mainloop()

    
app=IHM()

app.start()




