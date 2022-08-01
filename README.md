[![CodeFactor](https://www.codefactor.io/repository/github/eglodevelopment/trakr/badge)](https://www.codefactor.io/repository/github/eglodevelopment/trakr)

# Tracker
The Eglo website tracker, tracks website visits and the IP and the country the user has visted from

# Note
Please do note that this can be easily bypassed in numerous ways and that the IP that may show up in the database is not the same IP that the user may be accessing from.

Also note that the timestamp is provided in Unix time, so you have to convert it online to standard time.

# Installation
Installation is very simple, you ust have all of the packages installed that are listed in the top of ```app.js``` then after all of those are installed
you have to edit the ```app.js``` file MySQL settings from lines ```12-15``` providing your database credentials, then you must go into PHPMyAdmin and import the ```trakr.sql``` file, make sure that the database is called ```trakr```. Currently there is no panel to access the stats so 
you have to go into PHPMyAdmin to see that stats.