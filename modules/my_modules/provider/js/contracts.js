(function ($, Drupal, settings) {
    Drupal.behaviors.CONTRACTS = {
        attach: function (context) {
            $(document).ready(function () {
                console.log('contracts.js');
                var location = (window.location.href).replace("https://mystore/contracts/", "");
                if (location == '') {
                    location = 'new'
                }
                var tbody = Array.from(document.getElementById('table').children[1].children);
                tbody.forEach(function (item, i, arr) {
                    if (item.hasAttribute('user_id')) {
                        var id = item.getAttribute('user_id');
                        if (id)
                            item.children[3].innerHTML = '<a href="http://mystore/user/' + id + '">' + item.children[3].innerHTML + '</a>';
                    }
                });
                $("select").change(function () {
                    var tr = $(this).parent().parent().parent();
                    var id = this.getAttribute('data');
                    var nid = tr[0].getAttribute('node');
                    var value = $(this).children(":selected")[0].getAttribute('value');
                    console.log('/contracts/' + location + '/' + value + '/' + id);

                    if (value == 'go') {
                        window.open("//mystore/contract/" + id);
                    }
                    if( value=='view'){
                        window.open("//mystore/node/" + nid);
                    }
                    else
                        $.ajax({
                            type: 'get',
                            url: '/contracts/' + location + '/' + value + '/' + id,
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