+function ($) {
    var version = $j.fn.jquery.split(' ')[0].split('.');
    if ((version[0] < 2 && version[1] < 9) || (version[0] == 1 && version[1] == 9 && version[2] < 1)) {
        throw new Error('JavaScript requires jQuery higher');
    }
}(jQuery);
var $j = jQuery.noConflict();
var Scpopup = function () {
    this.idPopup = "";
    this.campaign_id = "";
    this.effect = "";
    this.borderSize = "";
    this.borderColor = "";
    this.paddingSize = "";
    this.width = "";
    this.bgColor = "";
    this.overlayColor = "";
    this.closeStyle = "";
    this.cornerStyle = "";
    this.cornerRadius = "";
    this.horizontalPosition = "";
    this.verticalPosition = "";
    this.horizontalPx = "";
    this.verticalPx = "";
    this.secondDelay = "";
    this.showWhen = "";
    this.urlImages = "";
    this.status = false;
    this.priority = "";
    this.frequency = "";
    this.trigger_popup = "";
    this.cookiePopup = "";

    this.runEffect = function () {
        var effectPopup = this.effect;
        switch (effectPopup) {
            case '0':
                $j("#sc-popup" + this.idPopup).prop("class", "modal fade").addClass('top');
                break;
            case '1':
                $j("#sc-popup" + this.idPopup).prop("class", "modal fade").addClass('bottom');
                break;
            case '2':
                $j("#sc-popup" + this.idPopup).prop("class", "modal fade").addClass('left');
                break;
            case '3':
                $j("#sc-popup" + this.idPopup).prop("class", "modal fade").addClass('right');
                break;
            case '4':
                $j("#sc-popup" + this.idPopup).prop("class", "modal fade").addClass('top-left');
                break;
            case '5':
                $j("#sc-popup" + this.idPopup).prop("class", "modal fade").addClass('top-right');
                break;
            case '6':
                $j("#sc-popup" + this.idPopup).prop("class", "modal fade").addClass('bottom-left');
                break;
            case '7':
                $j("#sc-popup" + this.idPopup).prop("class", "modal fade").addClass('bottom-right');
                break;
        }
        return "";
    };
    this.showPosition = function () {
        var hzposition = this.horizontalPosition;
        var vtposition = this.verticalPosition;
        var hzpx = this.horizontalPx;
        var vtpx = this.verticalPx;
        var overlayColor = this.overlayColor;
        var widthcenter = this.width * 0.5;
        var height = $j("#sc-popup" + this.idPopup + " .modal-dialog").height();
        var cssHead, cssHz, cssVt;
        switch (hzposition) {
            case 'left':
                if (hzpx != "") {
                    if (overlayColor == 'no_bg_fix_popup' || overlayColor == 'no_bg_absoulute_popup') {
                        cssHz = "#sc-popup" + this.idPopup + "{left:" + hzpx + "px;width:" + this.width + "px;right:auto} ";
                        cssHz = cssHz + "#sc-popup" + this.idPopup + " .modal-dialog{position:relative} ";
                    } else {
                        cssHz = "#sc-popup" + this.idPopup + "{top:0;left:0;bottom:0;right:0} ";
                        cssHz = cssHz + "#sc-popup" + this.idPopup + " .modal-dialog{left:" + hzpx + "px} ";
                    }
                }
                break;
            case 'center':
                if (overlayColor == 'no_bg_fix_popup' || overlayColor == 'no_bg_absoulute_popup') {
                    cssHz = "#sc-popup" + this.idPopup + "{left:50%;margin-left:-" + widthcenter + "px}";
                    cssHz = cssHz + "#sc-popup" + this.idPopup + " .modal-dialog{position:relative} ";
                } else {
                    cssHz = "#sc-popup" + this.idPopup + "{top:0;left:0;bottom:0;right:0} ";
                    cssHz = cssHz + "#sc-popup" + this.idPopup + " .modal-dialog{left:50%;margin-left:-" + widthcenter + "px}";
                }
                break;
            case 'right':
                if (hzpx != "") {
                    if (overlayColor == 'no_bg_fix_popup' || overlayColor == 'no_bg_absoulute_popup') {
                        cssHz = "#sc-popup" + this.idPopup + "{right:" + hzpx + "px;width:" + this.width + "px;left:auto} ";
                        cssHz = cssHz + "#sc-popup" + this.idPopup + " .modal-dialog{position:relative} ";
                    } else {
                        cssHz = "#sc-popup" + this.idPopup + "{top:0;left:0;bottom:0;right:0}";
                        cssHz = cssHz + "#sc-popup" + this.idPopup + " .modal-dialog{right:" + hzpx + "px} ";
                    }
                }
                break;
        }
        switch (vtposition) {
            case 'top':
                if (vtpx != "") {
                    if (overlayColor == 'no_bg_fix_popup' || overlayColor == 'no_bg_absoulute_popup') {
                        cssVt = "#sc-popup" + this.idPopup + "{top:" + vtpx + "px;bottom:auto} ";
                        cssHz = cssHz + "#sc-popup" + this.idPopup + " .modal-dialog{position:relative} ";
                    } else {
                        cssVt = "#sc-popup" + this.idPopup + "{top:0;left:0;bottom:0;right:0} ";
                        cssVt = cssVt + "#sc-popup" + this.idPopup + " .modal-dialog{top:" + vtpx + "px;width:" + this.width + "px;} ";
                    }
                }
                break;
            case 'bottom':
                if (vtpx != "") {
                    if (overlayColor == 'no_bg_fix_popup' || overlayColor == 'no_bg_absoulute_popup') {
                        cssVt = "#sc-popup" + this.idPopup + "{bottom:" + vtpx + "px;width:" + this.width + "px;top:auto} ";
                        cssHz = cssHz + "#sc-popup" + this.idPopup + " .modal-dialog{position:relative} ";
                    } else {
                        cssVt = "#sc-popup" + this.idPopup + "{top:0;left:0;bottom:0;right:0}";
                        cssVt = cssVt + "#sc-popup" + this.idPopup + " .modal-dialog{top:" + vtpx + "px} ";
                    }
                }
                break;
        }
        cssHead = cssHz + cssVt;
        return cssHead;
    };

    this.showOverlayColor = function () {
        var overlay = this.overlayColor;
        var widthPopup = this.width;
        var cssHead = "";
        switch (overlay) {
            case "white":
                cssHead = ".modal-open{top:0;left:0;right:0;left:0}";
                cssHead = cssHead + "#sc-popup" + this.idPopup + "{top:0;left:0;right:0;bottom:0;position:fixed;}";
                cssHead = cssHead + "#sc-popup" + this.idPopup + " .modal-backdrop{background-color:#fff}";
                cssHead = cssHead + "#sc-popup" + this.idPopup + " .modal-dialog{width:" + widthPopup + "px}";
                break;
            case "dark":
                cssHead = ".modal-open{top:0;left:0;right:0;left:0}";
                cssHead = cssHead + "#sc-popup" + this.idPopup + "{top:0;left:0;right:0;bottom:0;position:fixed;}";
                cssHead = cssHead + "#sc-popup" + this.idPopup + " .modal-backdrop{background-color:#000}";
                cssHead = cssHead + "#sc-popup" + this.idPopup + " .modal-dialog{width:" + widthPopup + "px}";
                break;
            case 'no_bg_fix_popup':
                cssHead = ".modal-open{overflow:auto;padding-right:0}";
                cssHead = cssHead + "#sc-popup" + this.idPopup + "{position:fixed}";
                cssHead = cssHead + "#sc-popup" + this.idPopup + " .modal-backdrop{display:none}";
                cssHead = cssHead + "#sc-popup" + this.idPopup + " .modal-dialog{margin:0;width:" + widthPopup + "px}";
                break;
            case 'no_bg_absoulute_popup':
                cssHead = ".modal-open{overflow:auto;padding-right:0}";
                cssHead = cssHead + "#sc-popup" + this.idPopup + "{position:absolute}";
                cssHead = cssHead + "#sc-popup" + this.idPopup + " .modal-backdrop{display:none}";
                cssHead = cssHead + "#sc-popup" + this.idPopup + " .modal-dialog{margin:0;width:" + widthPopup + "px}";
                break;
        }
        return cssHead;
    };
    this.setFrequencyCookie = function(id){
        var _id = '';
        if(typeof id != 'undefined'){
            _id = id;
        }else{
            _id = this.idPopup;
        }
        var Params = new Object;
        Params['popup_showed_'+_id] = 1;
        Params['popup_id'] = _id;
        this.setCookie(Params);
    };
    this.setClosedCookie = function(id){
        var _id = '';
        if(typeof id != 'undefined'){
            _id = id;
        }else{
            _id = this.idPopup;
        }
        var Params = new Object;
        Params['popup_closed_'+_id] = 1;
        Params['popup_id'] = _id;
        this.setCookie(Params);
    };
    this.setCookie = function(params){
        $j.post(this.cookieUrl, params,function(response){
            console.log('Set cookie success!');
        });
    };
    this.getCookie = function(cname){
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1);
            if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
        }
        return "";
    };
    this.checkFrequencyCookie = function(){
        if(this.frequency =='only_once' && !this.getCookie('popup_frequency_showed_'+this.idPopup)){
            return true;
        }else if(this.frequency =='until_close' && !this.getCookie('popup_frequency_showed_'+this.idPopup)){
            return true;
        }else if(this.frequency =='every_time'){
            return true;
        }else if(this.frequency =='only_trigger'){
            return true;
        }
        return false;
    };
    this.showStylePopup = function () {
        var bColor = this.borderColor;
        var bSize = this.borderSize;
        var bRadius = this.cornerStyle;
        var padding = this.paddingSize;
        var width = this.width;
        var bgContentColor = this.bgColor;
        var cssHead, cssBColor, cssBRadius, cssBSize, cssPadding, cssWidth;
        if (bColor != "") {
            cssBColor = "border-color:#" + bColor + ";";
        }
        if (bSize != "") {
            cssBSize = "border-width:" + bSize + "px;";
        }
        if (bRadius != "") {
            switch (bRadius) {
                case 'rounded':
                    cssBRadius = "#sc-popup" + this.idPopup + " .modal-content{border-radius:" + this.cornerRadius + "px;}";
                    cssBRadius = cssBRadius + "#sc-popup" + this.idPopup + " .modal-content .content-popup{overflow:hidden;border-radius:"+this.cornerRadius + "px;}";
                    break;
                case 'sharp':
                    cssBRadius = "#sc-popup" + this.idPopup + " .modal-content{border-radius:0px;}";
                    break;
                case 'circle':
                    var padding_circle = (width/2) - Math.round(width/2.8);
                    cssBRadius = "#sc-popup" + this.idPopup + " .modal-content{border-radius:50%;}";
                    cssBRadius = cssBRadius + " #sc-popup" + this.idPopup + " .modal-content{height:" + width + "px;} #sc-popup" + this.idPopup + " .modal-content .content-popup{overflow:hidden;height:100%;width:100%;padding:"+padding_circle +"px}";
                    break;
                default:
                    cssBRadius = "#sc-popup" + this.idPopup + " .modal-content{border-radius:0px;}";
            }
        }
        if (padding != "") {
            cssPadding = "padding:" + padding + "px;";
        }
        if (width != "") {
            cssWidth = "width:" + width + "px;";
        }
        if (bgContentColor != "") {
            var cssbgContentColor = "background:#" + bgContentColor + ";";
        }
        cssHead = "#sc-popup" + this.idPopup + " .modal-content{" + cssBColor + cssBSize + cssPadding + cssWidth + cssbgContentColor + "}" + cssBRadius;
        return cssHead;
    };
    this.showCloseIcon = function () {
        var cssCloseIcon = "";
        switch (this.closeStyle) {
            case 'circle':
                if (this.overlayColor == 'white') {
                    cssCloseIcon = "#sc-popup" + this.idPopup + " .dialogClose{background:url(" + this.urlImages + "images/campaign/popup/close_dark.png) no-repeat 5px 5px}";
                } else if (this.overlayColor == 'dark') {
                    cssCloseIcon = "#sc-popup" + this.idPopup + " .dialogClose{background:url(" + this.urlImages + "images/campaign/popup/close_white.png) no-repeat 5px 5px}";
                } else {
                    cssCloseIcon = "#sc-popup" + this.idPopup + " .dialogClose{background:url(" + this.urlImages + "images/campaign/popup/close_dark.png) no-repeat 5px 0;top:5px;right:5px;}";
                }
                break;
            case 'simple':
                cssCloseIcon = "#sc-popup" + this.idPopup + " .dialogClose{background:url(" + this.urlImages + "images/campaign/popup/close_promotion.png) no-repeat 5px -20px}";
                break;
            case 'none':
                cssCloseIcon = "#sc-popup" + this.idPopup + " .dialogClose{background:none transparent}";
                break;
        }
        return cssCloseIcon;
    };
    this.addCssToHead = function () {
        var stringcss = "<style type=\"text/css\">" + this.showPosition() + this.showOverlayColor() + this.showStylePopup() + this.showCloseIcon(this.urlImages) + "</style>";
        $j('html > head').append(stringcss);
    };
    this.modalShow = function(id){
        $j("#sc-popup" + id).modal('show');
        //set cookie when showing
        this.setFrequencyCookie(id);
    };
    this.showPopup = function () {
        var _this = this;
        var idPopup = this.idPopup;
        var scdelay = this.secondDelay;
        if (this.showWhen == 'after_seconds') {
            if (scdelay != "") {
                var timedelay = 1000 * scdelay;
                setTimeout(function () {
                    _this.modalShow(idPopup);
                }, timedelay);
            }
        } else if (this.showWhen == 'after_load_page') {
            this.modalShow(idPopup);
        }
        this.initCloseBackground(); //init close event, only showed can catch dom
    };
    this.initTrigger = function () {
        var _this = this;
        if(this.trigger_popup != '' && this.trigger_popup != null){
            var trigger_idpopup = this.trigger_popup;
            $j('.target_popup'+trigger_idpopup).click(function(){
                _this.modalShow(trigger_idpopup);
            });
        }
    };
    this.initPopup = function () {
        this.addCssToHead();
        this.runEffect();
        this.initTrigger();
        this.initCloseButton();
        return this;
    };
    this.initCloseButton = function(){
        var _this = this;
        $j("#sc-popup" + this.idPopup+" .dialogClose").on('click', function(ev) {
            _this.closeButton();
            ev.preventDefault();
        });
    };
    this.initCloseBackground = function(){
        $j("#sc-popup" + this.idPopup+".modal-backdrop").on('click', function(ev) {
            _this.closeBackground();
            ev.preventDefault();
        });
    };
    this.close = function(){
        $j("#sc-popup" + this.idPopup).modal('hide');
        $j(document).trigger('on_popup_closed', [this.idPopup, this.campaign_id]); //event for callback with jQuery event
        if(typeof this.onClosed == 'function'){
            this.onClosed(this.idPopup, this.campaign_id); //event for call back with object
        }
    };
    this.closeButton = function(){
        this.close();
        this.setClosedCookie();
    };
    this.closeBackground = function(){
        this.close();
    };
    /* this.hidePopup = function () {
     $j("#sc-popup" + this.idPopup).modal('hide');
     if (typeof this.callBack == "function") {
     this.callBack(this.priority);
     }
     };*/
    /* this.onClose = function (_function) {
     this.callBack = _function;
     };*/
};