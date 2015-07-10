#!/bin/bash

# Run this after setup.sh

adduser media
usermod -a -G www-data media
mkdir /home/media

# CouchPotato
git clone https://github.com/ruudburger/couchpotatoserver /home/media/couchpotato
cp /home/media/couchpotato/init/ubuntu /etc/init.d/couchpotato
chmod +x /etc/init.d/couchpotato
cp /home/media/couchpotato/init/ubuntu.default /etc/default/couchpotato
sed -i 's/CP_HOME=/CP_HOME=\/home\/media\/couchpotato\//g' /etc/default/couchpotato
sed -i 's/CP_USER=/CP_USER=media/g' /etc/default/couchpotato
echo "HOST=127.0.0.1" >> /etc/default/couchpotato
echo "PORT=5050" >> /etc/default/couchpotato
echo "SSD_OPTS=--group=media"
update-rc.d couchpotato defaults


# Sickbeard
apt-get install python-cheetah
git clone https://github.com/SiCKRAGETV/SickRage /home/media/sickrage
cp /home/media/sickrage/runscripts/init.debian /etc/init.d/sickrage
chmod +x /etc/init.d/sickrage
echo "SR_USER=media" > /etc/default/sickrage
echo "SR_HOME=/home/media/sickrage" > /etc/default/sickrage
echo "SR_OPTS=-p 8081" > /etc/default/sickrage
echo "SR_GROUP=media" > /etc/default/sickrage
#TODO: HOST
update-rc.d sickrage defaults

# Sabnzbd
r=$(grep "ppa.launchpad.net/jcfp/ppa/ubuntu precise main" /etc/apt/sources.list)
if [ -z "$r" ]; then
  echo "deb http://ppa.launchpad.net/jcfp/ppa/ubuntu precise main" | tee -a /etc/apt/sources.list
  apt-key adv --keyserver hkp://pool.sks-keyservers.net:11371 --recv-keys 0x98703123E0F52B2BE16D586EF13930B14BB9F05F
fi
apt-get update
apt-get upgrade -y
apt-get install -y --force-yes sabnzbdplus
mkdir /home/media/sabnzbdplus
sed -i 's/USER=/USER=media/g' /etc/default/sabnzbdplus
sed -i 's/CONFIG=/CONFIG=\/home\/media\/sabnzbdplus\/settings/g' /etc/default/sabnzbdplus
sed -i 's/HOST=/HOST=127.0.0.1/g' /etc/default/sabnzbdplus
sed -i 's/PORT=/PORT=8080/g' /etc/default/sabnzbdplus
sed -i 's/EXTRAOPTS=/EXTRAOPTS=--group=media/g' /etc/default/sabnzbdplus
update-rc.d sabnzbdplus defaults

# Headphones
git clone https://github.com/rembo10/headphones /home/media/headphones
cp /home/media/headphones/init-scripts/init.ubuntu /etc/init.d/headphones
chmod +x /etc/init.d/headphones
echo "HP_USER=media" > /etc/default/headphones
echo "HP_HOME=/home/media/headphones" > /etc/default/headphones
echo "HP_PORT=/home/media/headphones" > /etc/default/headphones
echo "SSD_OPTS=--group=media" > /etc/default/headphones
#TODO : HOST
update-rc.d headphones defaults

chown media:www-data -R /home/media
service couchpotato start
service sickrage start
service sabnzbdplus start
service headphones start
