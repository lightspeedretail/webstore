#!/bin/sh

STYLESHEETS="stylesheets"

if [ -d compass ]; then
	(
		cd compass
		git pull
	)
else
	git clone https://github.com/chriseppstein/compass.git
fi

[ -d "$STYLESHEETS" ] && rm -r "$STYLESHEETS"
mkdir "$STYLESHEETS"

cp -r compass/frameworks/compass/stylesheets/* "$STYLESHEETS"

# rename all the files
for file in $(find "$STYLESHEETS" | grep scss$); do
	out=$(echo $file | sed -e 's/\/_\([^\/]*\.scss\)$/\/\1/')
	if [ $file != $out ]; then
		# echo " -> " "$out"
		mv "$file" "$out"
	fi
done


