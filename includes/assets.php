<script src="./js/google-analytics.js"></script>
<script src="./js/jquery-1.11.1.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        var scroll_start = 0;
        var startchange = $('#top');
        var offset = startchange.offset();
        if (startchange.length){
            $(document).scroll(function() {
                scroll_start = $(this).scrollTop();
                if(scroll_start > offset.top) {
                    $(".navbar-inverse").css('background-color', 'rgba(240,240,240,0.85)');
                } else {
                    $('.navbar-inverse').css('background-color', 'transparent');
                }
            });
        }
    });
</script>