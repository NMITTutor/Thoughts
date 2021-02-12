let a: string = "Hello, Nothing here yet";

console.log(a);
enum FizzSelector {
   Value, FizzBuz,
}
interface AFizzBuzz {
    value : number;
    fizzbuzz : string;
    showMe : (which? : FizzSelector) => void ,
}

class FizzBuzz implements AFizzBuzz {
    value : number;
    fizzbuzz : string;
    showMe = (which? : FizzSelector) => 
    {
        if(which === FizzSelector.Value ) {
            console.log(this.value);
        }
        else if(which === FizzSelector.FizzBuz ) {
            console.log(this.fizzbuzz);
        }
        else {
            console.log(this.value, this.fizzbuzz);
        }
        

    } 
     
    constructor(value :number, fizzbuzz : string){
        this.value = value;
        this.fizzbuzz = fizzbuzz;
    }
}

let aFizzBuzz = new FizzBuzz(0,"0");
aFizzBuzz.showMe(FizzSelector.FizzBuz);

