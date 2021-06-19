use std::io;

//use std::fmt;
//use std::collections::HashMap;

const TEPUTAKA: &str = "aāeēhiīknŋgoōprtuwʍ";
const MATI: &str ="0123456789";



fn accept_string(p_prompt: String) -> String {
    // Keeping the IO away from the code
    let mut which_test_in = String::new();

    if p_prompt.len() > 0{ // this is still nasty
     println!("{}",p_prompt);
    }
    io::stdin()
        .read_line( &mut which_test_in)
        .expect("Failed to read line");

    String::from(which_test_in.trim())
}




fn main() {
    let teputaka_nga = String::from(TEPUTAKA);
    let mati_nga = String::from(MATI);
    let whakauru = accept_string("Kia ora, me aha ahau?".to_string());
    let wha_chars = whakauru.chars();
     
    
    for wha in wha_chars{
        let pos = teputaka_nga.to_lowercase().chars().position(|c| c == wha );
        let a:usize;
        match pos {
            Some(x) =>  a = x+1,
            None => {
                let mati_pos = mati_nga.to_lowercase().chars().position(|c| c == wha );
                match mati_pos {
                    Some(x) => a = x + 1,
                    None => a = 0
                }
               
            }
            
        } ;
        println!( "{}  Hello, world!, '{}' is at??{}",whakauru.trim(),wha, a);
    };
        
       
   
}

