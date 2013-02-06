#!/bin/bash

SOURCE=`pwd`/
TARGET=$1

FILES=`find *` 

for f in $FILES; do
    target=$TARGET$f
    if [ -f $target ]; then
        rm $target
    fi

    source=$SOURCE$f

    ln -s $source $target
done

