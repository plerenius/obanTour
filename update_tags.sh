#!/bin/sh
echo "Update tags in repo..."
git grep -l ":git_id:" | xargs sed -i "s/:git_id:/`git describe --abbrev=10 --dirty --always --tags`/"
git grep -l ":pub_date:" | xargs sed -i "s/:pub_date:/$(date '+%Y-%m-%d %H:%M')/"