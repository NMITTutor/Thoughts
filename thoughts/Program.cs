using System;


namespace cSharpFizzBuzz
{

    class Program
    {
        static void Main(string[] args)
        {
            FizzBuzz aFizzBuzz_100 = new FizzBuzz(1, 100);

            Console.WriteLine("FizzBuzz {0} to {1}", 1, 100);
            Console.WriteLine(aFizzBuzz_100.ToFizzBuzzesString());

            Console.WriteLine("Show the FizzBuzzies");
            Console.WriteLine(aFizzBuzz_100.ToFuzziesString());
        }
    }
}
