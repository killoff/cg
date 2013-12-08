/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
    // Define changes to default configuration here.
    // For the complete reference:
    // http://docs.ckeditor.com/#!/api/CKEDITOR.config

    // The toolbar groups arrangement, optimized for two toolbar rows.
//    config.toolbarGroups = [
//        { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
//        { name: 'editing',     groups: [ 'find', 'selection'] },
//        { name: 'links' },
//        { name: 'insert' },
//        { name: 'forms' },
//        { name: 'tools' },
//        { name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
//        { name: 'others' },
//        '/',
//        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
//        { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] },
//        { name: 'styles' },
//        { name: 'colors' },
//    ];


    config.toolbar =
        [
            { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Subscript','Superscript','-','RemoveFormat' ] },
            { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
                '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
            { name: 'clipboard', items : ['PasteText','PasteFromWord' ] },
            { name: 'insert', items : ['Table','HorizontalRule','SpecialChar' ] },
            { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
            { name: 'colors', items : [ 'TextColor','BGColor' ] },

        ];
    // Remove some buttons, provided by the standard plugins, which we don't
    // need to have in the Standard(s) toolbar.
    config.removeButtons = 'Underline,Subscript,Superscript';
    config.language = 'ru';
    config.height = '600px';
    config.contentsCss = '/css/editor.css';

    config.extraPlugins = 'typograf';
};
