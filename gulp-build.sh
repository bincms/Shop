#!/bin/bash

baseDir=${PWD}

cd Resources

./node_modules/gulp/bin/gulp.js build

cd $baseDir