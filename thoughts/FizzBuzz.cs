using System;
using System.Collections.Generic;
using System.Linq;

namespace cSharpFizzBuzz
{
    class FizzBuzz
    {
        private List<FizzBuzzItem> _fizzBuzzes;
        private readonly int _start;
        private readonly int _finish;

        public FizzBuzz(int pfrom, int pTo)
        {
            _start = pfrom;
            _finish = pTo;
            MakeFizzBuzzes();
        }

        private void MakeFizzBuzzes()
        {
            // is this needed in c#?
            if (_fizzBuzzes != null)
                _fizzBuzzes = null;

            // Make a new list of fizzBuzzes using the integer range from start to finish
            _fizzBuzzes = Enumerable.Range(_start, _finish).Select<int, FizzBuzzItem>(
                 x => {
                     string fizzBuzzStr =    (x % 3, x % 5) switch{
                                                 (0, 0)     => "FizzBuzz",
                                                 (0, _)     => "Fizz",
                                                 (_, 0)     => "Buzz",
                                                  _         => x.ToString()
                                             };

                     return new FizzBuzzItem { number = x, fuzzy = fizzBuzzStr };
                 }
                 ).ToList<FizzBuzzItem>();
        }

        public string ToFizzBuzzesString()
        {
            string result = "";
            _fizzBuzzes.ForEach(x => {
                result += (x.fuzzy +"\n");
                }
            );

            return result;
        }

        private List<FizzBuzzItem> Fuzzies()
        {
           return _fizzBuzzes.Where(x =>
            {
                bool blnFizzies = (x.fuzzy switch
                {
                    ("Fizz") => true,
                    ("Buzz") => true,
                    ("FizzBuzz") => true,
                    _ => false
                });
                return blnFizzies;
            }

            ).ToList<FizzBuzzItem>();
        }
        public string ToFuzziesString()
        {
            string result = "";
            Fuzzies().ForEach(x =>
            {
                result += x.number.ToString() + " " + x.fuzzy + "\n";
            });

            return result;
        }

    }
    class FizzBuzzItem
    {
        public int number { get; set; }
        public string fuzzy { get; set; }
    }
}
