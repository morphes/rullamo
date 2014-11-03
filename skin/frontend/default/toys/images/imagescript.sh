#!/bin/bash
echo " "
echo "--==Created by Alex Berber==--"
echo "--===www.linuxspace.org==--"
echo " "
 
mkdir NEW_RESIZE_PHOTO;
for i in *.JPG ;
do
echo "Working on $i ..."
convert -quality 90 "$i" "NEW_RESIZE_PHOTO/${i%.JPG}" ;
 
done
 
for i in *.jpg ;
do
echo "Working on $i ..."
convert -quality 90 "$i" "NEW_RESIZE_PHOTO/${i%.jpg}" ;
 
done
 
for i in *.gif ;
do
echo "Working on $i ..."
convert -quality 90 "$i" "NEW_RESIZE_PHOTO/${i%.gif}" ;
 
done
 
for i in *.png ;
do
echo "Working on $i ..."
convert -quality 90 "$i" "NEW_RESIZE_PHOTO/${i%.png}" ;
 
done
 
echo " "
echo "... Done!"
echo " "
 
exit 0
