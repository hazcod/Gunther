# Gunther
~ Easy responsive web frontend for Couch, Sick & media streaming.

Gunther can be used as a web frontend for your personal media. You can stream your media, aswell as automatically send new movies to program controlled by API. (think CouchPotato, Sickbeard, ...)

The idea is to have a central place for the less tech-savvy user.
If you want to contribute, application/ is the folder you need.


# Installation
1. Setup a database and web server. I supplied a sample apache2 site config in [apache2-site](/apache2-site-example)
2. Create the following tables on the SQL server; (sql script to be done)
    Table users with: id, login, password, datejoined
    Table langs with: id, name, flag
3. Add a user in the SQL table users, the password should be a SHA1 hash with the correct salt.[Home controller](/application/controllers/Home.php)
4. Change your API key in [config.php](/application/config.php).

# TODO
- Fix subtitles for TV Shows in Watch.php
- Provide SQL setup script
- Add administrator panel (user management, statistics, ...)

# Screenshots
![Gunther login screenshot](https://i.imgur.com/RWgQcBR.png "Login screen")

![Gunther dashboard](https://i.imgur.com/YDWSkz7.jpg "Dashboard")

![Gunther stream screenshot](https://i.imgur.com/ddidCuk.jpg "Streaming screen")



# Project dependencies
(HTML/PHP/CSS/SQL), VideoJS, Bootswatch Flatly
