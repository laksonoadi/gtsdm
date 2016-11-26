/**
Element based validation
Since it support function queing, and it need to be executed before the real submit,
it must be placed after XHR rule

@author Gabri NS (gabri.ns@gmail.com; gn_shally@yahoo.com)
@version 0.0.1
@copyright 2009
*/

var gValidation20090108 =
{
   '.gvFloat' : function (Obj)
   {
      if (Obj.tagName.toLowerCase() != 'input') return;
      else Obj.oldValue = 'undefined';
      
      var pushOldFunction = function(Obj, func)
      {
         for (var j = 1; j > 0; j++) if (Obj[func+j] == undefined) break;
         Obj[func+j] = Obj[func];
         return func+j;
      }
      
      var formatNumber = function (num, mode)
      {
         var num, decimal_place = 2;
         var point = ',', pointRegex = /,/g;
         var thousand = '.', thousandRegex = /\./g;
         var multiplier = Math.pow(10, decimal_place);
         
         if (mode > 0)
         {
            num = num.toString().replace(thousandRegex, '');
            if (mode == 2) return num; // just delete thousand mark
            num = num.toString().replace(pointRegex, '.');
            if (mode == 1) return num; // go back to english format
         }
         
         num = parseFloat(num);
         if (isNaN(num)) num = 0;
         if (num.toString().indexOf('e') != -1) return 'Out of range';
         num = (Math.round(num * multiplier)) / multiplier;
         
         var str = num.toString().split('.');
         var reminder = str[1] ? str[1].substr(0, 2) : '';
         while (reminder.length < decimal_place) reminder += '0';
         var quotient = str[0];
         
         var x = quotient.length % 3;
         var newQuotient = quotient.substr(0, x);
         
         for (var i = x; i < quotient.length; i += 3)
            newQuotient += thousand + quotient.substr(i, 3);
         if (newQuotient.charAt(0) == thousand) newQuotient = newQuotient.substr(1);
         
         return newQuotient + point + reminder;
      }
      
      if (typeof Obj.onfocus == 'function') var onFocusOld = pushOldFunction(Obj, 'onfocus');
      else var onFocusOld = false;
      Obj.onfocus = function()
      {
         SelectionStart = this.value.length - 3;
         if (this.setSelectionRange) this.setSelectionRange(SelectionStart, SelectionStart);
         if (onFocusOld) return this[onFocusOld]();
      }
      
      if (typeof Obj.onblur == 'function') var onBlurOld = pushOldFunction(Obj, 'onblur');
      else var onBlurOld = false;
      Obj.onblur = function()
      {
         this.value = formatNumber(this.value, 3);
         if (onBlurOld) return this[onBlurOld]();
      }
      
      if (typeof Obj.onkeypress == 'function') var onKeyPressOld = pushOldFunction(Obj, 'onkeypress');
      else var onKeyPressOld = false;
      Obj.onkeypress = function()
      {
         if (this.oldValue == 'undefined') this.oldValue = this.value;
         if (onKeyPressOld) return this[onKeyPressOld]();
      }
      
      if (typeof Obj.onkeyup == 'function') var onKeyUpOld = pushOldFunction(Obj, 'onkeyup');
      else var onKeyUpOld = false;
      Obj.onkeyup = function()
      {
         if (this.setSelectionRange)
         {
            this.oldSelectionStart = this.value.length - this.selectionStart;
            if (this.oldSelectionStart < 3) this.oldSelectionStart = 3
         }
         
         oldValue = this.oldValue;
         this.oldValue = 'undefined';
         
         num = formatNumber(this.value, 1);
         if (num != parseFloat(num))
            this.value = oldValue;
         else if (this.oldSelectionStart != undefined)
            this.value = formatNumber(num);
         
         if (this.oldSelectionStart != undefined)
            SelectionStart = this.value.length - this.oldSelectionStart;
         if (this.setSelectionRange) this.setSelectionRange(SelectionStart, SelectionStart);
         
         if (onKeyUpOld) return this[onKeyUpOld]();
      }
      
      if (typeof Obj.form.onsubmit == 'function') var onSubmitOld = pushOldFunction(Obj.form, 'onsubmit');
      else var onSubmitOld = function () {};
      Obj.form.onsubmit = function ()
      {
         if (Obj.name) this[Obj.name].value = formatNumber(this[Obj.name].value, 1);
         if (onSubmitOld) return this[onSubmitOld]();
      }
      
      Obj.value = formatNumber(Obj.value);
      Obj.gvExcelLikeFormat = formatNumber;
   },
   '.gvExcelLike' : function (Obj)
   {
      if (Obj.tagName.toLowerCase() != 'input') return;
      else Obj.oldValue = 'undefined';
      
      var pushOldFunction = function(Obj, func)
      {
         for (var j = 1; j > 0; j++) if (Obj[func+j] == undefined) break;
         Obj[func+j] = Obj[func];
         return func+j;
      }
      
      var formatNumber = function (num, mode)
      {
         var num, decimal_place = 2;
         var point = ',', pointRegex = /,/g;
         var thousand = '.', thousandRegex = /\./g;
         var multiplier = Math.pow(10, decimal_place);
         
         if (mode > 0)
         {
            num = num.toString().replace(thousandRegex, '');
            if (mode == 2) return num; // just delete thousand mark
            num = num.toString().replace(pointRegex, '.');
            if (mode == 1) return num; // go back to english format
         }
         
         num = parseFloat(num);
         if (isNaN(num)) num = 0;
         if (num.toString().indexOf('e') != -1) return 'Out of range';
         num = (Math.round(num * multiplier)) / multiplier;
         
         var str = num.toString().split('.');
         var reminder = str[1] ? str[1].substr(0, 2) : '';
         while (reminder.length < decimal_place) reminder += '0';
         var quotient = str[0];
         
         var x = quotient.length % 3;
         var newQuotient = quotient.substr(0, x);
         
         for (var i = x; i < quotient.length; i += 3)
            newQuotient += thousand + quotient.substr(i, 3);
         if (newQuotient.charAt(0) == thousand) newQuotient = newQuotient.substr(1);
         
         return newQuotient + point + reminder;
      }
      
      if (typeof Obj.onfocus == 'function') var onFocusOld = pushOldFunction(Obj, 'onfocus');
      else var onFocusOld = false;
      Obj.onfocus = function()
      {
         this.value = formatNumber(this.value, 2);
         if (onFocusOld) return this[onFocusOld]();
      }
      
      if (typeof Obj.onblur == 'function') var onBlurOld = pushOldFunction(Obj, 'onblur');
      else var onBlurOld = false;
      Obj.onblur = function()
      {
         this.value = formatNumber(this.value, 3);
         if (onBlurOld) return this[onBlurOld]();
      }
      
      if (typeof Obj.onkeypress == 'function') var onKeyPressOld = pushOldFunction(Obj, 'onkeypress');
      else var onKeyPressOld = false;
      Obj.onkeypress = function()
      {
         if (this.oldValue == 'undefined') this.oldValue = this.value;
         if (onKeyPressOld) return this[onKeyPressOld]();
      }
      
      if (typeof Obj.onkeyup == 'function') var onKeyUpOld = pushOldFunction(Obj, 'onkeyup');
      else var onKeyUpOld = false;
      Obj.onkeyup = function()
      {
         oldValue = this.oldValue;
         this.oldValue = 'undefined';
         
         num = formatNumber(this.value, 1);
         if (num != parseFloat(num))
            this.value = oldValue;
         
         if (onKeyUpOld) return this[onKeyUpOld]();
      }
      
      if (typeof Obj.form.onsubmit == 'function') var onSubmitOld = pushOldFunction(Obj.form, 'onsubmit');
      else var onSubmitOld = function () {};
      Obj.form.onsubmit = function ()
      {
         if (Obj.name) this[Obj.name].value = formatNumber(this[Obj.name].value, 1);
         if (onSubmitOld) return this[onSubmitOld]();
      }
      
      Obj.value = formatNumber(Obj.value);
      Obj.gvExcelLikeFormat = formatNumber;
   }
}

Behaviour.register(gValidation20090108);