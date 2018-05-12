(function ($, Drupal, settings) {
    Drupal.behaviors.user_contracts = {
        attach: function (context) {
            $(document).ready(function () {
                var location = (window.location.href).replace("https://mystore/user/", "");
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
                    window.open("//mystore/contract/" + id);
                }


                $("select").change(function () {
                    var tr = $(this).parent().parent().parent();
                    var id = this.getAttribute('data');
                    var value = $(this).children(":selected")[0].getAttribute('value');
                    console.log(value);
                    if (value == 'go') {
                        go(id);
                    }
                    else if (value == 'view') {
                        console.log('go view');
                    }
                    else if (value == 'end') {
                        ajax(value, id, function (data) {
                            console.log(data);
                            // data_log(data, function () {
                            $(tr).css('background-color','red');
                            // });
                        });
                    }
                    else {
                        if(value!=0)
                        ajax(value, id, function (data) {
                            console.log(data);
                            // data_log(data, function () {
                            $(tr).remove();
                            // });
                        });
                    }
                });
                function ajax(value, id, callback) {
                    console.log('/user/'+location+'/'+value+'/'+id);
                    $.ajax({
                        type: 'get',
                        url: '/user/'+location+'/'+value+'/'+id,
                        success: function (data) {
                            callback(data);
                        },
                        error: function (jqXHR, error, errorThrown) {
                            console.log(jqXHR, error, errorThrown);
                        }
                    });
                }


            });
        }
    }
})(jQuery, Drupal, drupalSettings);