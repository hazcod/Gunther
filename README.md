# Gunther
~ Easy web frontend for your media on a debian VPS.


Gunther can be used as a easy-to-use web frontend for your personal media. You can stream your media, aswell as automatically request new media to programs controlled by API. (think CouchPotato, Sickbeard, ...)

The idea is to have a central place for the less tech-savvy user.
If you want to contribute, application/ is the folder you need.

Note: This can install downloaders for you. [Use the setupDownloaders.sh script for that.](https://github.com/HazCod/Gunther/blob/master/setup/setupDownloaders.sh).

# Features
- Works with the web server apache (no nginx because of broken webdav)
- Uses the high performant Webdav protocol for sharing; almost every client supports this
- No running database required; uses SQlite
- Caches posters and actor images; a little slower, but at least everything is over HTTPS
- Fetches all media info directly from providers.


# Installation
0. Start with a fresh debian installation and be logged in as root.
1. Download the setup file to your VPS.
```
cd /tmp && wget --no-check-certificate https://raw.githubusercontent.com/HazCod/Gunther/master/setup/setup.sh
```
2. Set your desired admin password in the script (default is `Gunth3r!`) and run the [setup script](setup/setup.sh) to setup everything on your Debian host.
```
nano setup.sh
chmod +x setup.sh
./setup.sh
```
3. Set your API keys and other settings in [config.php](/application/config.php).
4. Login with admin and the password.

# TODO
[See issue tracker.](https://github.com/HazCod/Gunther/issues)

# Screenshots
![Gunther login screenshot](https://i.imgur.com/RWgQcBR.png "Login screen")

![Gunther dashboard](https://i.imgur.com/UcSAg08.png "Dashboard")

![Movie info](https://i.imgur.com/0QovMZD.png "Movie info page")

![Series info](http://i.imgur.com/JxIlfeC.png "Series info page")

![Gunther stream screenshot](https://i.imgur.com/ddidCuk.jpg "Streaming screen")

![Gunther admin screenshot](https://i.imgur.com/87bhWjv.jpg "Admin interface")


# Project dependencies
(HTML/PHP/CSS/SHELL/JS), VideoJS, Bootswatch Flatly, Bootstrap, IMDB, TheTVDB, SQLite
