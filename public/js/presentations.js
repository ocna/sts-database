/**
 * Created with JetBrains PhpStorm.
 * User: sandysmith
 * Date: 9/8/13
 * Time: 12:08 AM
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(function() {
    $('.btn-danger').click(function() {
        var id = $(this).attr('data-delete');
        var name = $(this).attr('data-name');
        if (confirm('Are you sure you want to delete this presentation at ' + name + '? You cannot undo this!')) {
            $('#deleteID').val(id);
            $('#deleteForm').submit();
        } else {
            return false;
        }
    })
})
