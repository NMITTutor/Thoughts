import functools 
import FizzBuzz

reduce = functools.reduce # strange because map and filter are built in?

def main() :
    # Get the FizzBuzzers
    fizzbuzzers = FizzBuzz.fizzBuzzes(1,100)
    
    # Print a FizzBuzzer
    printFizzBuzzer = lambda x : print(str(x.value)+" "+x.buzzy)
    stringFizzBuzzer = lambda x,y : x+"\n"+str(y.value)+" "+y.buzzy

    # Print all FizzBuzzers
    # Using "map"
    # Using "reduce" - they could be reduced to a single string then printed?
   
    #print("Fizz Buzzes")
    #list(map(printFizzBuzzer, fizzbuzzers) ) # why do I need to "list" for "map" to "work"?
    print(reduce(stringFizzBuzzer, fizzbuzzers,"Fizz Buzzes") ) 
    print("\n")
    print(reduce(stringFizzBuzzer,\
         filter(lambda x: (x.buzzy == "Fizz" or  \
                          x.buzzy == "Buzz" or   \
                          x.buzzy == "FizzBuzz"),\
                fizzbuzzers)\
                ,"Just the FizzBuzzers")\
             )

if __name__ == "__main__":
    main()