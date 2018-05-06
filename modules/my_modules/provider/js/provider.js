(function ($, Drupal, settings) {
    Drupal.behaviors.Provider = {
        attach: function (context) {
            $(document).ready(function () {
                var tbody = Array.from(document.getElementById('table').children[1].children);
                tbody.forEach(function (item,i,arr) {
                    item.setAttribute('mod','closed');
                    if(item.children.length<2) return;
                    var msg = item.children[4].innerHTML;
                    if(msg.length>27){
                        item.children[4].innerHTML = null;
                        item.children[4].innerHTML = '<div id='+`short-${i}`+'>'+msg.substring(0,27)+'...</div>'+'<div id='+`long-${i}`+'>'+msg+'</div>';
                        $(`#long-${i}`).hide();
                    }
                });
                $('tr').bind('click', function(){
                    if(event.target.tagName!="SELECT") {
                        var i = this.children[0].innerHTML - 1;
                        if(this.getAttribute('mod')=='closed') {
                            $(`#long-${i}`).show();
                            $(`#short-${i}`).hide();
                            this.setAttribute('mod', 'open');
                        }else{
                            $(`#long-${i}`).hide();
                            $(`#short-${i}`).show();
                            this.setAttribute('mod', 'closed');
                        }
                        console.log(i+1, this.getAttribute('mod'));
                    }
                });

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
                            //document.location.href = '/edit/'+(event.target.attributes['data'].nodeValue-id_changer);
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
                    }

                }

            });
        }
    }
})(jQuery, Drupal, drupalSettings);
