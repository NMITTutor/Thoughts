
with digit as (
    select 0 as d union all 
    select 1 union all select 2 union all select 3 union all
    select 4 union all select 5 union all select 6 union all
    select 7 union all select 8 union all select 9        
),
seq as (
    select a.d + (10 * b.d) + (100 * c.d) + (1000 * d.d) as num
    from digit a
        cross join
        digit b
        cross join
        digit c
        cross join
        digit d
    order by 1        
)
select seq.num as "Seq"
from seq
where seq.num <= 100;

select *
from 
    (select 0 as d union all 
    select 1 union all select 2 union all select 3 union all
    select 4 union all select 5 union all select 6 union all
    select 7 union all select 8 union all select 9  ) as digit;  
    
select  1 + (a.d * 10) +  b.d  as seq100
from 
    (select 0 as d union all 
    select 1 union all select 2 union all select 3 union all
    select 4 union all select 5 union all select 6 union all
    select 7 union all select 8 union all select 9  ) as a 
    cross join
	(select 0 as d union all 
    select 1 union all select 2 union all select 3 union all
    select 4 union all select 5 union all select 6 union all
    select 7 union all select 8 union all select 9  ) as  b
order by 1;

with digit as (
    select 0 as d union all 
    select 1 union all select 2 union all select 3 union all
    select 4 union all select 5 union all select 6 union all
    select 7 union all select 8 union all select 9        
),
seq as (
    select a.d + (10 * b.d) + (100 * c.d) + (1000 * d.d) as num
    from digit a
        cross join
        digit b
        cross join
        digit c
        cross join
        digit d
    order by 1        
)
select 
    seq.num,
    case when  (seq.num % 3 = 0) AND (seq.num % 5 = 0) then 'FIZZBUZZ' 
         when  (seq.num % 3 = 0) then 'FIZZ' 
         when  (seq.num % 5 = 0) then 'BUZZ' 
		else seq.num
    end as "FizzBuzz"
from seq
where seq.num > 0 and seq.num <= 100;


WITH RECURSIVE seq AS (SELECT 1 AS value UNION ALL SELECT value + 1 FROM seq WHERE value < 100)
  SELECT value, 
         case when  (seq.value % 3 = 0) AND (seq.value % 5 = 0) then 'FIZZBUZZ' 
          when  (seq.value % 3 = 0) then 'FIZZ' 
          when  (seq.value % 5 = 0) then 'BUZZ' 
		  else seq.value
        end as "FizzBuzz"
  FROM seq;


drop procedure if exists reSeqFizzBuzz;
delimiter //
create procedure reSeqFizzBuzz( IN fromValue INT, IN toValue INT, IN fizz INT, buzz INT)
begin
 with recursive seq AS (
  SELECT fromValue AS value UNION ALL SELECT value + 1 FROM seq WHERE value < toValue
  )
 SELECT value, 
         case when  (seq.value % fizz = 0) AND (seq.value % buzz = 0) then 'FIZZBUZZ' 
          when  (seq.value % fizz = 0) then 'FIZZ' 
          when  (seq.value % buzz = 0) then 'BUZZ' 
		  else seq.value
         end as `FizzBuzz(n,m)`
  FROM seq ;
  
end//
delimiter ;
use loginsystem;

call reSeqFizzBuzz(1,35,3,7);





