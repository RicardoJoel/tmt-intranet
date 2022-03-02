/*
Copyright (c) 2012-2017 Open Lab
Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

function dateToRelative(localTime){
  var diff=new Date().getTime()-localTime;
  var ret="";

  var min=60000;
  var hour=3600000;
  var day=86400000;
  var wee=604800000;
  var mon=2629800000;
  var yea=31557600000;

  if (diff<-yea*2)
    ret ="en ## años".replace("##",(-diff/yea).toFixed(0));

  else if (diff<-mon*9)
    ret ="en ## meses".replace("##",(-diff/mon).toFixed(0));

  else if (diff<-wee*5)
    ret ="en ## semanas".replace("##",(-diff/wee).toFixed(0));

  else if (diff<-day*2)
    ret ="en ## días".replace("##",(-diff/day).toFixed(0));

  else if (diff<-hour)
    ret ="en ## horas".replace("##",(-diff/hour).toFixed(0));

  else if (diff<-min*35)
    ret ="en aproximadamente una hora";

  else if (diff<-min*25)
    ret ="en aproximadamente media hora";

  else if (diff<-min*10)
    ret ="en algunos minutos";

  else if (diff<-min*2)
    ret ="en pocos minutos";

  else if (diff<=min)
    ret ="ahora";

  else if (diff<=min*5)
    ret ="hace pocos minutos";

  else if (diff<=min*15)
    ret ="hace algunos minutos";

  else if (diff<=min*35)
    ret ="hace aproximadamente media hora";

  else if (diff<=min*75)
    ret ="hace aproximadamente una hora";

  else if (diff<=hour*5)
    ret ="hace pocas horas";

  else if (diff<=hour*24)
    ret ="hace ## horas".replace("##",(diff/hour).toFixed(0));

  else if (diff<=day*7)
    ret ="hace ## días".replace("##",(diff/day).toFixed(0));

  else if (diff<=wee*5)
    ret ="hace ## semanas".replace("##",(diff/wee).toFixed(0));

  else if (diff<=mon*12)
    ret ="hace ## meses".replace("##",(diff/mon).toFixed(0));

  else
    ret ="hace ## años".replace("##",(diff/yea).toFixed(0));

  return ret;
}

//override date format i18n

Date.monthNames = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Setiembre","Octubre","Noviembre","Diciembre"];
// Month abbreviations. Change this for local month names
Date.monthAbbreviations = ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Set","Oct","Nov","Dic"];
// Full day names. Change this for local month names
Date.dayNames =["Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado"];
// Day abbreviations. Change this for local month names
Date.dayAbbreviations = ["Dom","Lun","Mar","Mié","Jue","Vie","Sáb"];
// Used for parsing ambiguous dates like 1/2/2000 - default to preferring 'American' format meaning Jan 2.
// Set to false to prefer 'European' format meaning Feb 1
Date.preferAmericanFormat = false;

Date.firstDayOfWeek =0;
Date.defaultFormat = "dd/MM/yyyy";
Date.masks = {
  fullDate:       "EEEE, dMMMM d, yyyy",
  shortTime:      "HH:mm a"
};
Date.today="Hoy";

Number.decimalSeparator = ".";
Number.groupingSeparator = ",";
Number.minusSign = "-";
Number.currencyFormat = "###,##0.00";

var millisInWorkingDay = 28800000;
var workingDaysPerWeek = 5;

function isHoliday(date) {
  var friIsHoly =false;
  var satIsHoly =true;
  var sunIsHoly =true;

  var pad = function (val) {
    val = "0" + val;
    return val.substr(val.length - 2);
  };

  var holidays = "##";

  var ymd = "#" + date.getFullYear() + "_" + pad(date.getMonth() + 1) + "_" + pad(date.getDate()) + "#";
  var md = "#" + pad(date.getMonth() + 1) + "_" + pad(date.getDate()) + "#";
  var day = date.getDay();

  return  (day == 5 && friIsHoly) || (day == 6 && satIsHoly) || (day == 0 && sunIsHoly) || holidays.indexOf(ymd) > -1 || holidays.indexOf(md) > -1;
}

var i18n = {
  YES:"Sí",
  NO:"No",
  FLD_CONFIRM_DELETE:"confirm the deletion?",
  INVALID_DATA:"The data inserted are invalid for the field format.",
  ERROR_ON_FIELD:"Error on field",
  OUT_OF_BOUDARIES:"Out of field admitted values:",
  CLOSE_ALL_CONTAINERS:"¿Realmente deseas cerrar todo?",
  DO_YOU_CONFIRM:"Do you confirm?",
  ERR_FIELD_MAX_SIZE_EXCEEDED:"El tamaño del campo excede el máximo permitido.",
  WEEK_SHORT:"W.",

  FILE_TYPE_NOT_ALLOWED:"File type not allowed.",
  FILE_UPLOAD_COMPLETED:"File upload completed.",
  UPLOAD_MAX_SIZE_EXCEEDED:"Max file size exceeded",
  ERROR_UPLOADING:"Error uploading",
  UPLOAD_ABORTED:"Upload aborted",
  DROP_HERE:"Drop files here",

  FORM_IS_CHANGED:"De continuar se perderán algunos datos del formulario.",

  PIN_THIS_MENU: "PIN_THIS_MENU",
  UNPIN_THIS_MENU: "UNPIN_THIS_MENU",
  OPEN_THIS_MENU: "OPEN_THIS_MENU",
  CLOSE_THIS_MENU: "CLOSE_THIS_MENU",
  PROCEED: "Proceed?",

  PREV: "Previous",
  NEXT: "Next",
  HINT_SKIP: "Got it, close this hint.",

  WANT_TO_SAVE_FILTER: "save this filter",
  NEW_FILTER_NAME: "name of the new filter",
  SAVE: "Guargar",
  DELETE: "Eliminar",
  HINT_SKIP: "Got it, close this hint.",

  COMBO_NO_VALUES: "no values available...?",

  FILTER_UPDATED:"Filter updated.",
  FILTER_SAVED:"Filter correctly saved."
};