#!/bin/bash

if [ "$#" -eq 2 ]; then
	# auth realm for digest auth
	AUTH_REALM=Media

	# input variables
	username=$2
	HTDIGEST_FILE=$1

	# generate a pseudo-random password
	rand_pw=`< /dev/urandom tr -dc _A-Z-a-z-0-9 | head -c12`

	# hash the username, realm, and password
	htdigest_hash=`printf $username:$AUTH_REALM:$rand_pw | md5sum -`

	if [ -f "$HTDIGEST_FILE" ]; then
		# remove previous username lines (if file exists)
		sed "/$username:$AUTH_REALM/d" "$HTDIGEST_FILE" > /var/www/digest_temp
		cat /var/www/digest_temp > "$HTDIGEST_FILE"
		rm /var/www/digest_temp
	fi

	# build an htdigest appropriate line, and tack it onto the file
	echo "$username:$AUTH_REALM:${htdigest_hash:0:32}" >> "$HTDIGEST_FILE"
	echo "$rand_pw"
else
	echo "ERROR: Expects two arguments. (digest_file and username)"
fi
