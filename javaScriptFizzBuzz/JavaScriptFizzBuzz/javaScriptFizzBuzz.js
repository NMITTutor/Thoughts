/*
   Hacky FizzBuzz 
   ===============
   Non OOP - sort of paradigmatically Functional programming
     - Added seq to Array 
      so Array can be a sequence of numbers in a given range
   - Uses map to make the "FizzBuzzes" {number:x, fizzbuzz:""} 

*/
Array.seq = function (pFrom, pTo) {

    // JavaScripty local function
    function seq(r, from, to) {

        if (from === to)
            return r.concat([to]);
        else
            return [from].concat(seq(r, from + 1, to));
    } // seq makes the new array

    return seq([], pFrom, pTo)
}// seq


// FizzBuzz on any int Fizz and any int Buzz, 
// - in the range (from, to )
function FizzBuzz(pIntFizz, pIntBuzz,
    pIntFrom, pIntTo) {
    return Array.seq(pIntFrom, pIntTo).map(
        x => {
            let result = { number: x, fizzbuzz: x.toString() }

            switch (true) {
                case x % pIntFizz == 0 && x % pIntBuzz == 0:
                    result.fizzbuzz = "FizzBuzz";
                    break;
                case x % 3 == 0:
                    result.fizzbuzz = "Fizz";
                    break;
                case x % 5 == 0:
                    result.fizzbuzz = "Buzz";
                    break;
                default: result = result; // Gah!
            }
            return result; // One return 
        }
    );
}// FizzBuzz

anArrayOfFizzBuzzes = FizzBuzz(3, 5, 10, 35);
console.log(anArrayOfFizzBuzzes);

// Nasty add of result to HTML
document.body.onload = addElement;
function addElement() {
    const fizzBuzzesDiv = document.createElement("div");
    fizzBuzzesDiv.innerHTML = JSON.stringify(anArrayOfFizzBuzzes);
    document.body.appendChild(fizzBuzzesDiv)

}
