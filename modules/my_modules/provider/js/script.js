(function ($, Drupal, settings) {
    Drupal.behaviors.Module = {
        attach: function (context) {
            $(document).ready(function () {
                var tags = document.getElementById('tags');
                if(settings.path.currentPath == 'provider'){
                    set(0);
                }else set(1);

                function set(i) {
                    tags.children[i].setAttribute('class','active');
                }
                var tbody = Array.from(document.getElementById('table').children[1].children);
                tbody.forEach(function (item,i,arr) {
                    item.setAttribute('mod','closed');
                    if(item.children.length<2) return;
                    var msg = item.children[3].innerHTML;
                    if(msg.length>27){
                        item.children[3].innerHTML = null;
                        item.children[3].innerHTML = '<div id='+`short-${i}`+'>'+msg.substring(0,27)+'...</div>'+'<div id='+`long-${i}`+'>'+msg+'</div>';
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
            });
        }
    }
})(jQuery, Drupal, drupalSettings);