// Here 
// - a contrived tutu with enums badly select a method
// - a Seq of type T is an extended array
// - some fun with putting the type into Seq - not quite right 

let a: string = "FizzBuzz Sequence FizzBuzzers use thier showMe";

console.log(a);

enum FizzSelector {
   Value, FizzBuz,Both,
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
     
    constructor(value :number){
        this.value = value;
        
           if ((value % 3 == 0) && (value % 5 == 0) )this.fizzbuzz =  "fizzbuzz";
           else if (value % 3 == 0) this.fizzbuzz = "fizz" 
           else if (value % 5 == 0) this.fizzbuzz = "buzz" 
           else this.fizzbuzz = value.toString();
        
    }
}

class Seq<T> extends Array{
    start : number;
     end : number;

    private   next = function(i :number,pTo : number, newValue: (i: number) => T) {
        if(i <= pTo){
            this[i] =  newValue(i);
            console.log(this[i]);
            this.next(i+1,pTo, newValue);
        }
    }
    constructor(pFrom: number,pTo :number, newValue: (i: number) => T ){
       
       
        super();
        
        this.start = pFrom;
        this.end = pTo;
        console.log(pFrom,"  ",pTo)
        this.next(pFrom,pTo,newValue);  
    }



}

// Some tests
let aFizzBuzz = new FizzBuzz(1);
aFizzBuzz.showMe(FizzSelector.Both);

let bFizzBuzz = new FizzBuzz(3);
bFizzBuzz.showMe(FizzSelector.Both);

let cFizzBuzz = new FizzBuzz(5);
cFizzBuzz.showMe(FizzSelector.Both);

let dFizzBuzz = new FizzBuzz(15);
dFizzBuzz.showMe(FizzSelector.Both);

function FizzBuzzMaker(i : number){
    return new FizzBuzz(i);
}

const someFizzBuzzes = new Seq<FizzBuzz>(1,15,FizzBuzzMaker);

someFizzBuzzes.forEach(function(fb:FizzBuzz){
    fb.showMe(FizzSelector.Both)
})




