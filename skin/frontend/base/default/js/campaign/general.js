+function ($) {
    var version = $j.fn.jquery.split(' ')[0].split('.')
    if ((version[0] < 2 && version[1] < 9) || (version[0] == 1 && version[1] == 9 && version[2] < 1)) {
        throw new Error('JavaScript requires jQuery higher')
    }
}(jQuery);
var $j = jQuery.noConflict();
var Scpopup = function(){
    this.idPopup= "";
    this.templateCode= "";
    this.effect= "";
    this.borderSize= "";
    this.borderColor= "";
    this.paddingSize= "";
    this.width= "";
    this.bgColor= "";
    this.overlayColor= "";
    this.closeStyle= "";
    this.cornerRadius= "";
    this.horizontalPosition= "";
    this.verticalPosition= "";
    this.horizontalPx= "";
    this.verticalPx= "";
    this.secondDelay= "";
    this.showWhen= "";
    this.status= false;
    //this.Scpopup = function(){return this;};
    this.showPopup = function() {
        var idPopup = this.idPopup;
        var scdelay = this.secondDelay;
        console.log(idPopup + 'seconday' + scdelay);
        console.log("#sc-popup" + idPopup);
        if (this.showWhen == 'after_seconds') {
            if(scdelay != "") {
                var timedelay = 1000 * scdelay;
                setTimeout(function () {
                    $j("#sc-popup" + idPopup).modal('show');
                }, timedelay);
            }
        }
        if (this.showWhen == 'after_load_page') {
                $j("#sc-popup" + idPopup).modal('show');
        }
    };
    this.hidePopup= function () {
        $j("#sc-popup" + this.idPopup).modal('hide');
    };
    this.runEffect= function () {
        var effectPopup = this.effect;
        switch (effectPopup) {
        case 0:
            $j("#sc-popup" + this.idPopup).prop("class", "modal fade").addClass('top');
            break;
        case 1:
            $j("#sc-popup" + this.idPopup).prop("class", "modal fade").addClass('left');
            break;
        case 2:
            $j("#sc-popup" + this.idPopup).prop("class", "modal fade").addClass('right');
            break;
        case 3:
            $j("#sc-popup" + this.idPopup).prop("class", "modal fade").addClass('bottom');
            break;
        case 4:
            $j("#sc-popup" + this.idPopup).prop("class", "modal fade").addClass('top-left');
            break;
        case 5:
            $j("#sc-popup" + this.idPopup).prop("class", "modal fade").addClass('top-right');
            break;
        case 6:
            $j("#sc-popup" + this.idPopup).prop("class", "modal fade").addClass('bottom-left');
            break;
        case 7:
            $j("#sc-popup" + this.idPopup).prop("class", "modal fade").addClass('bottom-right');
            break;
        }
    };
    this.showPosition= function () {
        var hzposition = this.horizontalPosition;
        var vtposition = this.verticalPosition;
        var hzpx = this.horizontalPx;
        var vtpx = this.verticalPx;
        var tpcode = this.templateCode;
        var overlayColor = this.overlayColor;
        var widthcenter = this.width * 0.5;
        var cssHead, cssHz, cssVt;
        switch (hzposition) {
            case 'left':
                if (hzpx != "") {
                    if (overlayColor == 'no_bg_fix_popup' || overlayColor == 'no_bg_absoulute_popup') {
                        cssHz = ".sc-popup" + tpcode + " .modal{left:" + hzpx + "px}";
                    } else {
                        cssHz = ".sc-popup" + tpcode + " .modal-dialog{left:" + hzpx + "px}";
                    }
                }
                break;
            case 'center':
                if (overlayColor == 'no_bg_fix_popup' || overlayColor == 'no_bg_absoulute_popup') {
                    cssHz = ".sc-popup" + tpcode + " .modal{left:50%;margin-left:-" + widthcenter + "px}";
                } else {
                    cssHz = ".sc-popup" + tpcode + " .modal-dialog{left:50%;margin-left:-" + widthcenter + "px}";
                }
                break;
            case 'right':
                if (hzpx != "") {
                    if (overlayColor == 'no_bg_fix_popup' || overlayColor == 'no_bg_absoulute_popup') {
                        cssHz = ".sc-popup" + tpcode + " .modal{right:" + hzpx + "px}";
                    } else {
                        cssHz = ".sc-popup" + tpcode + " .modal-dialog{right:" + hzpx + "px}";
                    }
                }
                break;
        }
        switch (vtposition) {
            case 'top':
                if (vtpx != "") {
                    if (overlayColor == 'no_bg_fix_popup' || overlayColor == 'no_bg_absoulute_popup') {
                        cssVt = ".sc-popup" + tpcode + " .modal{top:" + vtpx + "px}";
                    } else {
                        cssVt = ".sc-popup" + tpcode + " .modal-dialog{top:" + vtpx + "px}";
                    }
                }
                break;
            case 'bottom':
                if (vtpx != "") {
                    if (overlayColor == 'no_bg_fix_popup' || overlayColor == 'no_bg_absoulute_popup') {
                        cssVt = ".sc-popup" + tpcode + " .modal{bottom:" + vtpx + "px}";
                    } else {
                        cssVt = ".sc-popup" + tpcode + " .modal-dialog{top:" + vtpx + "px}";
                    }
                }
                break;
        }
        cssHead = cssHz + cssVt;
        return cssHead;
    };

    this.showOverlayColor= function () {
        var tpcode = this.templateCode;
        var overlay = this.overlayColor;
        var widthPopup = this.width;
        var cssHead = "";
        switch (overlay) {
            case "white":
                cssHead = ".modal-open{overflow:auto}";
                cssHead = cssHead + ".sc-popup" + tpcode + " .modal{top:0;left:0;right:0;bottom:0;position:fixed;}";
                cssHead = cssHead + ".sc-popup" + tpcode + " .modal-backdrop{background-color:#fff}";
                cssHead = cssHead + ".sc-popup" + tpcode + " .modal-dialog{width:" + widthPopup + "px}";
                break;
            case "dark":
                cssHead = ".modal-open{overflow:auto}";
                cssHead = cssHead + ".sc-popup" + tpcode + " .modal{top:0;left:0;right:0;bottom:0;position:fixed;}";
                cssHead = cssHead + ".sc-popup" + tpcode + " .modal-backdrop{background-color:#000}";
                cssHead = cssHead + ".sc-popup" + tpcode + " .modal-dialog{width:" + widthPopup + "px}";
                break;
            case 'no_bg_fix_popup':
                cssHead = ".modal-open{overflow:auto}";
                cssHead = cssHead + ".sc-popup" + tpcode + " .modal{position:fixed}";
                cssHead = cssHead + ".sc-popup" + tpcode + " .modal-backdrop{display:none}";
                cssHead = cssHead + ".sc-popup" + tpcode + " .modal-dialog{margin:0;width:" + widthPopup + "px}";
                break;
            case 'no_bg_absoulute_popup':
                cssHead = ".modal-open{overflow:auto}";
                cssHead = cssHead + ".sc-popup" + tpcode + " .modal{position:absolute}";
                cssHead = cssHead + ".sc-popup" + tpcode + " .modal-backdrop{display:none}";
                cssHead = cssHead + ".sc-popup" + tpcode + " .modal-dialog{margin:0;width:" + widthPopup + "px}";
                break;
        }
        return cssHead;
    };
    this.showStylePopup= function () {
        var bColor = this.borderColor;
        var bSize = this.borderSize;
        var bRadius = this.cornerRadius;
        var padding = this.paddingSize;
        var width = this.width;
        var bgContentColor = this.bgColor;
        var tcode = this.templateCode;
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
                    cssBRadius = "border-radius:" + this.borderSize + "px;";
                    break;
                case 'sharp':
                    cssBRadius = "border-radius:0px;";
                    break;
                case 'circle':
                    cssBRadius = "border-radius:50%;overflow:hidden;";
                    break;
            }
        }
        else{
            cssBRadius = "";
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
        cssHead = ".sc-popup" + tcode + " .modal-content{" + cssBColor + cssBRadius + cssBSize + cssPadding + cssWidth + cssbgContentColor + "}";
        return cssHead;
    };
    this.showCloseIcon= function (_url) {
        var cssCloseIcon = "";
        switch (this.closeStyle) {
            case 'circle':
                if (this.overlayColor == 'white') {
                    cssCloseIcon = ".dialogClose{background:url(" + _url + "images/campaign/popup/close_dark.png) no-repeat 5px 5px}";
                } else {
                    if (this.overlayColor == 'dark') {
                        cssCloseIcon = ".dialogClose{background:url(" + _url + "images/campaign/popup/close_white.png) no-repeat 5px 5px}";
                    } else {
                        cssCloseIcon = ".dialogClose{background:url(" + _url + "images/campaign/popup/close_promotion.png) no-repeat 5px -20px;top:5px;right:5px;}";
                    }
                }
                break;
            case 'simple':
                cssCloseIcon = ".dialogClose{background:url(" + _url + "images/campaign/popup/close_promotion.png) no-repeat 5px -20px}";
                break;
            case 'none':
                cssCloseIcon = ".dialogClose{background:none transparent}";
                break;
            default:
                cssCloseIcon = ".dialogClose{background:url(" + _url + "images/campaign/popup/close_promotion.png) no-repeat 5px -20px;top:5px;right:5px;}";
        }
        return cssCloseIcon;
    };
    this.addCssToHead = function (_url) {
        var stringcss = "<style type=\"text/css\">" + this.showPosition() + this.showOverlayColor() + this.showStylePopup() + this.showCloseIcon(_url) + "</style>"
        $j('html > head').append(stringcss);
    }
};