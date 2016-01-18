/**
 * Created by thinlt on 9/15/2015.
 */
//<![CDATA[
/*
option = {
    year: 0, //year yyyy
    month: 0, //month MM
    day: 0, //day dd
    hour: 0, //hour H
    mins: 0, //minutes mm
    secs: 0, //seconds ss
    tz: 0, //timezone
    type: 'short|medium|long|text',
}
*/
var Countdown = function(option, htmlId, end_time_call_back_func){
    this.opt = {year: 0, month: 0, day: 0, hour: 0, mins: 0, secs: 0, tz:0, type: 'medium'}; //opt = {tz, dow, day, month, hour}
    this.id = htmlId;
    this.times = 0;
    //days, hours, mins, secs, times //attributes

    /*this.isDomLoaded = 0;
    window.onload = function() { this.isDomLoaded = 1; }*/

    this.init = function(){
        this.setOpt(option);
        if(typeof Countdown.times == 'undefined'){
            this.times = this.getStartCount(this.opt.year, this.opt.month, this.opt.day, this.opt.hour, this.opt.mins, this.opt.secs, this.opt.tz);
        }
        return this;
    }

    //set an array option
    this.setOpt = function(options){
        if(typeof options == 'array'){
            var i = 0;
            options.forEach(function(v){
                this.opt[i] = v;
                i++;
            }, this);
        }else if(typeof options == 'object'){
            //for (var attrname in this.opt) { this.opt[attrname] = this.opt[attrname]; }
            for (var attrname in options) { this.opt[attrname] = options[attrname]; }
        }
        return this;
    }

    this.getStartCount = function(year,month,date,hour,mins,secs,tz) {
        var toDate = new Date(); //get date with no timezone (UTC)
        var fromDate = new Date();
        fromDate.setMinutes(fromDate.getMinutes() + fromDate.getTimezoneOffset()); //reset to UTC time
        toDate.setMinutes(toDate.getMinutes() + toDate.getTimezoneOffset()); //reset to UTC time
        fromDate.setMinutes(fromDate.getMinutes() + tz*60); //to new timezone
        toDate.setYear(year);
        if (month > 0) { toDate.setMonth(month - 1); }
        toDate.setDate(date);
        toDate.setHours(hour);
        toDate.setMinutes(mins);
        toDate.setSeconds(secs);
        var diffDate = new Date(0);
        diffDate.setMilliseconds(toDate - fromDate);
        return Math.floor(diffDate.valueOf()/1000);
    }

    this.isDomLoaded = function(){
        if(document.readyState === "complete"){
            return true;
        }else{
            return false;
        }
    }

    //checking and run
    this.checkLoaded = function(){
        if (document.getElementById && document.getElementById(this.id) != null){
            this.running();
        }else if (!this.isDomLoaded()){
            var obj = this;
            setTimeout(function(){ obj.checkLoaded() }, 100);
        }
        return this;
    }

    this.running = function(){
        var _times = 0;
        if(this.times > 0){
            this.times = this.times - 1;
            this.secs = this.times % 60;
            _times = (this.times - this.secs) / 60;
            this.mins = _times % 60;
            _times = (_times - this.mins) / 60;
            this.hours = _times % 24;
            this.days = (_times - this.hours) / 24;

            if (this.hours < 10) this.hours = '0' + this.hours;
            if (this.mins < 10) this.mins = '0' + this.mins;
            if (this.secs < 10) this.secs = '0' + this.secs;
            this.writeTime(); //out html
            var obj = this;
            setTimeout(function () { obj.running() }, 999);
        }else{
            //end time and run here
            var elements = document.getElementsByClassName('super-campaign-parent-'+this.id);
            for(var i=0; i<elements.length; i++){
                elements[i].parentNode.removeChild(elements[i]);
            }
            if(end_time_call_back_func){
                end_time_call_back_func();
            }
            //run finished
        }
    }

    this.format = function(days, hours, mins, secs){
        var html = '';
        var dayText = '', hoursText = '', minsText = '', secsText = '';
        if(days > 1){
            dayText = 'Days';
        }else{
            dayText = 'Day';
        }
        switch (this.opt.type){
            case 'long':
            case 'text':
                if(hours > 1){
                    hoursText = 'Hours';
                }else{
                    hoursText = 'Hour';
                }
                if(mins > 1){
                    minsText = 'Minutes';
                }else{
                    minsText = 'Minute';
                }
                if(secs > 1){
                    secsText = 'Seconds';
                }else{
                    secsText = 'Second';
                }
                html = '<span class="countdown-day"><span class="val">' + days + '</span><span class="unit"> ' + dayText + '</span></span>' + '<span class="dot">&nbsp;</span>';
                html += '<span class="countdown-hour"><span class="val">' + hours + '</span><span class="unit"> ' + hoursText + '</span></span>' + '<span class="dot"> : </span>';
                html += '<span class="countdown-min"><span class="val">' + mins + '</span><span class="unit"> ' + minsText + '</span></span>' + '<span class="dot"> : </span>';
                html += '<span class="countdown-sec"><span class="val">' + secs + '</span><span class="unit"> ' + secsText + '</span></span>';
                break;
            case 'medium':
                html = '<span class="countdown-day"><span class="val">' + days + '</span><span class="unit"> ' + 'd' + '</span></span>' + '<span class="dot">&nbsp;</span>';
                html += '<span class="countdown-hour"><span class="val">' + hours + '</span><span class="unit"> ' + 'h' + '</span></span>' + '<span class="dot"> : </span>';
                html += '<span class="countdown-min"><span class="val">' + mins + '</span><span class="unit"> ' + 'm' + '</span></span>' + '<span class="dot"> : </span>';
                html += '<span class="countdown-sec"><span class="val">' + secs + '</span><span class="unit"> ' + 's' + '</span></span>';
                break;
            case 'short':
                html = '<span class="countdown-day"><span class="val">' + days + '</span><span class="unit"> ' + dayText + '</span></span>' + '<span class="dot">&nbsp;</span>';
                html += '<span class="countdown-hour"><span class="val">' + hours + '</span></span>' + '<span class="dot"> : </span>';
                html += '<span class="countdown-min"><span class="val">' + mins + '</span></span>' + '<span class="dot"> : </span>';
                html += '<span class="countdown-sec"><span class="val">' + secs + '</span></span>';
                break;
        }
        return html;
    }

    this.writeTime = function(){
        document.getElementById(this.id).innerHTML = this.format(this.days, this.hours, this.mins, this.secs);
    }

    this.init(); //init object
    this.checkLoaded(); //checking and run
}

//]]>
