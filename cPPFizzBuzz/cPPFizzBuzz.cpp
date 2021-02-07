// cPPFizzBuzz.cpp 
//  Not OOP a bit of fun with recursion.
//  Have used a For loop to output in this one.
//
using namespace std;
#include <iostream>
#include <string>

typedef struct {
    int number;
    char fizzBuzz[9];
} FizzBuzzer;

const char fizz[9] = { 'F','i','z','z','\0' };
const char buzz[9]  = { 'B','u','z','z','\0' };
const char fizzbuzz[9] = { 'F','i','z','z','B','u','z','z','\0' };
void makeFizzBuzzer(int x, FizzBuzzer &fB) {
    fB.number = x;
    if (x % 3 == 0 && x % 5 == 0) {
        strcpy_s(fB.fizzBuzz,fizzbuzz);
    }
    else if (x % 3 == 0) {
        strcpy_s(fB.fizzBuzz, fizz);
    }
    else if (x % 5 == 0) {
        strcpy_s(fB.fizzBuzz, buzz);
    }
    else {
        
        _itoa_s(x, fB.fizzBuzz,10);
    }
}
FizzBuzzer* fillFizzBuzzesArray(FizzBuzzer* pFb, int pFrom, int pTo) {
    static int firstFrom = pFrom;
    makeFizzBuzzer(pFrom, pFb[pFrom - firstFrom]);
    return
        pFrom == pTo ?  pFb
                     :
                       fillFizzBuzzesArray(pFb, pFrom + 1, pTo);

}

FizzBuzzer* makeFizzBuzzers(int pFrom, int pTo) {
    int numberOfFizzBuzzers = (pTo - pFrom) + 1;
    FizzBuzzer* fizzBuzzersArray = (FizzBuzzer *)malloc(sizeof(FizzBuzzer) * numberOfFizzBuzzers);

    fizzBuzzersArray = fillFizzBuzzesArray(fizzBuzzersArray, 1, pTo);

    return fizzBuzzersArray;
}
int main()
{
    std::cout << "Fizz Buzz\n";

    int from, to;
    FizzBuzzer* fizzBuzzers = makeFizzBuzzers(1, 100);
    for (int i = 0; i < 100; i++)
        printf("%d %s \n", fizzBuzzers[i].number, fizzBuzzers[i].fizzBuzz);
    free(fizzBuzzers);

}

