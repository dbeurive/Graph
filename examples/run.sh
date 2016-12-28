#!/bin/bash

# This script will run all the examples.

SOURCE="${BASH_SOURCE[0]}"
while [ -h "$SOURCE" ] ; do SOURCE="$(readlink "$SOURCE")"; done
printf -v __PWD__ "%q" "$( cd -P "$( dirname "$SOURCE" )" && pwd )"

rm -f $__PWD__/csvs/*.csv
rm -f $__PWD__/dots/*.dot
rm -f $__PWD__/images/*.gif
rm -f $__PWD__/scripts/*.sh

for filename in $__PWD__/*.php; do
    php $filename > /dev/null
done

for filename in $__PWD__/scripts/*.sh; do
    $filename
done
