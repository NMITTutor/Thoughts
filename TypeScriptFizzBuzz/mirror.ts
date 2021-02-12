
// Loop free mirror in TypeScript

const mirror = <T>(pArray: T[]) : T[] =>
{
    
    let count: number = 0;

    const reverse = (ary : T[]) : T[] =>
    {
        const r = (i :number, a:T[]):T[] => {
            return i == 0 ? [a[i]] :
            [a[i]].concat(r(i-1,a))
        }
        return r(ary.length -1 , ary);
    }
    
    const firstN = (n :number, ary : T[]) : T[] =>
    {
        const next = (i :number, a:T[]):T[] => {
            return i == 0 ? [a[i]] :
                   next(i-1,a).concat([a[i]])
        }

        return next(n,ary)
    }
    return firstN(pArray.length - 2,pArray).concat(
           reverse(pArray)
    );    
}

console.log(mirror<number>([0,1,2,3]));

