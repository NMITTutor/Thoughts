use std::io;
use std::fmt;
use std::collections::HashMap;

fn main() {
    test_rig();
}

enum Test {
    Hash,
    Array,
    VectorEnum,
    Stats,
    PigLatin,
    Korero,
    Unknown,
    Stop
    
}

fn accept_string(p_prompt: String) -> String {
    // Keeping the IO away from the code
    let mut which_test_in = String::new();

    if p_prompt.len() > 0{ // this is still nasty
     println!("{}",p_prompt);
    }
    io::stdin()
        .read_line( &mut which_test_in)
        .expect("Failed to read line");

    which_test_in
}

fn tester() -> Option<Test> {
    
    
    let str_in : String = String::from(accept_string("Which test Toddles?".to_string()).to_string());//which_test_in.to_string();
  
    match str_in.as_str().trim() {
        "hash" =>  Some(Test::Hash),
        "array" =>  Some(Test::Array),
        "vector_num" => Some(Test::VectorEnum),
        "stats" => Some(Test::Stats),
        "stop" | "quit" => Some(Test::Stop),
        "piglatin" => Some(Test::PigLatin),
        "korero" => Some(Test::Korero),
        _ => { 
                  println!("Don't know that one ... ");
                  Some(Test::Unknown)
                },
    }
   
}

//

fn test_rig(){
    
    loop{ // ugh back to loops!!

        match tester() {
            Some(Test::Hash) => count_using_hash_map(),
            Some(Test::Array)=> get_value_in_array(),
            Some(Test::VectorEnum) => store_different_types_in_vectors(),
            Some(Test::Stats) => stats(),
            Some(Test::PigLatin) => pig_latin(),
            Some(Test::Korero) => korero_mai(),
            Some(Test::Unknown) => continue,
            Some(Test::Stop) => {
                   println!("Quiting :-) ");
                   break;
                },
            None => {
                    println!("This should never happen");
                    break;
            }
        }
    }
}

// testing examples 
fn count_using_hash_map(){

    
        let text = "hello world wonderful world";
    
        let mut map = HashMap::new();
    
        for word in text.split_whitespace() {
            let count = map.entry(word).or_insert(0); // returns a mutable reference (&mut V) to the value for this key
            *count += 1; // that means count is a reference to the value at entry hashed by the key "word", 
                         // so "*count += 1" adds one to the value at "word" --- nasty
        }
    
        println!("{:?}", map);
  
      
}

fn get_value_in_array(){
    let a = [1, 2, 3, 4, 5];

    let index = accept_string("Please enter an array index.".to_string());
    let index: usize = index
        .trim()
        .parse()
        .expect("Index entered was not a number");

    let element = a[index];

    println!(
        "The value of the element at index {} is: {}",
        index, element
    );
}

fn store_different_types_in_vectors(){
    
        enum SpreadsheetCell {
            Int(i32),
            Float(f64),
            Text(String),
        }
        // need a diplay formatter for this (enum) type, to print it out.
        impl fmt::Display for SpreadsheetCell {
            fn fmt(&self,f:&mut fmt::Formatter) -> fmt::Result {
                match self {
                    SpreadsheetCell::Int(int_value) => write!(f, "{}",int_value),
                    SpreadsheetCell::Float(float_value)  => write!(f, "{}",float_value),
                    SpreadsheetCell::Text(string_value) => write!(f, "{}",string_value),
                }
            }
        }

        //  vector of SpreadsheetCells 
        let row = vec![
            SpreadsheetCell::Int(3),
            SpreadsheetCell::Text(String::from("blue")),
            SpreadsheetCell::Float(10.12),
        ];

        // print it out
        for v in &row {
             println!("{}",v);
        }
            
}
    
// Exercises from   https://doc.rust-lang.org/book/ch08-03-hash-maps.html

// 1.
 // Given a list of integers, use a vector and return the mean (the average value), 
 // median (when sorted, the value in the middle position), and mode (the value that occurs most often; 
 //  a hash map will be helpful here) of the list.
fn stats(){

    let list_of_numbers = accept_string("Enter a list of numbers (with decimal points), separated by spaces".to_string());
    
    // Ugh heaps of variables .. 
    let mut map = HashMap::new();
    let mut vec_of_numbers :Vec<f64> = Vec::new();
    let mut mode_count: i32 = 0;
    let mut mode: f64 = 0.0;
    let mut mean: f64 = 0.0;
    let mut count_sofar : f64 = 0.0;
    for number in list_of_numbers.split_whitespace(){
        let float_number:f64 = number.trim().parse::<f64>().unwrap(); 
        
        // A running Mean
        count_sofar +=1.0;
        mean = ((mean * (count_sofar - 1.0)) +float_number)/count_sofar;
        
        // A running Mode - using a Hash
        // This is cobbled from Hash example above.
        // Not happy with this - I think it is storing strings in the Hash ??
        // Because f64 doe n't have the the right traits (hmmm this must be wrong).
        let count_of = map.entry(number.trim()).or_insert(0); // returns a mutable reference (&mut V) to the value for this key
        *count_of += 1; 
        mode = if *count_of > mode_count {
            mode_count = *count_of; // ooo naughty side effect in an if value?
            float_number
           } else { mode};
        
        // This part collects the numbers into a vector for median
        vec_of_numbers.push(float_number);
    }

    // median - using sorting
    vec_of_numbers.sort_by(|a, b| a.partial_cmp(b).unwrap());
    let n = vec_of_numbers.len();
    let median = if (n  % 2) != 0 {
                        vec_of_numbers[((n+1)/2) -1]
                    } else {
                        (vec_of_numbers[(n/2) -1 ] + vec_of_numbers[((n/2)+1) - 1]) /2.0 
                    };

    println!("Mean = {} , Median = {}, Mode = {}",mean,median, mode);
    

}

// 2.
// Convert strings to pig latin. 
// The first consonant of each word is moved to the end of the word and “ay” is added, so “first” becomes “irst-fay.”
// Words that start with a vowel have “hay” added to the end instead (“apple” becomes “apple-hay”). 
// Keep in mind the details about UTF-8 encoding!
fn pig_latin(){
    // Could think about fixing case so the transation keeps the case on each word.
    let mut piggies = String::from("");

    let phrase = accept_string("Enter phrase to translate to Pig Latin".to_string());
    for word in phrase.trim().split_whitespace(){
        // first character 
        let c =  word.clone().chars().nth(0).unwrap(); // not sure if I need to clone
        println!("First character is {} ",c);   
        match c {
            'A' |'a' | 'E' |'e'| 'I'| 'i' | 'O' |'o' | 'U'| 'u' => {
                piggies.push_str(word);
                piggies.push_str("-hay ");
                 
            }
            'A'..='Z' |  'a'..='z'  => {
                let mut end_slice = String::from("-");
                end_slice.push(c);
                end_slice.push_str("ay ");
                let end_slice = &end_slice[0..];
                 
                let front_slice = &word[c.len_utf8() ..]; // UTF-8 encoding
                piggies.push_str(front_slice);
                piggies.push_str(end_slice);
                 

            }
            _ => { println!("Should never reach here!");}

        }
              
                
        
    }

    println!("Piglatin for \"{}\" is \"{}\"",phrase,piggies);

}


// 3.
// Using a hash map and vectors, 
// create a text interface to allow a user to add employee names to a department in a company. 
// For example, “Add Sally to Engineering” or “Add Amir to Sales.” 
// Then let the user retrieve a list of all people in a department or all people
// in the company by department, sorted alphabetically.
fn korero_mai(){

    // TePūTaka
    #[derive(Copy, Clone)]
    #[derive(Debug)]
    enum TePuTaka{
        a, e, h, i, k, m, n, ng, o, p, r, t, u, w, wh, // Pūriki
        A, E, H, I, K, M, N, NG, O, P, R, T, U, W, WH, // Pūmatua
        Nah(char)

    }
    impl fmt::Display for TePuTaka {
        fn fmt(&self,f:&mut fmt::Formatter) -> fmt::Result {
            match self {
                TePuTaka::a => write!(f, "{}","a"),
                TePuTaka::e => write!(f, "{}","e"),
                TePuTaka::h => write!(f, "{}","h"),
                TePuTaka::i => write!(f, "{}","i"),
                TePuTaka::k => write!(f, "{}","k"),
                TePuTaka::n => write!(f, "{}","n"),
                TePuTaka::ng => write!(f, "{}","ng"),
                TePuTaka::o => write!(f, "{}","o"),
                TePuTaka::p => write!(f, "{}","p"),
                TePuTaka::r => write!(f, "{}","r"),
                TePuTaka::t => write!(f, "{}","t"),
                TePuTaka::u => write!(f, "{}","u"),
                TePuTaka::w => write!(f, "{}","w"),
                TePuTaka::wh => write!(f, "{}","wh"),
                TePuTaka::A => write!(f, "{}","A"),
                TePuTaka::E => write!(f, "{}","E"),
                TePuTaka::H => write!(f, "{}","H"),
                TePuTaka::I => write!(f, "{}","I"),
                TePuTaka::K => write!(f, "{}","K"),
                TePuTaka::N => write!(f, "{}","N"),
                TePuTaka::NG => write!(f, "{}","NG"),
                TePuTaka::O => write!(f, "{}","O"),
                TePuTaka::P => write!(f, "{}","P"),
                TePuTaka::R => write!(f, "{}","R"),
                TePuTaka::T => write!(f, "{}","T"),
                TePuTaka::U => write!(f, "{}","U"),
                TePuTaka::W => write!(f, "{}","W"),
                TePuTaka::WH => write!(f, "{}","WH"),
                TePuTaka::Nah(a_char) => write!(f, "{} ={}",a_char,"Nah"),
                _ => write!(f, "{}","Not known"),
            }
        }
    }  


    #[derive(Copy, Clone)]
    #[derive(Debug)]
    enum Tua{
        tahi, rua
    }
    impl fmt::Display for Tua {
        fn fmt(&self,f:&mut fmt::Formatter) -> fmt::Result {
            match self {
                Tua::tahi => write!(f,"1"),
                Tua::rua => write!(f,"2"),
            }
        }
    }
 
    #[derive(Clone,Debug)]
    struct Kupu{
        pub nga_teputaka : Vec<TePuTaka>,
        pub kiwaha: Tua, // kīwaha
        pub tuponotanga :f64, //tūponotanga
    }
    impl fmt::Display for Kupu {
        fn fmt(&self,f:&mut fmt::Formatter) -> fmt::Result {
            let mut tk_str : String = String::new();
            for tk in self.nga_teputaka.clone(){
                tk_str.push_str(format!("{}",tk).trim());
            }
            write!(f,"{},{},{}",tk_str,self.kiwaha,self.tuponotanga)
        }
    }

    fn e_teputaka(c:char) -> TePuTaka{
        match c {
            'a' =>  TePuTaka::a,
            'ā' =>  TePuTaka::ā,
            'e' =>  TePuTaka::e,
            'h' =>  TePuTaka::h,
            'i' =>  TePuTaka::i,
            'k' =>  TePuTaka::k,
            'm' =>  TePuTaka::m,
            'n' =>  TePuTaka::n,
            'g' =>  TePuTaka::ng,
            'o' =>  TePuTaka::o,
            'p' =>  TePuTaka::p,
            'r' =>  TePuTaka::r,
            't' =>  TePuTaka::t,
            'u' =>  TePuTaka::u,
            'w' =>  TePuTaka::w,
            'A' =>  TePuTaka::A,
            'E' =>  TePuTaka::E,
            'H' =>  TePuTaka::H,
            'I' =>  TePuTaka::I,
            'K' =>  TePuTaka::K,
            'M' =>  TePuTaka::M,
            'N' =>   TePuTaka::N,
            'G' =>  TePuTaka::NG,
            'O' => TePuTaka::O,
            'P' => TePuTaka::P,
            'R' => TePuTaka::R,
            'T' => TePuTaka::T,
            'U' => TePuTaka::U,
            'W' => TePuTaka::W,
         
             _ => TePuTaka::Nah(c)

        }
    }

    
    fn token_strs(in_str : String) -> Vec<String> {
        #[derive(Copy, Clone)]
        #[derive(Debug)]
        enum TokenState{
            Tuatahi,Kupu, Literal //, Punctuation
        }
        let mut toks: Vec<String> = Vec::new(); 
        let mut token: String = String::from("");
        let mut state: TokenState = TokenState::Kupu;
        let mut lit_start:char ='`'; // place holder
        for c in in_str.chars() {
            let teputaka:TePuTaka = e_teputaka(c);
            match state {
                TokenState::Tuatahi =>{
                    match teputaka{
                        TePuTaka::Nah(a_char) => state = {
                            match a_char {
                                '"' | '\'' => {lit_start = a_char; token.push(a_char);TokenState::Literal}, // this recognizes literals 
                                '.'| '?' |',' | '-' | '+' | '/' | '*' | '=' | '>' | '<' | '!' | '@' | '#' | '$' | '%' | '^' | 
                                '&' | '(' | ')' | '{'|'}' | '[' |']' | '|' | '\\' | ':' | ';'  =>  // "/" can not match ?? why
                                {  
                                 toks.push(String::from(format!("{}",a_char))); // here we need to consume a punctuation mark, or an operator
                                 TokenState::Tuatahi
                                },
                                '0'..='9' => {  
                                    toks.push(String::from(format!("{}",a_char))); // here we need to consume a punctuation mark, or an operator
                                    TokenState::Tuatahi
                                   },
                                _ => TokenState::Tuatahi //  skips any char we're not interested in
                            }
                        },
                        _ => state = {
                            token.push(c);
                            TokenState::Kupu
                        }
                    }
                },
                TokenState::Kupu => {
                    match teputaka {
                        TePuTaka::Nah(a_char) => state = { // end of kupu
                            toks.push(token.clone());
                            token = String::from("");
                            
                            match a_char{
                                '"' | '\'' =>{ token.push(a_char); TokenState::Literal}, // put literal character at the front
                                '.'| '?' |',' | '-' | '+' | '/' | '*' | '=' | '>' | '<' | '!' | '@' | '#' | '$' | '%' | '^' | 
                                '&' | '(' | ')' | '{'|'}' | '[' |']' | '|' | '\\' | ':' | ';'  => 
                                {  
                                 toks.push(String::from(format!("{}",a_char))); // here we need to consume a punctuation mark, or an operator
                                 TokenState::Tuatahi
                                },
                                '0'..='9' => {  
                                    toks.push(String::from(format!("{}",a_char))); // here we need to consume a punctuation mark, or an operator
                                    TokenState::Tuatahi
                                   },
                                _ => TokenState::Tuatahi
                            }
                            
                        },
                        _ => state = {
                            token.push(c);
                            TokenState::Kupu
                        }
                    }
                },
                TokenState::Literal => state = {
                    match c {
                       '\'' | '"' => { // end of Literal - this is consumed
                            if c == lit_start { 
                                token.push(c);
                                toks.push(token.clone());
                                token = String::from("");
                                TokenState::Tuatahi
                            }
                            else { token.push(c); TokenState::Literal}
                        },
                        _ => { token.push(c); TokenState::Literal}
                    }
                },
                _ => {}
            }
        }
        toks
    } 
    fn whakaae_kupu() -> Vec<Kupu>{
        let mut kupa_nga : Vec<Kupu> = Vec::new();

        let whakauru = accept_string("Kia ora, me aha ahau?".to_string());
        
        for ru in token_strs(whakauru){ //})whakauru.trim().split_whitespace(){
            let ru = String::from(ru);
            let mut teputaka_nga = Vec::new();
            let mut state = 0;

            for c in ru.chars(){
                let teputaka:TePuTaka = e_teputaka(c);
                    match state {
                        0 => {
                            match teputaka{
                                TePuTaka::n => {state = 1;},
                                TePuTaka::w => {state = 2;},
                                TePuTaka::N => {state = 3;},
                                TePuTaka::W => {state = 4;},
                                _ => {
                                    teputaka_nga.push(teputaka);
                                    }
                            }
                        },
                        1 => {
                            match teputaka{
                                    TePuTaka::ng |TePuTaka::NG => {
                                                    
                                                    teputaka_nga.push(teputaka);
                                                },
                                    _ => {
                                        
                                        teputaka_nga.push(TePuTaka::n);
                                        teputaka_nga.push(teputaka);
                                    }
                                    
                            };
                            state = 0;

                        },
                        2 => {
                            match teputaka{
                                    TePuTaka::h  => {
                                                    
                                                    teputaka_nga.push(TePuTaka::wh);
                                                },
                                    TePuTaka::H => {
                                                    teputaka_nga.push(TePuTaka::WH);
                                                },
                                    _ => {
                                        
                                        teputaka_nga.push(TePuTaka::w);
                                        teputaka_nga.push(teputaka);
                                    }
                                    
                            };
                            state = 0;

                        },
                        3 => {
                            match teputaka{
                                    TePuTaka::NG | TePuTaka::ng => {
                                                    
                                                    teputaka_nga.push(teputaka);
                                                },
                                    _ => {
                                        
                                        teputaka_nga.push(TePuTaka::N);
                                        teputaka_nga.push(teputaka);
                                    }
                                    
                            };
                            state = 0;

                        },
                        4 => {
                            match teputaka{
                                    TePuTaka::H | TePuTaka::h => {
                                                    
                                                    teputaka_nga.push(TePuTaka::WH);
                                                },
                                    _ => {
                                        
                                        teputaka_nga.push(TePuTaka::W);
                                        teputaka_nga.push(teputaka);
                                    }
                                    
                            };
                            state = 0;

                        },
                        _ => {
                            state = 0;
                        }
                        
                    }
                
            }
            let kupu : Kupu = Kupu{
                nga_teputaka :teputaka_nga,
                kiwaha: Tua::tahi,
                tuponotanga: 1.0
            };

            kupa_nga.push(kupu);
        }

        kupa_nga
    }

    let kupa_nga : Vec<Kupu> = whakaae_kupu();

    println!("{:?}",kupa_nga)
}