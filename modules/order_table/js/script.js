(function ($, Drupal, settings) {
    Drupal.behaviors.del = {
        attach: function (context) {
            $(document).ready(function () {
                $('input[id="del"]').click(function (event) {
                    var id = event.target.attributes['data'].nodeValue;
                    $.ajax({
                        type:'get',
                        url:'/del/'+id,
                        success: function(data){
                            var table = document.getElementById('table');
                            table.deleteRow(data+1);
                            console.log('delete: '+data);

                        }
                    });
                });
                $('input[id="edit"]').click(function (event) {
                    document.location.href = '/edit/'+event.target.attributes['data'].nodeValue;
                });
            });
        }
    }
})(jQuery, Drupal, drupalSettings);
