module FizzBuzz
type  fizzBuzzItem = {
      number : int;
      fuzzy : string;
}

let calcFizzBuzz x = 
    match x % 3, x % 5 with
       | 0, 0 -> {number= x; fuzzy = "FizzBuzz"}
       | 0, _ -> {number= x; fuzzy = "Fizz"}
       | _, 0 -> {number= x; fuzzy = "Buzz"}
       | _ -> {number= x; fuzzy = string  x }

let FizzBuzzList pFrom pTo = [pFrom .. pTo] |> List.map calcFizzBuzz

let OnlyFizzBuzzes x = 
    match x.fuzzy with
    | "FizzBuzz"  -> true
    | "Fizz"  -> true
    | "Buzz" -> true
    |  _ -> false

let PrintFizzBuzzes fizzbuzzes = 
    let printOne x = printfn "%s"  x.fuzzy
    List.map printOne fizzbuzzes |> ignore

let PrintFizzBuzzesDetails fizzbuzzes = 
    let printOne x = printfn "%s %s" (string x.number) x.fuzzy
    List.map printOne fizzbuzzes |> ignore

let PrintWhere fdetails fizzbuzzes selector =
    let whereFizzBuzzes = List.where selector fizzbuzzes
    fdetails whereFizzBuzzes

let PrintJustFizzBuzzes fizzbuzzes = 
    PrintWhere PrintFizzBuzzesDetails fizzbuzzes OnlyFizzBuzzes

    