(function ($, Drupal, settings) {
    Drupal.behaviors.Provider = {
        attach: function (context) {
            $(document).ready(function () {

                $("select").change(function(){
                    var tr = $(this).parent().parent().parent();
                    var id = this.getAttribute('data');
                    var value = $(this).children(":selected")[0].getAttribute('value');
                    switch (value){
                        case 'hide':{
                            var option = this.children[2];
                            if(tr[0].getAttribute('read')=='false') {
                                $.ajax({
                                    type: 'get',
                                    url: '/' + value + '/' + id,
                                    success: function (data) {
                                        data_log(data,function () {
                                            option.setAttribute('disabled', 'disabled');
                                            $(tr).css('background-color', 'inherit');
                                        });
                                    }
                                });
                            }else{}
                            option.setAttribute('disabled', 'disabled');
                            break;
                        }
                        case 'refuse':{
                            $.ajax({
                                type: 'get',
                                url: '/' + value + '/' + id,
                                success: function (data) {
                                    data_log(data,function () {
                                        $(tr).remove();
                                    });
                                }
                            });
                            break;
                        }
                        case 'confirm':{
                            document.location.href = '/contract_create/'+id;
                            break;
                        }
                        default:{
                            console.log('switsh error!');
                            break;
                        }
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

                function printError(msg) {
                    document.getElementById('page-info').innerHTML = msg;
                }

            });
        }
    }
})(jQuery, Drupal, drupalSettings);
