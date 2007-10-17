import os
import shutil
import time
import datetime

oyak_dest="C:\\Program Files\\EasyPHP1-8\\www\\"
oyak_from="phpmyfactures"

today=datetime.datetime.now()
now=today.strftime("%Y%m%d%H%M")

for root, dirs, files in os.walk(oyak_from):
    for name in files:
        file_from=root+"\\"+name
        file_dest=oyak_dest+file_from
        file_renamed=file_dest+"-"+now
        
        try:
            #print "ren ",file_dest,file_renamed
            shutil.copy(file_dest,file_renamed)
        except:
            print "pb a la sauvevgarde de ",file_from

        try:
            #print "ren ",file_dest,file_renamed
            shutil.copy(file_from,file_dest)
        except:
            print "pb a la copie de ",file_from

        print "updating ",file_from," OK "

input "serveur updated"
                    

 
