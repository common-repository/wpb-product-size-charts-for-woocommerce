/**
 * Size Table Generator
 */

var tableApp = new Vue({
  el: '#mdc_cmb_fieldset__wpb_psc_size_table',
  data: {
    tableJSON: '',
    tableNewJSON: '',
    table: {
      thead: [
        '',
      ],
      tbody: [
        [''],
      ]
    } 
  },
  mounted: function() {

    var _this = this;
    _this.updateTableJSON();    
    
    jQuery('#mdc_cmb_fieldset__wpb_psc_size_table').on('input', '[type="text"]', function() {
      _this.updateTableJSON();
    });

    jQuery(window).on('load', function() {
      jQuery('#mdc_cmb_fieldset__wpb_psc_size_table table').removeClass('wpb-psc-table-loading')
    });


    if( wpbPscData.post_id ){

      fetch(wpbPscData.rest_url + '/wp/v2/wpb_psc_size_chart/' + wpbPscData.post_id)

        .then( (response) => response.json() )

        .then( (json) => ( json._wpb_psc_size_table ) )
      
        .then( (finaldata) => this.table = finaldata )

        .then( (finaldata) => this.tableNewJSON = JSON.stringify(finaldata) );
    }else{
      this.tableNewJSON = this.tableJSON;
    }

  },
  methods: {
    updateTableJSON: function() {
      this.tableJSON = JSON.stringify(this.table);
      this.tableNewJSON = JSON.stringify(this.table);
    },

    initProAlert: function(){
      Swal.fire({
          html: '<h3>You Have Reached Your Maximum Limit</h3><p>The lite version of the plugin allows you to add 3 columns and 5 rows. If you like the plugin features and found helpful, please upgrade to the pro version. It will help us a lot.</p><a href="https://wpbean.com/?p=32752" target="_blank" class="swal2-confirm swal2-styled">Get The Pro</a>',
          showConfirmButton: false,
          showCloseButton: true,
          customClass: {
              container: 'wpb-psc-pro-alert-popup',
          },
      });
    },
    
    addColumn: function( index ) {
      //this.table.thead.push('Heading ' + (this.table.thead.length + 1));

      var thead_length = this.table.thead.length;

      if(thead_length < 3){
        this.table.thead.splice( index + 1, 0, '' );
      
        for(var i = 0, length = this.table.tbody.length; i < length; i++) {
          this.table.tbody[i].push('');
        }
        
        this.updateTableJSON();
      }else{
        this.initProAlert();
      }
    },
    removeColumn: function( index ) {


      if (this.table.thead.length != 1) {
        this.table.thead.splice( index, 1 )

        for(var i = 0, length = this.table.tbody.length; i < length; i++) {
          this.table.tbody[i].splice(index, 1);
        }
      }

      this.updateTableJSON();
    },
    
    addRow: function( index ) {
      var newRow = [];

      var tbody_length = this.table.tbody.length;

      if(tbody_length < 5){
      
        for(var i = 0, length = this.table.thead.length; i < length; i++) {
          newRow.push('')
        }
        
        this.table.tbody.splice( index + 1, 0, newRow);
        
        this.updateTableJSON();
      }else{
        this.initProAlert();
      }
    },

    removeRow: function( index ) {
      if (this.table.tbody.length != 1) {
        this.table.tbody.splice(index, 1);
      }
      
      this.updateTableJSON();
    }
  }
});




(function( $ ) {

  // Select2 search
  $(document).ready(function() {
      $(".wpb-psc-meta-field-select2").each(function() {
        var id = $(this).data("type");
      $(this).select2({
          ajax: {
              url: ajaxurl, // AJAX URL is predefined in WordPress admin
              dataType: 'json',
              delay: 250, // delay in ms while typing when to perform a AJAX search
              data: function (params) {
                  return {
                    q: params.term, // search query
                    id: id,
                    action: 'psc_get_' + id // AJAX action for admin-ajax.php
                  };
              },
              processResults: function( data ) {
            var options = [];
            if ( data ) {
     
              // data is the array of arrays, and each of them contains ID and the Label of the option
              $.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
                options.push( { id: text[0], text: text[1]  } );
              });
     
            }
            return {
              results: options
            };
          },
          cache: true
        },
        minimumInputLength: 3 // the minimum of symbols to input before perform a search
      });
    });
  });

  $('#mdc_cmb_fieldset__wpb_psc_products, #mdc_cmb_fieldset__wpb_psc__product_categories').dependsOn({
    '#mdc_cmb_fieldset__wpb_psc_set_for_all_products input[type="checkbox"]': {
      checked: false
    }
  });

})( jQuery );