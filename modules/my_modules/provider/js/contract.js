(function ($, Drupal, settings) {
    Drupal.behaviors.CHAT = {
        attach: function (context) {
            $(document).ready(function () {
                var last = 0;
                var id = (document.URL).replace('https://mystore/contract/', '');
                var load = document.getElementById('chat');
                load.innerHTML = "<svg xmlns:svg=\"http://www.w3.org/2000/svg\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" version=\"1.0\" width=\"64px\" height=\"64px\" viewBox=\"0 0 128 128\" xml:space=\"preserve\"><g transform=\"translate(0,128) scale(1,-1)\"><circle cx=\"16\" cy=\"64\" r=\"16\" fill=\"#000000\" fill-opacity=\"1\"/><circle cx=\"16\" cy=\"64\" r=\"14.344\" fill=\"#000000\" fill-opacity=\"1\" transform=\"rotate(45 64 64)\"/><circle cx=\"16\" cy=\"64\" r=\"12.531\" fill=\"#000000\" fill-opacity=\"1\" transform=\"rotate(90 64 64)\"/><circle cx=\"16\" cy=\"64\" r=\"10.75\" fill=\"#000000\" fill-opacity=\"1\" transform=\"rotate(135 64 64)\"/><circle cx=\"16\" cy=\"64\" r=\"10.063\" fill=\"#000000\" fill-opacity=\"1\" transform=\"rotate(180 64 64)\"/><circle cx=\"16\" cy=\"64\" r=\"8.063\" fill=\"#000000\" fill-opacity=\"1\" transform=\"rotate(225 64 64)\"/><circle cx=\"16\" cy=\"64\" r=\"6.438\" fill=\"#000000\" fill-opacity=\"1\" transform=\"rotate(270 64 64)\"/><circle cx=\"16\" cy=\"64\" r=\"5.375\" fill=\"#000000\" fill-opacity=\"1\" transform=\"rotate(315 64 64)\"/><animateTransform attributeName=\"transform\" type=\"rotate\" values=\"0 64 64;315 64 64;270 64 64;225 64 64;180 64 64;135 64 64;90 64 64;45 64 64\" calcMode=\"discrete\" dur=\"480ms\" repeatCount=\"indefinite\"></animateTransform></g></svg>";

                $.ajax({
                    type: 'get',
                    url: '/chat/' + id,
                    success: function (data) {
                        var messages = JSON.parse(data);
                        if(messages.length>0) {
                            load.innerHTML='';
                            messages.forEach(function (item, i, arr) {
                                addmsg(item);
                            });
                            var container = $('#chat');
                            container[0].scrollTop = container[0].scrollHeight;
                        }
                        else{
                            load.innerHTML = "Сообзений не найдено!";
                        }
                        //load.innerHTML = data;
                        if (messages.length > 0) {
                            last = messages[messages.length - 1]['id'];
                        }
                        console.log(JSON.parse(data));
                        console.log("start update");
                        //setInterval(update, 2000);
                    },
                    error: function (jqXHR, error, errorThrown) {
                        load.innerHTML = 'Error';
                        console.log(jqXHR, error, errorThrown);
                    }
                });


                $('#chat_btn').bind('click', function () {
                    var msg = document.getElementById('chat_text');
                    $.ajax({
                        type: 'get',
                        url: '/chat/add/' + id + '/' + msg.value,
                        success: function (data) {
                            load.innerHTML += msg.value;
                            console.log(data);
                        },
                        error: function (jqXHR, error, errorThrown) {
                            load.innerHTML = 'Error';
                            console.log(jqXHR, error, errorThrown);
                        }
                    });
                    msg.value = '';
                });

                function update() {
                    $.ajax({
                        type: 'get',
                        url: '/chat/update/' + id + '/' + last,
                        success: function (data) {
                            console.log(data);
                            add(data);
                        },
                        error: function (jqXHR, error, errorThrown) {
                            load.innerHTML = 'Error';
                            console.log(jqXHR, error, errorThrown);
                        }
                    });
                }

                function add(data) {
                    var messages = JSON.parse(data);
                    if (messages.length > 0) {
                        messages.forEach(function (item, i, arr) {
                            addmsg(item);
                        });
                        last = messages[messages.length - 1]['id'];
                        // var container = $('#chat');
                        // container[0].scrollTop = container[0].scrollHeight;
                        console.log(JSON.parse(data));
                    }
                }
                function addmsg(item) {
                        var msg = "<div class='msg_sender'>User</div>";
                        var msg_class = "";
                        if (item['owner_id'] == '1'){
                            //msg = "<div class='msg_user'>"+"<link href='#'>User"+messages[i]['owner_id']+"</link>"+"</div>";
                            msg_class = 'msg_right';
                        }else{
                            //msg = "<div class='msg_interlocutor'>User</div>";
                            msg_class = 'msg_left';
                        }
                        load.innerHTML+="<div class="+msg_class+">" +
                            msg +
                            "<div class='msg_date'>"+item['date']+"</div>"+
                            "<div class='msg'>"+item['message']+"</div>"+
                            "</div>";
                }
            });
        }
    }
})(jQuery, Drupal, drupalSettings);