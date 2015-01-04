#! /usr/local/bin/bash

# auto fetch image from web

for i in {1..15}
do
url="http://api.androidhive.info/json/movies/"$i".jpg"
wget $url
done

