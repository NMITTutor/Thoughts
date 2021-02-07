// Learn more about F# at http://fsharp.org

open System
open FizzBuzz

[<EntryPoint>]
let main argv =
    let start = 1
    let finish = 100
    
    printfn "FizzBuzz %i to %i"start finish
    let FizzBuzzes = FizzBuzz.FizzBuzzList start finish
    let Result = FizzBuzz.PrintFizzBuzzes FizzBuzzes 
    
    printfn "Show the FizzBuzzies" 
    let Result = FizzBuzz.PrintJustFizzBuzzes FizzBuzzes
    
    0 // return an integer exit code
