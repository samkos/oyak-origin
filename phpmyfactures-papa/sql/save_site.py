import os, sys, string, shutil
from stat import *
import time


from_dir="C:\Program Files\EasyPHP1-8\www\phpmyfactures"
work_dir="C:\Program Files\EasyPHP1-8\www\SAVE"
dirname=string.split(from_dir,'\\')[-1]
to_dir=work_dir+"\\"+dirname

debug=10

def deltree(dir):
    global debug
    
    mode = os.stat(dir)[ST_MODE]
    if S_ISDIR(mode):
      for f in os.listdir(dir):
          pathname = '%s\%s' % (dir, f)
          mode = os.stat(pathname)[ST_MODE]
          if S_ISDIR(mode):
              # It's a directory, recurse into it
              deltree(pathname)
          elif S_ISREG(mode):
              # It's a file removeit
              try:
                  os.remove(pathname)
                  if debug>0:
                      print "F- "+pathname  
              except:
                  print "erreur Affacement fichier  "+pathname
                  raise
      # It's a directory, remove it
      try:
          print "D- "+dir
          os.rmdir(dir)
      except:
          print "erreur Effacement Dir "+dir
          raise
      
    



def copytree(dir,from_dir,to_dir):
    global debug
    
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
                    raise
                if debug>0:
                      print "D+ "+to_pathname
                # It's a directory, recurse into it
                if not (f=="work"):
                    copytree(pathname, from_dir, to_dir)

            elif S_ISREG(mode):
                # It's a file copy it!
                try:
                    shutil.copy(pathname,to_pathname)
                except:
                    print "erreur copie fichier  "+pathname+"  "+to_pathname
                    raise
                if debug>0:
                    print "F+ "+to_pathname
    

if __name__ == '__main__':

    # effacement de la sauvegarde eventuelle
    deltree(work_dir)
    try:
        deltree(work_dir)
        print "Effacement "+work_dir
    except:
        print "Erreur effacement " + work_dir
        pass

    # creation du diretory cible
    os.mkdir(work_dir)
    try:
        os.mkdir(work_dir)
    except:
        print "Erreur creation " + work_dir
        pass
    try:
        os.mkdir(to_dir)
    except:
        print "Erreur creation " + to_dir
        pass

    

    # recopie du site web
    copytree(from_dir,from_dir, to_dir)

    # sauvegarde de la base

    # construction du fichier 7z

    


