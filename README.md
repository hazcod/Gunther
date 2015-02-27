# Gunther
~ Easy responsive web frontend for Couch, Sick & media streaming.

Gunther can be used as a web frontend for your personal media. You can stream your media, aswell as automatically send new movies to program controlled by API. (think CouchPotato, Sickbeard, ...)

The idea is to have a central place for the less tech-savvy user.
If you want to contribute, application/ is the folder you need.


# Installation
1. Setup a database and web server. I supplied a sample apache2 site config in [apache2-site](/apache2-site-example)
2. Change `<password>` and import the [database setup file](/setup_database.sql) in your database.
4. Change your database settings and API keys in [config.php](/application/config.php).
5. Setup a daily cron job to keep the site snappy. (not mandatory)
   `01 0 * * * www-data wget --no-check-certificate -q http://localhost/cache &>/dev/null`
6. Login with admin - admin

# TODO
- Fix Watch.php (or not)
- Add detailed media information page
- Expand administrator interface

# Screenshots
![Gunther login screenshot](https://i.imgur.com/RWgQcBR.png "Login screen")

![Gunther dashboard](https://i.imgur.com/UcSAg08.png "Dashboard")

![Movie info](https://i.imgur.com/0QovMZD.png "Movie info page")

![Gunther stream screenshot](https://i.imgur.com/ddidCuk.jpg "Streaming screen")

![Gunther admin screenshot](https://i.imgur.com/87bhWjv.jpg "Admin interface")


# Project dependencies
(HTML/PHP/CSS/SQL), VideoJS, Bootswatch Flatly
