#! /bin/bash

#curl "http://localhost/proj/index?object=verificationCode&email=hejc09@gmail.com"
curl -d "object=login&email=hejc09@gmail.com&password=hjc" "http://localhost/proj/index"
