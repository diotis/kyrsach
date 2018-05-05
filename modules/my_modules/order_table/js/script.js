(function ($, Drupal, settings) {
    Drupal.behaviors.del = {
        attach: function (context) {
            $(document).ready(function () {

                var id_changer = 0;

                $('input[id="del"]').click(function (event) {
                    var id = event.target.attributes['data'].nodeValue;
                    $.ajax({
                        type:'get',
                        url:'/del/'+id,
                        success: function(data){
                            location.reload(true);
                        }
                    });
                });
                $('input[id="edit"]').click(function (event) {
                    document.location.href = '/edit/'+(event.target.attributes['data'].nodeValue-id_changer);
                });
            });
        }
    }
})(jQuery, Drupal, drupalSettings);
