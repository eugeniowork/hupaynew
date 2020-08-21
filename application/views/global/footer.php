</body>
</html>

<script>
    var base_url = '<?php echo base_url()?>'
    $.protip();
    function render_response(div,msg, status){
        $(div).empty();
        $(div).append(
            '<div class="alert alert-'+status+' alert-dismissible fade show">'+
            msg+
            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
            '</div>'
        );
    }
</script>
<script src="<?php echo base_url();?>assets/js/global/toast_options.js"></script>