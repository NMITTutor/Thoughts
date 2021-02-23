// Loop free mirror in TypeScript
var mirror = function (pArray) {
    var count = 0;
    var reverse = function (ary) {
        var r = function (i, a) {
            return i == 0 ? [a[i]] :
                [a[i]].concat(r(i - 1, a));
        };
        return r(ary.length - 1, ary);
    };
    var firstN = function (n, ary) {
        var next = function (i, a) {
            return i == 0 ? [a[i]] :
                next(i - 1, a).concat([a[i]]);
        };
        return next(n, ary);
    };
    return firstN(pArray.length - 2, pArray).concat(reverse(pArray));
};
console.log(mirror([0, 1, 2, 3]));
