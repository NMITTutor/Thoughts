var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (Object.prototype.hasOwnProperty.call(b, p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
var a = "Hello, Nothing here yet";
console.log(a);
var FizzSelector;
(function (FizzSelector) {
    FizzSelector[FizzSelector["Value"] = 0] = "Value";
    FizzSelector[FizzSelector["FizzBuz"] = 1] = "FizzBuz";
    FizzSelector[FizzSelector["Both"] = 2] = "Both";
})(FizzSelector || (FizzSelector = {}));
var FizzBuzz = /** @class */ (function () {
    function FizzBuzz(value) {
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
        if ((value % 3 == 0) && (value % 5 == 0))
            this.fizzbuzz = "fizzbuzz";
        else if (value % 3 == 0)
            this.fizzbuzz = "fizz";
        else if (value % 5 == 0)
            this.fizzbuzz = "buzz";
        else
            this.fizzbuzz = value.toString();
    }
    return FizzBuzz;
}());
var Seq = /** @class */ (function (_super) {
    __extends(Seq, _super);
    function Seq(pFrom, pTo, newValue) {
        var _this = _super.call(this) || this;
        _this.next = function (i, pTo, newValue) {
            if (i <= pTo) {
                this[i] = newValue(i);
                console.log(this[i]);
                this.next(i + 1, pTo, newValue);
            }
        };
        _this.start = pFrom;
        _this.end = pTo;
        console.log(pFrom, "  ", pTo);
        _this.next(pFrom, pTo, newValue);
        return _this;
    }
    return Seq;
}(Array));
// Some tests
var aFizzBuzz = new FizzBuzz(1);
aFizzBuzz.showMe(FizzSelector.Both);
var bFizzBuzz = new FizzBuzz(3);
bFizzBuzz.showMe(FizzSelector.Both);
var cFizzBuzz = new FizzBuzz(5);
cFizzBuzz.showMe(FizzSelector.Both);
var dFizzBuzz = new FizzBuzz(15);
dFizzBuzz.showMe(FizzSelector.Both);
function FizzBuzzMaker(i) {
    return new FizzBuzz(i);
}
var someFizzBuzzes = new Seq(1, 15, FizzBuzzMaker);
console.log(someFizzBuzzes);
