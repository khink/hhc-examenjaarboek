HHC-Examenboek
==============

Disclaimer
----------

Although i don't doubt the usability of this software for a specific purpose,
the code is beneath all standards. The areas where it is most inferior include,
but are not limited to: Readability, Security, and Separation of layout from
logic. 

When i started this project years ago, i was working another (non-programming)
job, so I'm glad I can truthfully say: I have never in my professional life
created anything as bad as this. 


Prerequisites
-------------

You should have an account on a web server with PHP and MySQL installed.
Tested on:

* PHP: 5 
* Apache: 2 (mod_php)
* MySQL: 5.1.37-1ubuntu5


Installing
----------

- Decide which database to use. If you are at liberty to create a new one, do
  so:
  > CREATE DATABASE mydbname;

- Use this database to create new tables in:
  > USE mydbname;

- Add the tables, using the sql statements from tabellen.sql.
 
- Grant access to this database to your MySQL user:
  > GRANT ALL ON mydbname.* TO 'mysqluser'@'localhost' IDENTIFIED BY
  > mypassword; 
  (If you have only one database you may want to GRANT on mydbname.tablename
  instead.) 

- Extract these folders in some location on your server, for example::

  $ cd ~/public_html
  $ mkdir jaarboek
  $ cd jaarboek
  $ git init
  $ git pull git://github.com/khink/hhc-examenjaarboek.git

- Copy includes/example.mysqlsecrets.php to includes/mysqlsecrets.php, and
  modify the settings in it to match the ones above.

- Set a master password for the person who can view everything in
  includes/appadmin_auth.php


License
-------

The GPLv3 applies, see LICENSE.txt
