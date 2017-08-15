#!/bin/bash

# Please run this file in terminal. using `./RUNME.sh`

echo -e "\e[94m _______  _______    _____"
echo -e "\e[94m(_______)(_______)  (____ \\"
echo -e "\e[94m ______   ______     _   \ \ ____ ____  ____ ____ ____  ___"
echo -e "\e[94m(_____ \ (_____ \   | |   | / _  ) _  |/ ___) _  ) _  )/___)"
echo -e "\e[94m _____) ) _____) )  | |__/ ( (/ ( ( | | |  ( (/ ( (/ /|___ |"
echo -e "\e[94m(______(_|______/   |_____/ \____)_|| |_|   \____)____|___/"
echo -e "\e[94m                                (_____|"
echo -e "\e[90mCopyright (C) 5.5 Degrees"
echo -e "\e[90mhttp://5andhalf.com/"
echo -e "\e[90m--------------------------"

# check if first run
if [ -e create.sh ]
then

# (! main repo)
if [ $C9_PROJECT != "laravelthe" ]
then

# start mysql 
mysql-ctl start

# update .env
cat >.env <<EOL
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:Eie0VnnvYIgPn7iNRk8hEoFTAYX4sCIPCQgkxYIIUW4=
APP_DEBUG=true
APP_LOG_LEVEL=debug
APP_URL=http://$C9_HOSTNAME/


DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=c9
DB_USERNAME=$C9_USER
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
SESSION_DRIVER=file
EOL

# update readme.md
cat >README.md <<EOL
# Laravel Take Home Exercise
Copyrights (c) [5.5 Degrees](http://www.5andhalf.com)

## HOW TO START?
1. Click "Run Project"
2. Browse to "http://$C9_HOSTNAME/"
2. Read Test Document
3. ...
100. START CODING.

## INFO
You can test / develop enpoints using POSTMAN  
You can access your database through [PHPMYADMIN](http://$C9_HOSTNAME/phpmyadmin)  
    - Username: $C9_USER  
    - Password: (BLANK / NO PASSWORD)  

### Framework Documentation
Documentation for the framework can be found on the
[Laravel website](http://laravel.com/docs).
EOL

echo -e "\e[91mPlease visit http://$C9_HOSTNAME/"

# remove create.sh
rm create.sh

fi
fi