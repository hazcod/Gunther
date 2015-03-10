#!/bin/sh

username=$2
HTDIGEST_FILE=$1

AUTH_REALM=Media


if [ "$#" -eq 2 ]; then
	if [ -f "$HTDIGEST_FILE" ]; then
		# remove line number x (if file exists)
		sed -e "$2 d" "$HTDIGEST_FILE" > /var/www/digest_temp
		cat /var/www/digest_temp > "$HTDIGEST_FILE"
		rm /var/www/digest_temp
	fi
else
	echo "ERROR: Expects two arguments. (digest_file and username)"
fi