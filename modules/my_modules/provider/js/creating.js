(function ($, Drupal, settings) {
    Drupal.behaviors.Creating = {
        attach: function (context) {
            $(document).ready(function () {

                var user = document.getElementById('user_link');
                var id = user.getAttribute('user_id');
                user.innerHTML = '<a href="/user"'+id+'>'+user.innerHTML+'</a>';
                console.log(user);


            });
        }
    }
})(jQuery, Drupal, drupalSettings);