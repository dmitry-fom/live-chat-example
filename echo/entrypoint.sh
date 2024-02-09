#!/bin/sh
set -e

npm install

file="laravel-echo-server.lock"
if [[ -f ${file} ]] ; then
    rm ${file}
fi

npm start