=======================================================================

(C)2000 Christophe Marchal
Released under GPL-2 license (see LICENSE)
mccricri@yahoo.com
http://www.sourceforge.com/projects/jd3

=======================================================================

This is the pick basic socket server.

To run it just type at TCL :

j-startup

then the server is waiting for incomming connection on the port 20002.
You can specify your own port like this :

j-startup -port 20002

The server start childs threads as phantoms to serve incoming conection. 
you can specify the number of child by the option -c xxx 
The default is 2 childs, so the server can manage 2 connection at a time.

This start a server with 3 threads :
j-startup -c 3

Each child listen on a different tcp port, you can specify the first port used
by the first child with the option -cp xxxx, like this :

j-startup -cp 3500

this start a server with 2 child (the default) listenning on port 3500 and 3501.
The default is 30000.

To show status of the server , just type j-status at tcl.


For debugging :
you can "tandem" on the phantom line to see the message print by the subroutine
or start a tread with the -d option


=======================================================================

