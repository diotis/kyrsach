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


            });
        }
    }
})(jQuery, Drupal, drupalSettings);