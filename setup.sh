#!/bin/bash

#~ extra: setup an encrypted volume that mounts on boot (supposing your disk is /dev/vdb)
#~ note : this still asks a password on boot
#apt-get install -y cryptsetup
#cryptsetup -y -v luksFormat /dev/vdb
#cryptsetup luksOpen /dev/vdb media
#mkfs.ext4 /dev/mapper/media
#mkdir -p /mnt/media
#echo "media_crypt	/dev/vdb	none	luks" >> /etc/crypttab
#echo "/dev/mapper/media_crypt	/mnt/media	ext4	rw	0	2" >> /etc/fstab

# This will be the password used for your admin account. Login with 'admin'
ADMIN_PASSWORD="my_password_with_$p3cial_characters"



##===== DO NOT MODIFY BELOW THIS LINE ====================================

#update system
apt-get update && apt-get upgrade -y

# install dependencies
apt-get install -y git apache2-utils openssl libssl-dev libpcre3-dev make gcc php5-common php5-cli php5-fpm php5-curl mediainfo libexpat1-dev haveged

# install nginx
mkdir -p /etc/nginx
cd /tmp
wget http://nginx.org/download/nginx-1.8.0.tar.gz
tar zxf nginx-1.8.0.tar.gz
# download modules
git clone https://github.com/atomx/nginx-http-auth-digest
git clone https://github.com/arut/nginx-dav-ext-module
# compile and install nginx
cd nginx-1.8.0/
./configure --add-module=../nginx-dav-ext-module --add-module=../nginx-http-auth-digest --with-http_ssl_module --with-http_dav_module --prefix=/etc/nginx
cpunum=$(nproc)
make -j$cpunum && make install
# cleanup
rm -r /tmp/nginx-*

# create web directory
mkdir -p /var/www

# clone repo
git clone https://github.com/HazCod/Gunther /var/www

# create webdav directory and set permission to the web user
mkdir -p /var/www/webdav
chown www-data:www-data -R /var/www/

# create webdav authentication file
# add admin user
htdigest_hash=`printf admin:Media:$ADMIN_PASSWORD| md5sum -`
echo "admin:Media:${htdigest_hash:0:32}" > /etc/nginx/webdav.auth

#create ssl directory
mkdir -p /etc/nginx/ssl-certs

#log directory
mkdir -p /var/log/nginx
chown www-data -R /var/log/nginx
touch /var/log/nginx/error.log
chmod 777 /var/log/nginx/error.log

#create certs
cd /etc/nginx/ssl-certs
openssl req \
    -new \
    -newkey rsa:4096 \
    -days 999 \
    -nodes \
    -x509 \
    -subj "/C=BE/ST=Vlaams-Brabant/L=Brussels/O=Global IT/OU=IT/CN=gunther.com" \
    -keyout gunther.key \
    -out gunther.crt \
    -sha256
    
#create DHE parameters, instead of 1024 default ones
cd /etc/ssl/certs
#openssl dhparam -out dhparam.pem 4096

#create nginx config
cat > /etc/nginx/conf/nginx.conf << EOF
user www-data;
worker_processes $(nproc);
error_log /var/log/nginx/error.log;

http {
	worker_connections $(ulimit -n);
	
        upstream php {
                server unix:/tmp/php5-fpm/sock;
        }
        
	#Only use secure ciphers
	ssl_ciphers 'EECDH+AESGCM:EDH+AESGCM:AES256+EECDH:AES256+EDH';
	ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
        
	client_body_buffer_size 10K;
	client_header_buffer_size 1k;
	client_max_body_size 8m;
	large_client_header_buffers 2 1k;
	
	client_body_timeout 12;
	client_header_timeout 12;
	keepalive_timeout 15;
	send_timeout 10;
        
        gzip on;
        gzip_http_version 1.0;
        gzip_comp_level 5;
        gzip_min_length 512;
        gzip_buffers 4 8k;
        gzip_proxied any;
        gzip_types
        # text/html is always compressed by HttpGzipModule
        text/css
        text/plain
        text/x-component
        application/javascript
        application/json
        application/xml
        application/xhtml+xml
        application/x-font-ttf
        application/x-font-opentype
        application/vnd.ms-fontobject
        image/svg+xml
        image/x-icon;

        root /var/www;

        ssl_certificate /etc/nginx/ssl-certs/gunther.crt;
        ssl_certificate_key /etc/nginx/ssl-certs/gunther.key;

	include /etc/nginx/conf/mime.types;
        
        server {
                # REDIRECT HTTP TO HTTPS
                listen 80;
                rewrite     ^   https://$server_name$request_uri? permanent;
        }
        server {
                listen 443 ssl;
                
                # Only allow over HTTPS
                add_header Strict-Transport-Security "max-age=63072000; includeSubdomains; preload";
		# Use stronger DHE parameter
		#ssl_dhparam /etc/ssl/certs/dhparam.pem;

                ssl_certificate     /etc/nginx/ssl-certs/gunther.crt;
                ssl_certificate_key /etc/nginx/ssl-certs/gunther.key;

                root /var/www;
                index index.php;

                location ~ /\. { deny all; access_log off; log_not_found off; }
                
                location /webdav {
                        auth_digest 'Media';
                        auth_digest_user_file /etc/nginx/webdav.auth;
                
                	autoindex On;
                	alias /mnt/media; #assuming your media is accessed in /mnt/media
                        dav_methods COPY;
                        dav_access all:r;
                }
                
                location / {
                    try_files $uri /index.php$is_args$args;
                }
                
                location /img/ { }
                location /js/ { }
                location /css/ { }
                location /fonts/ { }    
        
                location /index.php {
                    fastcgi_split_path_info ^(.+\.php)(/.+)$;
                    include fastcgi_params;
                    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
                    fastcgi_pass unix:/var/run/php5-fpm.sock;
                }

                location ~ /\.ht {
                        deny all;
                }
        }
} 
EOF

#create nginx service file
cat > /etc/init.d/nginx << 'EOF'
#! /bin/sh
 
### BEGIN INIT INFO
# Provides:          nginx
# Required-Start:    $all
# Required-Stop:     $all
# Default-Start:     2 3 4 5
# Default-Stop:      0 1 6
# Short-Description: starts the nginx web server
# Description:       starts nginx using start-stop-daemon
### END INIT INFO
 
PATH=/opt/bin:/opt/sbin:/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin
DAEMON=/etc/nginx/sbin/nginx
NAME=nginx
DESC=nginx
 
test -x $DAEMON || exit 0
 
# Include nginx defaults if available
if [ -f /etc/default/nginx ] ; then
        . /etc/default/nginx
fi
 
set -e
 
case "$1" in
  start)
        echo -n "Starting $DESC: "
        start-stop-daemon --start --quiet --pidfile /var/run/nginx.pid \
                --exec $DAEMON -- $DAEMON_OPTS
        echo "$NAME."
        ;;
  stop)
        echo -n "Stopping $DESC: "
        start-stop-daemon --stop --quiet --pidfile /var/run/nginx.pid \
                --exec $DAEMON
        killall nginx
        echo "$NAME."
        ;;
  restart|force-reload)
        echo -n "Restarting $DESC: "
        start-stop-daemon --stop --quiet --pidfile \
                /var/run/nginx.pid --exec $DAEMON
        killall nginx
        sleep 1
        start-stop-daemon --start --quiet --pidfile \
                /var/run/nginx.pid --exec $DAEMON -- $DAEMON_OPTS
        echo "$NAME."
        ;;
  reload)
      echo -n "Reloading $DESC configuration: "
      start-stop-daemon --stop --signal HUP --quiet --pidfile /var/run/nginx.pid \
          --exec $DAEMON
      echo "$NAME."
      ;;
  *)
        N=/etc/init.d/$NAME
        echo "Usage: $N {start|stop|restart|force-reload}" >&2
        exit 1
        ;;
esac
 
exit 0
EOF
chmod +x /etc/init.d/nginx
mkdir -p /var/tmp/nginx
#run nginx
update-rc.d nginx defaults
service nginx start

