CKEDITOR.plugins.add('typograf', {
    icons: 'typograf',
    init: function( editor ) {

        editor.addCommand('typografy', {
            exec: function( editor ) {
                $.post('/js/ckeditor/plugins/typograf/typograf.php', {text: editor.getData()}, function(response) {
                    editor.setData(response);
                });
            }
        });

        editor.ui.addButton('Typograf', {
            label: 'Make it sweet :)',
            command: 'typografy',
            toolbar: 'insert'
        });
        //Plugin logic goes here.
    }
});
