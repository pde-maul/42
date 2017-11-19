#!/bin/sh

curl -s $1 | grep -i href | cut -d '"' -f2 |  grep -o ".*\w"
