<?php


array_map(function($value) { 
            $FizzOut = ($value % 5)+($value % 3) == 0 ? "FizzBuzz":
                    (($value % 5) == 0 ? "Buzz" :
                    (($value % 3) == 0 ? "Fizz" :$value
                    ))
                    ;
            echo  $value.":".$FizzOut."\n";
            return $FizzOut;
          }, range(1,35));
               

?>