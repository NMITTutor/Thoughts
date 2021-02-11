"""FizzBuzz Module
   Methods for FizzBuzzing
"""
getFizzBuzz = lambda x : FizzBuzzer(x,"FizzBuzz") if (x % 3 == 0 and x % 5 == 0) else \
                         FizzBuzzer(x,"Fizz")     if (x % 3 == 0 ) else \
                         FizzBuzzer(x,"Buzz")     if (x % 5 == 0) \
                         else FizzBuzzer(x,str(x))

fizzBuzzes = lambda start,end : list(map(getFizzBuzz,range(start,end +1)))

class FizzBuzzer(object):
     """Stores a FizzBuzzer"""
     def __init__(self, value, buzzy):
        self.value = value
        self.buzzy = buzzy
        




