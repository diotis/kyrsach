(function ($, Drupal, settings) {
    Drupal.behaviors.Provider = {
        attach: function (context) {
            $(document).ready(function () {
                // var id_changer = 0;
                // $('input[id="del"]').click(function (event) {
                //     var id = event.target.attributes['data'].nodeValue;
                //     $.ajax({
                //         type:'get',
                //         url:'/del/'+id,
                //         success: function(data){
                //             location.reload(true);
                //         }
                //     });
                // });
                // $('input[id="edit"]').click(function (event) {
                //     document.location.href = '/edit/'+(event.target.attributes['data'].nodeValue-id_changer);
                // });
                var tbody = Array.from(document.getElementById('table').children[1].children);
                tbody.forEach(function (item,i,arr) {
                    item.setAttribute('mod','closed');
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
                    var id = this.getAttribute('data');
                    var value = $(this).children(":selected")[0].getAttribute('value');
                    console.log(value, id);

                    if(value == 'hide' || value == 'refuse') {
                        // $.ajax({
                        //     type: 'get',
                        //     url: '/'+value+'/' + id,
                        //     success: function (data) {
                        //         console.log(data);
                        //         location.reload(true);
                        //     }
                        // });
                    }
                    if(value == 'confirm'){
                        //document.location.href = '/edit/'+(event.target.attributes['data'].nodeValue-id_changer);
                    }
                });


            });
        }
    }
})(jQuery, Drupal, drupalSettings);
