
The problem - sockets not releasing ports on D3NT
-------------------------------------------------
For a socket to release a port (in D3NT), the socket has to close successfully before the program ends. If a program ends normally or abnormally without having closed the socket, the port binded to that socket will be useless (until the D3 vme is restarted).

It appears that on D3NT, PickBasic doesn't close sockets automatically when the program ends, as it does with opened files.

Modifications to JD3
--------------------
1. The D3client program was modified to
   (a) Skip over 'unreleased' ports in the socket binding logic.
   (b) Accept a 'shutdown' command initialized as 0 in the Equates include. This command instructs the program to terminate after closing all the sockets.
   (c) A variable 'maxloop' is used to indicate the maximum number of 'unreleased' ports you anticipate by-passing when restarting JD3. Example if on average 2 ports hang after a certain period, setting 'maxloop' to 200 will allows you to restart JD3 100 times before you need to restart the D3 vme.
2. The D3Server program was modified to terminate after closing all the sockets if the read of the 'Server' record in the FJ.PORTS fails.
3. The J-Stop program was modified to
   (a) Send a shutdown command to each child.
   (b) Delete the 'Server' record in the FJ.PORTS before 'connecting' to the server port.
   (c) Add a 'Logoff' to terminated processes witch are in continuous loops or have terminated abnormally.
   (d) The port being logged off is displayed - this is helpful when the logging off process takes a while.

Important
---------
These modifications will not 'fix' the problem that D3NT has with 'unreleased' ports - it will 'by-pass' the problem and make it transparent to the operator.
If for some bizarre reason the server port hangs, the D3 vme will need to be restarted to clear this problem.

Other
-----
Always execute the 'J-Stop -a' command before the shutting down D3. Inserting the 'J-Stop -a' command in User-Shutdown is convenient.  It appears that stopping/restarting the D3 vme service on windows does not execute the User-Shutdown macro - rather execute the Shutdown command in the DM account.


mdubois@hbic.co.za