(function ($, Drupal, settings) {
    Drupal.behaviors.Refused = {
        attach: function (context) {
            $(document).ready(function () {

                function printError(msg) {
                    document.getElementById('page-info').innerHTML = msg;
                }

                $("select").change(function() {
                    var tr = $(this).parent().parent().parent();
                    var id = this.getAttribute('data');
                    var value = $(this).children(":selected")[0].getAttribute('value');

                    $.ajax({
                        type: 'get',
                        url: '/' + value + '/' + id,
                        success: function (data) {
                            console.log(data);
                            data_log(data,function () {
                                $(tr).remove();
                            });
                        },
                        error: function (jqXHR, error, errorThrown) {
                            console.log(jqXHR, error, errorThrown);
                            printError(error);
                        }
                    });
                    function data_log(_data,callback) {
                        var data = JSON.parse(_data);
                        if(data['error']) {
                            console.log("error! "+data['data']);
                        }
                        else {
                            callback();
                            console.log(data['data']);
                            printError(data['data']);
                        }

                    }
                });


            });
        }
    }
})(jQuery, Drupal, drupalSettings);