#!/bin/sh

username=$2
HTDIGEST_FILE=$1

AUTH_REALM=Media


if [ "$#" -eq 2 ]; then
	if [ -f "$HTDIGEST_FILE" ]; then
		sed -i "/$username:Media/d" "$HTDIGEST_FILE"
	fi
else
	echo "ERROR: Expects two arguments. (digest_file and username)"
fi
