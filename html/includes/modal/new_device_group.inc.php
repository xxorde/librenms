<?php
/*
 * LibreNMS
 *
 * Copyright (c) 2014 Neil Lathwood <https://github.com/laf/ http://www.lathwood.co.uk/fa>
 *
 * This program is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the
 * Free Software Foundation, either version 3 of the License, or (at your
 * option) any later version.  Please see LICENSE.txt at the top level of
 * the source code distribution for details.
 */

if(is_admin() !== false) {

?>

 <div class="modal fade bs-example-modal-sm" id="create-group" tabindex="-1" role="dialog" aria-labelledby="Create" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h5 class="modal-title" id="Create">Device Groups</h5>
        </div>
        <div class="modal-body">
            <form method="post" role="form" id="group" class="form-horizontal group-form">
        <div class='form-group'>
            <label for='name' class='col-sm-3 control-label'>Name: </label>
            <div class='col-sm-9'>
                <input type='text' id='name' name='name' class='form-control' maxlength='200'>
            </div>
        </div>
        <div class='form-group'>
            <label for='desc' class='col-sm-3 control-label'>Description: </label>
            <div class='col-sm-9'>
                <input type='text' id='desc' name='desc' class='form-control' maxlength='200'>
            </div>
        </div>
            <input type="hidden" name="group_id" id="group_id" value="">
            <input type="hidden" name="type" id="type" value="create-device-group">
        <div class="form-group">
                <label for='pattern' class='col-sm-3 control-label'>Pattern: </label>
                <div class="col-sm-5">
                        <input type='text' id='suggest' name='pattern' class='form-control' placeholder='I.e: devices.status'/>
                </div>
        </div>
        <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                        <p>Start typing for suggestions, use '.' for indepth selection</p>
                </div>
        </div>
        <div class="form-group">
                <label for='condition' class='col-sm-3 control-label'>Condition: </label>
                <div class="col-sm-5">
                        <select id='condition' name='condition' placeholder='Condition' class='form-control'>
                                <option value='='>Equals</option>
                                <option value='!='>Not Equals</option>
				<option value='~'>Like</option>
				<option value='!~'>Not Like</option>
                                <option value='>'>Larger than</option>
                                <option value='>='>Larger than or Equals</option>
                                <option value='<'>Smaller than</option>
                                <option value='<='>Smaller than or Equals</option>
                        </select>
                </div>
        </div>
        <div class="form-group">
                <label for='value' class='col-sm-3 control-label'>Value: </label>
                <div class="col-sm-5">
                        <input type='text' id='value' name='value' class='form-control'/>
                </div>
        </div>
        <div class="form-group">
                <label for='group-glue' class='col-sm-3 control-label'>Connection: </label>
                <div class="col-sm-5">
                        <button class="btn btn-default btn-sm" type="submit" name="group-glue" value="&&" id="and" name="and">And</button>
                        <button class="btn btn-default btn-sm" type="submit" name="group-glue" value="||" id="or" name="or">Or</button>
                </div>
        </div>
            <div class="row">
                <div class="col-md-12">
                    <span id="response"></span>
                </div>
            </div>
        <div class="form-group">
                <div class="col-sm-offset-3 col-sm-3">
                        <button class="btn btn-default btn-sm" type="submit" name="group-submit" id="group-submit" value="save">Save Group</button>
                </div>
        </div>
</form>
                        </div>
                </div>
        </div>
</div>

<script>

$('#create-group').on('hide.bs.modal', function (event) {
    $('#response').data('tagmanager').empty();
    $('#name').val('');
    $('#desc').val('');
});

$('#create-group').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var group_id = button.data('group_id');
    var modal = $(this)
    $('#group_id').val(group_id);
    $('#tagmanager').tagmanager();
    $('#response').tagmanager({
           strategy: 'array',
           tagFieldName: 'patterns[]'
    });
    $.ajax({
        type: "POST",
        url: "/ajax_form.php",
        data: { type: "parse-device-group", group_id: group_id },
        dataType: "json",
        success: function(output) {
            var arr = [];
            $.each ( output['pattern'], function( key, value ) {
                arr.push(value);
            });
            $('#response').data('tagmanager').populate(arr);
            $('#name').val(output['name']);
            $('#desc').val(output['desc']);
        }
    });
});
var cache = {};
$('#suggest').typeahead([
    {
      name: 'suggestion',
      remote : '/ajax_rulesuggest.php?device_id=-1&term=%QUERY',
      template: '{{name}}',
      valueKey:"name",
      engine: Hogan
    }
]);

$('#and, #or').click('', function(e) {
    e.preventDefault();
    var entity = $('#suggest').val();
    var condition = $('#condition').val();
    var value = $('#value').val();
    var glue = $(this).val();
    if(entity != '' && condition != '') {
        $('#response').tagmanager({
           strategy: 'array',
           tagFieldName: 'patterns[]'
        });
        if(entity.indexOf("%") >= 0) {
            $('#response').data('tagmanager').populate([ entity+' '+condition+' '+value+' '+glue ]);
        } else {
            $('#response').data('tagmanager').populate([ '%'+entity+' '+condition+' "'+value+'" '+glue ]);
        }
    }
});

$('#group-submit').click('', function(e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "/ajax_form.php",
        data: $('form.group-form').serialize(),
        success: function(msg){
            $("#message").html('<div class="alert alert-info">'+msg+'</div>');
            $("#create-group").modal('hide');
            if(msg.indexOf("ERROR:") <= -1) {
                $('#response').data('tagmanager').empty();
                setTimeout(function() {
                    location.reload(1);
                }, 1000);
            }
        },
        error: function(){
            $("#message").html('<div class="alert alert-info">An error occurred creating this group.</div>');
            $("#create-group").modal('hide');
        }
    });
});

</script>

<?php

}

?>
