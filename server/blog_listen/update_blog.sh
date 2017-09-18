#!/bin/sh

cd /root/repos/wusisu.com
git fetch
git reset --hard origin/master
bundle exec jekyll build
cp -r _site/* /web/wusisu.com/
