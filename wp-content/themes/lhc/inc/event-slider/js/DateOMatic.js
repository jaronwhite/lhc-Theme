/**
 * Date-O-Matic is a javascript Date utilities project.
 * @author Jaron White
 * @version 2.0.0
 */

(function dateOMatic() {
    const longMonth = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December"
    ];
    const shortMonth = [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec"
    ];
    const longWeekDay = [
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday"
    ];
    const shortWeekDay = [
        "Sun",
        "Mon",
        "Tue",
        "Wed",
        "Thu",
        "Fri",
        "Sat"
    ];

    Date.prototype.getQuarter = function () {
        let m = this.getMonth();
        if (m < 3) {
            return 0;
        } else if (m > 2 && m < 6) {
            return 1;
        } else if (m > 5 && m < 9) {
            return 2;
        }
        return 3;
    };

    Date.prototype.getEra = function () {
        if (this.getFullYear() > 0) {
            return "CE";
        }
        return "BCE";
    };

    function isLeapYear(year) {
        return year % 4 === 0 && (year % 100 !== 0 || year % 400 === 0);
    }

    function addLeadingZero(num) {
        if (num < 10 && num > -10) {
            num = "0" + num;
        }
        return num;
    }

    function firstWeekDayOfYear(year) {
        return new Date(year, 0, 1).getDay();
    }

    function firstWeekDayOfMonth(year, month) {
        return new Date(year, month, 1).getDay();
    }

    function daysInYear(year) {
        if (isLeapYear) {
            return 366;
        }
        return 365;
    }

    function daysInMonth(year, month) {
        month += 1;
        switch (month) {
            case 4:
            case 6:
            case 9:
            case 11:
                return 30;
                break;
            case 2:
                if (isLeapYear(year)) {
                    return 29;
                } else {
                    return 28;
                }
                break;
            default:
                return 31;
        }
    }

    /* format q */
    function formatQuarter(format, q) {
    }

    /* format M */
    function formatMonth(format, month) {
        switch (format) {
            case "M":
                return month + 1;
                break;
            case "MM":
                return addLeadingZero(month + 1);
                break;
            case "MMM":
                return shortMonth[month];
                break;
            case "MMMM":
                return longMonth[month];
        }
    }

    /* format d */
    /* reuse for day, minute, second */
    function formatDay(format, day) {
        let count = format.split("").length;
        if (count === 2) {
            return addLeadingZero(day);
        }
        return day;
    }

    /* format D */
    function formatWeekDay(format, weekDay) {
        if (format === "D") {
            return shortWeekDay[weekDay];
        } else if (format === "DD") {
            return longWeekDay[weekDay];
        }
    }

    /* format h 12hr H 24hr */
    function formatHour(format, hour) {
        switch (format) {
            case "h":
                if (hour > 12) {
                    hour -= 12;
                } else if (hour === 0) {
                    hour = 12;
                }
                break;
            case "hh":
                if (hour > 12) {
                    hour -= 12;
                } else if (hour === 0) {
                    hour = 12;
                }
                if (hour < 100) {
                    hour = addLeadingZero(hour);
                }
                break;
            case "HH":
                hour = addLeadingZero(hour);
                break;
        }
        return hour;
    }

    function formatMeridiem(format, hour) {
        let meridiem;
        hour < 12 || hour === 24 ? (meridiem = "a") : (meridiem = "p");
        if (format.length > 1) meridiem += "m";
        if (format === format.toUpperCase()) meridiem = meridiem.toUpperCase();
        return meridiem;
    }

    /* format y */
    function formatYear(format, year) {
        if (format === "yy") {
            return year.toString().substring(2, 4);
        } else if (format === "yyyy") {
            return year;
        }
    }

    function parseFormatString(format) {
        let parsedArray = [];
        let charSplit = format.split("");
        let formatPiece = "";
        let position = 0;
        let formatStrObj = {
            hasNoYear: true,
            hasNoMonth: true,
            parsedArray: []
        };

        for (let i in charSplit) {
            i = parseInt(i);
            let type = charSplit[i];
            let specialChar = "";
            let p = new RegExp("[ -/:@]");
            if (p.test(type)) {
                specialChar = type;
            }
            let trigger = (charSplit[i + 1] !== type) || (specialChar === type);

            formatPiece += type;

            if (trigger) {
                switch (type) {
                    case "y":
                        formatStrObj.hasNoYear = false;
                        formatStrObj.parsedArray.push(
                            {
                                type: 'year',
                                format: formatPiece,
                                pos: position
                            }
                        );
                        break;
                    case "q":
                        formatStrObj.parsedArray.push(
                            {
                                type: 'quarter',
                                format: formatPiece,
                                pos: position
                            }
                        );
                        break;
                    case "M":
                        formatStrObj.hasNoMonth = false;
                        formatStrObj.parsedArray.push(
                            {
                                type: 'month',
                                format: formatPiece,
                                pos: position
                            }
                        );
                        break;
                    case "d":
                        formatStrObj.parsedArray.push(
                            {
                                type: 'day',
                                format: formatPiece,
                                pos: position
                            }
                        );
                        break;
                    case "D":
                        formatStrObj.parsedArray.push(
                            {
                                type: 'weekDay',
                                format: formatPiece,
                                pos: position
                            }
                        );
                        break;
                    case "h":
                    case "H":
                        formatStrObj.parsedArray.push(
                            {
                                type: 'hour',
                                format: formatPiece,
                                pos: position
                            }
                        );
                        break;
                    case "m":
                        formatStrObj.parsedArray.push(
                            {
                                type: 'minute',
                                format: formatPiece,
                                pos: position
                            }
                        );
                        break;
                    case "s":
                        formatStrObj.parsedArray.push(
                            {
                                type: 'second',
                                format: formatPiece,
                                pos: position
                            }
                        );
                        break;
                    case "S":
                        formatStrObj.parsedArray.push(
                            {
                                type: 'millisecond',
                                format: formatPiece,
                                pos: position
                            }
                        );
                        break;
                    case "a":
                    case "A":
                        formatStrObj.parsedArray.push(
                            {
                                type: 'meridiem',
                                format: formatPiece,
                                pos: position
                            }
                        );
                        break;
                    case specialChar:
                        formatStrObj.parsedArray.push(
                            {
                                type: 'specialChar',
                                format: formatPiece,
                                pos: position
                            }
                        );
                        break;
                    default:
                        return undefined;
                }
                formatPiece = "";
                position++;
            }
        }
        return formatStrObj;
    }

    String.prototype.toDate = function (format) {
        let dateStr = this;

        function parseDateStr() {
            let dateObj = {
                year: 0,
                month: 0,
                day: 1,
                hour: 0,
                minute: 0,
                second: 0,
                millisecond: 0
            };
            let parsedFormatObj = parseFormatString(format);
            if (parsedFormatObj.hasNoYear || parsedFormatObj.hasNoMonth) {
                return false;
            }

            let parsedFormat = parsedFormatObj.parsedArray;
            for (let i in parsedFormat) {
                let frmt = parsedFormat[i].format;
                switch (parsedFormat[i].type) {
                    case "year":
                        dateObj.year = stripYear(frmt);
                        break;
                    case "quarter":
                        break;
                    case "month":
                        dateObj.month = stripMonth(frmt);
                        break;
                    case "day":
                        dateObj.day = stripDay(frmt);
                        break;
                    case "weekDay":
                        break;
                    case "era":
                        break;
                    case "hour":
                        break;
                    case "minute":
                        break;
                    case "second":
                        break;
                    case "meridiem":
                        break;
                    case "specialChar":
                        dateStr = dateStr.substr(1);
                        break;
                    default:
                }
            }
            return dateObj;
        }

        /**
         * Strips 2 or 4 digit year from dateStr and returns the year.
         * This function assumes future dates are desired and will return
         * all 2-digit years within current century.
         * @param format
         * @returns {number} year
         */
        function stripYear(format) {
            let len = 0, year = 1970;
            if (format === "yy") {
                len = 2;
                //Short year will always default to current century
                year = (new Date().getFullYear().toString().substr(0, 2)) + dateStr.substr(0, len);
            } else if (format === "yyyy") {
                len = 4;
                year = dateStr.substr(0, len);
            }
            dateStr = dateStr.substr(len);
            return year;
        }

        function stripMonth(format) {
            let len = 0;
            let month = 0;

            switch (format) {
                case "M":
                    len = 1;
                    month = parseInt(dateStr.substr(0, len)) - 1;
                    break;
                case "MM":
                    len = 2;
                    month = parseInt(dateStr.substr(0, len)) - 1;
                    break;
                case "MMM":
                    len = 3;
                    month = loopShortMonth(dateStr);
                    break;
                case "MMMM":
                    len = longMonth[month].length;
                    month = loopShortMonth(dateStr);
            }

            function loopShortMonth(dateStr) {
                for (let i in shortMonth) {
                    if (dateStr.substr(0, 3) == shortMonth[i]) {
                        return i;
                    }
                }
            }

            dateStr = dateStr.substr(len);
            return month;
        }

        function stripWeekDay(format) {
            let len = 0;
            let month = 0;

            switch (format) {
                case "D":
                    len = 3;
                    month = loopShortMonth(dateStr);
                    break;
                case "DD":
                    len = longMonth[month].length;
                    month = loopShortMonth(dateStr);
            }

            function loopShortMonth(dateStr) {
                for (let i in shortMonth) {
                    if (dateStr.substr(0, 3) == shortMonth[i]) {
                        return i;
                    }
                }
            }

            dateStr = dateStr.substr(len);
            return month;
        }

        function stripDay(format) {
            let len = format.split("").length;
            let day = 1;
            day = dateStr.substr(0, len);
            dateStr = dateStr.substr(len);
            return day;
        }

        let dateObj = parseDateStr();
        let date = new Date();
        for (let i in dateObj) {
            switch (i) {
                case "year" :
                    date.setFullYear(dateObj[i]);
                    break;
                case "month":
                    date.setMonth(dateObj[i]);
                    break;
                case "day":
                    date.setDate(dateObj[i]);
                    break;
                case "hour":
                    date.setHours(dateObj[i]);
                    break;
                case "minute":
                    date.setMinutes(dateObj[i]);
                    break;
                case "second":
                    date.setSeconds(dateObj[i]);
                    break;
                case "millisecond":
                    date.setMilliseconds(dateObj[i]);
                    break;

            }
        }

        return date;
    };

    Date.prototype.format = function (format) {
        let year = this.getFullYear();
        let quarter = this.getQuarter();
        let month = this.getMonth();
        let day = this.getDate();
        let weekDay = this.getDay();
        let hour = this.getHours();
        let minute = this.getMinutes();
        let second = this.getSeconds();
        let millisecond = this.getMilliseconds();

        let parsedFormat = parseFormatString(format).parsedArray;
        let formatted = "";

        for (let i in parsedFormat) {
            if (parsedFormat[i].pos != i) {
                break;
            }
            switch (parsedFormat[i].type) {
                case "year":
                    formatted += formatYear(parsedFormat[i].format, year);
                    break;
                case "quarter":
                    formatted += formatQuarter(parsedFormat[i].format, quarter);
                    break;
                case "month":
                    formatted += formatMonth(parsedFormat[i].format, month);
                    break;
                case "day":
                    formatted += formatDay(parsedFormat[i].format, day);
                    break;
                case "weekDay":
                    formatted += formatWeekDay(parsedFormat[i].format, weekDay);
                    break;
                case "hour":
                    formatted += formatHour(parsedFormat[i].format, hour);
                    break;
                case "minute":
                    formatted += formatDay(parsedFormat[i].format, minute);
                    break;
                case "second":
                    formatted += formatDay(parsedFormat[i].format, second);
                    break;
                case "millisecond":
                    formatted += formatDay(parsedFormat[i].format, millisecond);
                    break;
                case "meridiem":
                    formatted += formatMeridiem(parsedFormat[i].format, hour);
                    break;
                case "specialChar":
                    formatted += parsedFormat[i].format;
                    break;
                default:
            }
        }

        return formatted;
    };
})();