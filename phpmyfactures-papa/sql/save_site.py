import os, sys, string
from stat import *
import time


from_dir="C:\Program Files\EasyPHP1-8\www\phpmyfactures"
work_dir="C:\Program Files\EasyPHP1-8\www\SAVE"
dirname=string.split(from_dir,'\\')[-1]
to_dir=work_dir+"\\"+dirname


def walktree(dir,par,type):
    global from_dir,to_dir
        
    mode = os.stat(dir)[ST_MODE]
    if S_ISDIR(mode):
      for f in os.listdir(dir):
        if not(f==".svn") :
            pathname = '%s\%s' % (dir, f)
            to_pathname=to_dir+"\\"+pathname.replace(from_dir,"")
            mode = os.stat(pathname)[ST_MODE]
            if S_ISDIR(mode):
                # It's a directory, create a copy directory
                try:
                    os.mkdir(to_pathname)
                except:
                    print "erreur Creation Dir "+to_pathname
                print "D+ "+to_pathname
                # It's a directory, recurse into it
                walktree(pathname, 0, TYPE_DIR)

            elif S_ISREG(mode):
                # It's a file copy it!
                try:
                    os.copy(path_name,to_pathname)
                except:
                    print "erreur copie fichier  "+to_pathname                    
                print "F+ "+to_pathname
    

if __name__ == '__main__':

    # read number of cd in base

    TYPE_DISK, TYPE_FILE, TYPE_DIR = 'Disk', 'File', 'Dir'

    # effacement de la sauvegarde eventuelle
    os.removedirs(work_dir)

    # creation du diretory cible
    os.mkdir(work_dir)
    os.mkdir(to_dir)


    

    # recopie du site web
    walktree(from_dir, 0, TYPE_DISK)

    # sauvegarde de la base

    # construction du fichier 7z

    


