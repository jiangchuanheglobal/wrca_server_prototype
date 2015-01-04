#! /bin/bash

# local test
#curl "http://localhost/proj/index?object=verificationCode&email=hejc09@gmail.com"
#curl "http://localhost/proj/index?object=password&email=hejc09@gmail.com"
#curl -d "object=login&email=hejc09@gmail.com&password=hjc" "http://localhost/proj/index"

# remote
#curl -d "object=register&email=hejc09@gmail.com&password=hjc&verificationCode=1234" "http://jiangchuan.info/php/index.php"

#curl "http://jiangchuan.info/php/index.php?object=password&email=hejc09@gmail.com&password=hjc" 

#curl -d "object=login&email=hejc09@gmail.com&password=hjc" "http://jiangchuan.info/php/index.php"

curl "http://localhost/proj/index?object=events&type=all&cursorPos=1"
