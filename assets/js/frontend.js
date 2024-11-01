+(function($) {
    var WPB_Woo_Product_size_Chart = {

        initialize: function() {
            $('.wpb-psc-table-fire').on('click', this.FireSizeChart);
        },

        FireSizeChart: function(e) {
            e.preventDefault();

            var button  = $(this),
            id          = button.attr('data-id'),
            post_id     = button.attr('data-post_id'),
            popup_style = button.attr('data-popup_style') ? !0 : !1,
            width       = button.attr('data-width');

            wp.ajax.send( {
                data: {
                    action: 'fire_wpb_product_size_chart',
                    size_id: id,
                    wpb_post_id: post_id,
                    _wpnonce: WPB_PSC_Vars.nonce
                },
                beforeSend : function ( xhr ) {
					button.addClass('wpb-psc-btn-loading');
				},
                success: function( res ) {
                    button.removeClass('wpb-psc-btn-loading');
                    Swal.fire({
                        html: res,
                        showConfirmButton: false,
                        customClass: {
                            container: 'wpb-psc-size-chart-popup wpb-psc-table-style-' + popup_style,
                        },
                        padding: '30px',
                        width: width,
                        showCloseButton: true,
                    });
                },
                error: function(error) {
                    alert( error );
                }
            });
        },


    };

    $(function() {
        WPB_Woo_Product_size_Chart.initialize();
    });
})(jQuery);