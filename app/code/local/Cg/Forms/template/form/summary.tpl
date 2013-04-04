<form action="{$formActionUrl}" method="post" id="edit_form" enctype="multipart/form-data">
<input id="customer_id" name="data[customer_id]" value="{$customer_id}" type="hidden">
<input id="customer_id" name="id" value="{$data.visit_id}" type="hidden">
{$formKey}
<div class="entry-edit">
    <div class="entry-edit-head">
        <h4 class="icon-head head-edit-form fieldset-legend">{"Visit information"|t:cg_forms}</h4>
        <div class="form-buttons"></div>
    </div>
    <div class="fieldset fieldset-wide" id="visit_form">
        <div class="hor-scroll">
            <table cellspacing="0" class="form-list">
                <tbody>
                <tr>
                    <td class="label"><label for="customer">{"Customer"|t:cg_forms}<span class="required">*</span></label></td>
                    <td class="value">
                        {*<input id="customer" class="required-entry input-text required-entry" type="text">*}
                        <b>{$customer.firstname} {$customer.middlename} {$customer.lastname}</b>, {$customer.dob|date_format:"%d.%m.%Y"} г.р.
                    </td>
                </tr>
                <tr>
                    <td class="label"><label for="customer">{"Date"|t:cg_forms}<span class="required">*</span></label></td>
                    <td class="value">
                        <input type="text" id="datepicker" name="user_date" value="{$data.user_date|default:$smarty.now|date_format:"%d.%m.%Y"}" />
                    </td>
                </tr>
                <tr>
                    <td class="label"><label for="visit_description1">{"Summary"|t:cg_forms}<span class="required">*</span></label></td>
                    <td class="value">
                        {*<textarea id="visit_description1" name="row_data[visit_1]" class="required-entry required-entry textarea" rows="2" cols="15">{$row_data.visit_1}</textarea>*}
                        {$editor1}
                    </td>
                </tr>
                <tr>
                    <td class="label"><label for="visit_description2">{"Recommendation"|t:cg_forms}<span class="required">*</span></label></td>
                    <td class="value">
                        {$editor2}
                        {*<textarea id="visit_description2" name="row_data[visit_2]" class="required-entry required-entry textarea" rows="2" cols="15">{$row_data.visit_2}</textarea>*}
                    </td>
                </tr>
                <tr>
                    <td class="label"><label for="files">{"Files"|t:cg_forms}</label></td>
                    <td class="value">
                        <div class="row fileupload-buttonbar">
                            <div class="span7">
                                <span class="btn btn-success fileinput-button">
                                    <i class="icon-plus icon-white"></i>
                                    <span>{"Add files"|t:cg_forms}</span>
                                    <input type="file" name="files[]" multiple>
                                </span>
                            </div>
                            <div class="span5 fileupload-progress fade">
                                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                    <div class="bar" style="width:0%;"></div>
                                </div>
                                <div class="progress-extended">&nbsp;</div>
                            </div>
                        </div>
                        <div class="fileupload-loading"></div>
                        <br>
                        <table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>


<script>
$j(function() {
    function log( message ) {
        $j( "<div>" ).text( message ).prependTo( "#log" );
        $j( "#log" ).scrollTop( 0 );
    }

    $j( "#customer" ).autocomplete({
        source: "{$customerSearchUrl}",
        minLength: 2,
        select: function( event, ui ) {
            $j('#customer_id').val(ui.item.id);
//            log( ui.item ?
//                    "Selected: " + ui.item.value + " aka " + ui.item.id :
//                    "Nothing selected, input was " + this.value );
        }
    });
    $j( "#datepicker" ).datepicker({
        showOn: "button",
        buttonImage: "/skin/adminhtml/default/default/images/grid-cal.gif",
        buttonImageOnly: true,
        dateFormat: "dd.mm.yy",
        defaultDate: "22.05.2013"
    });

    //$j("#datepicker").datepicker($j.datepicker.regional["fr"]);
});
</script>
{literal}
<script id="template-upload" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td class="preview"><span class="fade"></span></td>
        <td class="name"><span>{%=file.name%}</span></td>
        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
        {% if (file.error) { %}
        <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
        {% } else if (o.files.valid && !i) { %}
        <td>
            <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
        </td>
        <td class="start">{% if (!o.options.autoUpload) { %}
            <button class="btn btn-primary">
                <i class="icon-upload icon-white"></i>
                <span>Start</span>
            </button>
                          {% } %}</td>
        {% } else { %}
        <td colspan="2"></td>
        {% } %}
        <td class="cancel">{% if (!i) { %}
            <button class="btn btn-warning">
                <i class="icon-ban-circle icon-white"></i>
                <span>Cancel</span>
            </button>
                           {% } %}</td>
    </tr>
    {% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        {% if (file.error) { %}
        <td></td>
        <td class="name"><span>{%=file.name%}</span></td>
        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
        <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
        {% } else { %}
        <td class="preview">{% if (file.thumbnail_url) { %}
            <a href="{%=file.url%}" title="{%=file.name%}" data-gallery="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
                            {% } %}</td>
        <td class="name">
            <a href="{%=file.url%}" title="{%=file.name%}" data-gallery="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
        </td>
        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
        <td colspan="2"></td>
        {% } %}
        <td class="delete">
            <button class="btn btn-danger" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}"{% if (file.delete_with_credentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
            <i class="icon-trash icon-white"></i>
            <span>Delete</span>
            </button>
        </td>
    </tr>
    {% } %}
</script>
{/literal}
