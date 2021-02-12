var a = "Hello, Nothing here yet";
console.log(a);
var FizzSelector;
(function (FizzSelector) {
    FizzSelector[FizzSelector["Value"] = 0] = "Value";
    FizzSelector[FizzSelector["FizzBuz"] = 1] = "FizzBuz";
})(FizzSelector || (FizzSelector = {}));
var FizzBuzz = /** @class */ (function () {
    function FizzBuzz(value, fizzbuzz) {
        var _this = this;
        this.showMe = function (which) {
            if (which === FizzSelector.Value) {
                console.log(_this.value);
            }
            else if (which === FizzSelector.FizzBuz) {
                console.log(_this.fizzbuzz);
            }
            else {
                console.log(_this.value, _this.fizzbuzz);
            }
        };
        this.value = value;
        this.fizzbuzz = fizzbuzz;
    }
    return FizzBuzz;
}());
var aFizzBuzz = new FizzBuzz(0, "0");
aFizzBuzz.showMe(FizzSelector.FizzBuz);
