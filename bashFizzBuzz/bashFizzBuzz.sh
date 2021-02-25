#!/usr/bin/env bash

echo "Functional recursive "
fizzBuzz(){ 
    # need to check arguments
    mod3Value=$(($1 % 3 ));
    mod5Value=$(($1 % 5 ));
   
    if [ $mod3Value == 0 ] && [ $mod5Value == 0 ] ; then
        echo $1" FizzBuzz";
    elif [ $mod3Value == 0 ]; then
        echo $1" Fizz";
    elif [ $mod5Value == 0 ]; then
        echo $1" Buzz";
    else 
        echo $1" _";
    fi;
}

# Fizz Buzz tests
# echo "Fizz Buzz tests";
# fizzBuzz 1;
# fizzBuzz 0;
# fizzBuzz 3;
# fizzBuzz 5;
# fizzBuzz 15;

fizzBuzzers(){
    # Need to check arguments
    from=$(($1));
    to=$(($2));
    
    if [ $from -le $to ] ; then
     fizzBuzz $1 ;
     fizzBuzzers $(($from+1)) $(($to));
       
    fi;
}

# fizzBuzzers test
# fizzBuzzers 1 15

# Check for arguments
fizzBuzzers $1 $2;
echo " "
echo "More bashy";

# this is heaps slower, because ... ?
function bashyFizz(){
  cat <&0 > seq.lst
  linesLeft=$(<seq.lst wc -l) 
  if [ $linesLeft -gt 0 ] 
   then
    first=$(<seq.lst head -n1 )
    <seq.lst tail -n+2  > rest.lst
    fizzBuzz $first 
    <rest.lst bashyFizz # recursive call, plus could pipe it here
  else
    rm rest.lst
    rm seq.lst
  fi
 

}

function bashyFizzBuzzers(){
  # Need to check arguments
  seq $1 1 $2 |  bashyFizz
}

bashyFizzBuzzers $1 $2 
