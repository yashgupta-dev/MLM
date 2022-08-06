!function(s){if(!s.wp.wpColorPicker.prototype._hasAlpha){var p="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAAHnlligAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAHJJREFUeNpi+P///4EDBxiAGMgCCCAGFB5AADGCRBgYDh48CCRZIJS9vT2QBAggFBkmBiSAogxFBiCAoHogAKIKAlBUYTELAiAmEtABEECk20G6BOmuIl0CIMBQ/IEMkO0myiSSraaaBhZcbkUOs0HuBwDplz5uFJ3Z4gAAAABJRU5ErkJggg==",r='<div class="wp-picker-holder" />',e='<div class="wp-picker-container" />',a='<input type="button" class="button button-small" />',i=void 0!==wpColorPickerL10n.current;if(i)var n='<a tabindex="0" class="wp-color-result" />';else{n='<button type="button" class="button wp-color-result" aria-expanded="false"><span class="wp-color-result-text"></span></button>';var l="<label></label>",c='<span class="screen-reader-text"></span>'}Color.fn.toString=function(){if(this._alpha<1)return this.toCSS("rgba",this._alpha).replace(/\s+/g,"");var t=parseInt(this._color,10).toString(16);return this.error?"":(t.length<6&&(t=("00000"+t).substr(-6)),"#"+t)},s.widget("wp.wpColorPicker",s.wp.wpColorPicker,{_hasAlpha:!0,_create:function(){if(s.support.iris){var o=this,t=o.element;if(s.extend(o.options,t.data()),"hue"===o.options.type)return o._createHueOnly();o.close=s.proxy(o.close,o),o.initialValue=t.val(),t.addClass("wp-color-picker"),i?(t.hide().wrap(e),o.wrap=t.parent(),o.toggler=s(n).insertBefore(t).css({backgroundColor:o.initialValue}).attr("title",wpColorPickerL10n.pick).attr("data-current",wpColorPickerL10n.current),o.pickerContainer=s(r).insertAfter(t),o.button=s(a).addClass("hidden")):(t.parent("label").length||(t.wrap(l),o.wrappingLabelText=s(c).insertBefore(t).text(wpColorPickerL10n.defaultLabel)),o.wrappingLabel=t.parent(),o.wrappingLabel.wrap(e),o.wrap=o.wrappingLabel.parent(),o.toggler=s(n).insertBefore(o.wrappingLabel).css({backgroundColor:o.initialValue}),o.toggler.find(".wp-color-result-text").text(wpColorPickerL10n.pick),o.pickerContainer=s(r).insertAfter(o.wrappingLabel),o.button=s(a)),o.options.defaultColor?(o.button.addClass("wp-picker-default").val(wpColorPickerL10n.defaultString),i||o.button.attr("aria-label",wpColorPickerL10n.defaultAriaLabel)):(o.button.addClass("wp-picker-clear").val(wpColorPickerL10n.clear),i||o.button.attr("aria-label",wpColorPickerL10n.clearAriaLabel)),i?t.wrap('<span class="wp-picker-input-wrap" />').after(o.button):(o.wrappingLabel.wrap('<span class="wp-picker-input-wrap hidden" />').after(o.button),o.inputWrapper=t.closest(".wp-picker-input-wrap")),t.iris({target:o.pickerContainer,hide:o.options.hide,width:o.options.width,mode:o.options.mode,palettes:o.options.palettes,change:function(t,r){o.options.alpha?(o.toggler.css({"background-image":"url("+p+")"}),i?o.toggler.html('<span class="color-alpha" />'):(o.toggler.css({position:"relative"}),0==o.toggler.find("span.color-alpha").length&&o.toggler.append('<span class="color-alpha" />')),o.toggler.find("span.color-alpha").css({width:"30px",height:"28px",position:"absolute",top:0,left:0,"border-top-left-radius":"2px","border-bottom-left-radius":"2px",background:r.color.toString()})):o.toggler.css({backgroundColor:r.color.toString()}),s.isFunction(o.options.change)&&o.options.change.call(this,t,r)}}),t.val(o.initialValue),o._addListeners(),o.options.hide||o.toggler.click()}},_addListeners:function(){var r=this;r.wrap.on("click.wpcolorpicker",function(t){t.stopPropagation()}),r.toggler.click(function(){r.toggler.hasClass("wp-picker-open")?r.close():r.open()}),r.element.on("change",function(t){""!==s(this).val()&&!r.element.hasClass("iris-error")||(r.options.alpha?(i&&r.toggler.removeAttr("style"),r.toggler.find("span.color-alpha").css("backgroundColor","")):r.toggler.css("backgroundColor",""),s.isFunction(r.options.clear)&&r.options.clear.call(this,t))}),r.button.on("click",function(t){s(this).hasClass("wp-picker-clear")?(r.element.val(""),r.options.alpha?(i&&r.toggler.removeAttr("style"),r.toggler.find("span.color-alpha").css("backgroundColor","")):r.toggler.css("backgroundColor",""),s.isFunction(r.options.clear)&&r.options.clear.call(this,t),r.element.trigger("change")):s(this).hasClass("wp-picker-default")&&r.element.val(r.options.defaultColor).change()})}}),s.widget("a8c.iris",s.a8c.iris,{_create:function(){if(this._super(),this.options.alpha=this.element.data("alpha")||!1,this.element.is(":input")||(this.options.alpha=!1),void 0!==this.options.alpha&&this.options.alpha){var o=this,t=o.element,r=s('<div class="iris-strip iris-slider iris-alpha-slider"><div class="iris-slider-offset iris-slider-offset-alpha"></div></div>').appendTo(o.picker.find(".iris-picker-inner")),e=r.find(".iris-slider-offset-alpha"),a={aContainer:r,aSlider:e};void 0!==t.data("custom-width")?o.options.customWidth=parseInt(t.data("custom-width"))||0:o.options.customWidth=100,o.options.defaultWidth=t.width(),(o._color._alpha<1||-1!=o._color.toString().indexOf("rgb"))&&t.width(parseInt(88)),s.each(a,function(t,r){o.controls[t]=r}),o.controls.square.css({"margin-right":"0"});var i=o.picker.width()-o.controls.square.width()-20,n=i/6,l=i/2-n;s.each(["aContainer","strip"],function(t,r){o.controls[r].width(l).css({"margin-left":n+"px"})}),o._initControls(),o._change()}},_initControls:function(){if(this._super(),this.options.alpha){var o=this;o.controls.aSlider.slider({orientation:"vertical",min:0,max:100,step:1,value:parseInt(100*o._color._alpha),slide:function(t,r){o._color._alpha=parseFloat(r.value/100),o._change.apply(o,arguments)}})}},_change:function(){this._super();var t=this,r=t.element;if(this.options.alpha){var o=t.controls,e=parseInt(100*t._color._alpha),a=t._color.toRgb(),i=["rgb("+a.r+","+a.g+","+a.b+") 0%","rgba("+a.r+","+a.g+","+a.b+", 0) 100%"],n=t.options.defaultWidth,l=(t.options.customWidth,t.picker.closest(".wp-picker-container").find(".wp-color-result"));o.aContainer.css({background:"linear-gradient(to bottom, "+i.join(", ")+"), url("+p+")"}),l.hasClass("wp-picker-open")&&(o.aSlider.slider("value",e),t._color._alpha<1?(o.strip.attr("style",o.strip.attr("style").replace(/rgba\(([0-9]+,)(\s+)?([0-9]+,)(\s+)?([0-9]+)(,(\s+)?[0-9\.]+)\)/g,"rgb($1$3$5)")),r.width(parseInt(88))):r.width(n))}!r.data("reset-alpha")&&1||t.picker.find(".iris-palette-container").on("click.palette",".iris-palette",function(){t._color._alpha=1,t.active="external",t._change()}),r.trigger("change")},_addInputListeners:function(e){function t(t){var r=new Color(e.val()),o=e.val();e.removeClass("iris-error"),r.error?""!==o&&e.addClass("iris-error"):r.toString()!==a._color.toString()&&("keyup"===t.type&&o.match(/^[0-9a-fA-F]{3}$/)||a._setOption("color",r.toString()))}var a=this;e.on("change",t).on("keyup",a._debounce(t,100)),a.options.hide&&e.on("focus",function(){a.show()})}})}}(jQuery),jQuery(document).ready(function(t){t(".color-picker").wpColorPicker()});