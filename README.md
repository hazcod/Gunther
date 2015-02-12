# Gunther
~ Easy responsive web frontend for Couch, Sick & media streaming.

Gunther can be used as a web frontend for your personal media. You can stream your media, aswell as automatically send new movies to program controlled by API. (think CouchPotato, Sickbeard, ...)

The idea is to have a central place for the less tech-savvy user.


# Installation
1. Setup a web server (I supplied the apache2 site config in (/apache2-site)[/apache2-site]) and database server.
2. Create the following tables on the SQL server; (sql script to be done)
    Table users with: id, login, password, datejoined
    Table langs with: id, name, flag
3. Add a user in the SQL table users.
4. Change your API key in the Controllers.

# TODO
- Provide SQL setup script
- Write controllers cleanly
- Add movies overview
- Add Series overview
- Add serie episode overview
- Finish watch page
- Add administrator panel (user management, statistics, ...)

# Screenshots
![Gunther login screenshot](https://i.imgur.com/RWgQcBR.png "Login screen")
