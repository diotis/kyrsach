(function ($, Drupal, settings) {
    Drupal.behaviors.user_contracts = {
        attach: function (context) {
            $(document).ready(function () {

                console.log('user');
                // $('tr').bind('click', function() {
                //     var action = document.getElementById('actions');
                //     var id = 0;
                //     if (action.hasAttribute('data')) {
                //         id = action.getAttribute('data');
                //     }
                //     if (event.target.tagName != "SELECT") {
                //         go(id);
                //         console.log('contract: '+id);
                //     }
                // });
                function go(id) {
                    window.open("contract/"+id);
                }



                $("select").change(function(){
                    var tr = $(this).parent().parent().parent();
                    var id = this.getAttribute('data');
                    var value = $(this).children(":selected")[0].getAttribute('value');
                    console.log(value);
                    $.ajax({
                        type: 'get',
                        url: "/user/proposed/" + value + '/' + id,
                        success: function (data) {
                            console.log(data);
                            // data_log(data, function () {
                            $(tr).remove();
                            // });
                        }
                    });
                });

            });
        }
    }
})(jQuery, Drupal, drupalSettings);