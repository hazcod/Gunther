# Gunther
~ Easy web frontend for your media on a debian VPS.

Gunther can be used as a web frontend for your personal media. You can stream your media, aswell as automatically send new movies to program controlled by API. (think CouchPotato, Sickbeard, ...)

The idea is to have a central place for the less tech-savvy user.
If you want to contribute, application/ is the folder you need.


# Installation
0. Start with a fresh debian installation and be logged in as root.
1. Download the setup file to your VPS.
```
cd /tmp && wget --no-check-certificate https://raw.githubusercontent.com/HazCod/Gunther/master/setup.sh
```
2. Replace `<YOURPASS>` with your admin password and run the [setup script](setup.sh) to setup everything on your Debian host.
```
nano setup.sh
chmod +x setup.sh
./setup.sh
```
3. Change your API keys in [config.php](/application/config.php).
4. Setup a daily cron job to keep the site snappy. (not mandatory)
   `01 0 * * * www-data wget --no-check-certificate -q http://localhost/cache &>/dev/null`
5. Login with admin and `<YOURPASS>`

# TODO
- Expand administrator interface

# Screenshots
![Gunther login screenshot](https://i.imgur.com/RWgQcBR.png "Login screen")

![Gunther dashboard](https://i.imgur.com/UcSAg08.png "Dashboard")

![Movie info](https://i.imgur.com/0QovMZD.png "Movie info page")

![Series info](http://i.imgur.com/JxIlfeC.png "Series info page")

![Gunther stream screenshot](https://i.imgur.com/ddidCuk.jpg "Streaming screen")

![Gunther admin screenshot](https://i.imgur.com/87bhWjv.jpg "Admin interface")


# Project dependencies
(HTML/PHP/CSS/SHELL), VideoJS, Bootswatch Flatly, Bootstrap, IMDB, TheTVDB
