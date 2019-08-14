mwPopup = {
    show:function () {
        $('message-popup-window-mask').setStyle({'height':$('html-body').getHeight() + 'px'});
        Element.show('message-popup-window-mask');
        Effect.Appear('mw_popup', {duration:1.2});
    },
    closePopup:function () {
        Effect.Fade('mw_popup', {duration:0.75});
        Element.hide('message-popup-window-mask');
    },
    scPopup:function () {
        $('save-button').innerHTML = '<span>Saving ...</span>';
        $('save-button').addClassName('disabled');
        $('mw_notice').request({
            onComplete:function () {
                Effect.Fade('mw_popup', {duration:0.75});
                Element.hide('message-popup-window-mask');
            }
        })
    },
    selectAll:function(val) {
        $$('#update_types li').each(function(el){el.firstChild.checked=val});return false;
    }
}
