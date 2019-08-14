jQuery(function(){
    var mw_pageReloadEnhancement = {
        mw_advnav_last : '',
        cache : {},
        containers : {
            main_content: '.col-main:first',
            list_product: '.category-products'
        },

        init : function (containers){
            var self = this;
            this.bind();
            if(containers)
                jQuery.extend(this.containers, containers);

            jQuery(window).bind("hashchange", function() {
                self.urlChange();
            });
        },
        bind : function(){
            var self = this;
            jQuery('.toolbar .pages li a, .view-mode a, .sorter a').unbind().bind('click', function(e){self.product_list(e, this); return false;});
            jQuery('.limiter select, .sorter select').removeAttr('onchange').unbind().bind('change', function(e){self.product_list(e, this); return false;});
        },
        product_list : function (e, elem){
            var target = jQuery(e.currentTarget);
            var url;
            if (target.is('a')) {
                url = jQuery(elem).attr('href');
            }else{
                url = jQuery(elem).val();
            }
            var query_params = jQuery.deparam.querystring(url);
            delete query_params.aj;
            query_params.aj = 'l';
            var new_url = location.href.substring(0, location.href.indexOf('#'));
            new_url = jQuery.param.fragment( new_url, query_params );
            location = new_url;
        },
        beforeRequest : function (){
            jQuery(this.containers.list_product)
                .prepend("<div class='mw-loading'></div>")
                .animate({ opacity : 0.5 },'slow')
            ;
        },
        success : function (data){
            var main_col = this.cache['content_main_col'];
            if(!main_col)
            {
                main_col = jQuery(this.containers.main_content);
                if(main_col.length > 0)
                {
                    var cate_pro_parents = jQuery(this.containers.list_product).parentsUntil(main_col);
                    if(cate_pro_parents.length > 0) {
                        main_col = jQuery(cate_pro_parents.get(-1));
                    }
                }
                this.cache['content_main_col'] = main_col;
            }
            /*var content_con = jQuery(this.containers.main_content);
            if(content_con.length > 0)
            {
                var cate_pro_parents = jQuery(this.containers.list_product).parentsUntil(content_con);
                if(cate_pro_parents.length > 0) {
                    content_con = jQuery(cate_pro_parents.get(-1));
                }
            }*/

            main_col.html(data);
            /*jQuery('html').animate({
                scrollTop : jQuery(this.containers.list_product).offset().top
            },'slow');*/
            this.bind();
        },
        urlChange : function(){
            if(this.mw_advnav_last == location.hash)
                return;

            var self = this;
            var fragments = jQuery.deparam.fragment();
            var url_org = location.href.substring(0, location.href.indexOf('?'));

            var url_request = false;
            if(fragments && fragments.aj == 'l')
            {
                url_request = jQuery.param.querystring(url_org, fragments);
            }else
            {
                var old_fragments = jQuery.deparam.fragment(this.mw_advnav_last);
                if(old_fragments && old_fragments.aj == 'l' && jQuery.isEmptyObject(fragments)){
                    fragments.aj = 'l';
                    url_request = jQuery.param.querystring(url_org, fragments);
                }
            }

            if(url_request)
            {
                jQuery.ajax({
                    url: url_request,
                    beforeSend: function() {self.beforeRequest();},
                    success: function(data) {self.success(data);},
                    dataType: 'html'
                });
            }
            this.mw_advnav_last = location.hash;
        }
    };

    mw_pageReloadEnhancement.init({
        main_content: '.col-main:first',
        list_product: '.category-products'
    });

    var init_state = jQuery.bbq.getState();
    if (init_state.length!=0)
        jQuery(window).trigger('hashchange');
});


