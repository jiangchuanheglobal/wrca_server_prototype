#! /bin/bash

# local test
#curl "http://localhost/proj/index?object=verificationCode&email=hejc09@gmail.com"
#curl "http://localhost/proj/index?object=password&email=hejc09@gmail.com"
#curl -d "object=login&email=hejc09@gmail.com&password=hjc" "http://localhost/proj/index"

# remote
#curl -d "object=register&email=hejc09@gmail.com&password=hjc&verificationCode=1234" "http://jiangchuan.info/php/index.php"

#curl "http://jiangchuan.info/php/index.php?object=password&email=hejc09@gmail.com&password=hjc" 

#curl -d "object=login&email=hejc09@gmail.com&password=hjc" "http://jiangchuan.info/php/index.php"

#curl "http://localhost/proj/index?object=events&type=all&offset=1"
#curl "http://jiangchuan.info/php/index.php?object=events&type=all&cursorPos=2"

#curl "http://localhost/proj/index?object=events&type=week&cursorPos=1"
#curl "http://localhost/proj/index?object=events&type=month"

#curl "http://jiangchuan.info/php/index.php?object=events&type=month&cursorPos=1"

curl -d "name=jc&email=hejc09@gmail.com&regId=APA91bGiI2CmMvfwCR-pNZ4GESNLudijfTcxw0Yj4GoK6JukC-8wvXjc3rMX_4iBgDfDJxGNJDm4YrbFrmyAMLMdz-pE3PXexhJh3JEYgsR3CybAixQVVZDixfMCjD8RIn60yZ1TD43esDtkldpsuDqLzOZTun-6oG6xHk3iRi0bwCM60vBI51o" "http://jiangchuan.info/gcm/register.php"
