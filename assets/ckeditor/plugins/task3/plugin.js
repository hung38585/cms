CKEDITOR.plugins.add( 'task3', {
    icons: 'task3',
    init: function( editor ) {
        editor.addCommand( 'task3', new CKEDITOR.dialogCommand( 'task3Dialog' ) );
        editor.ui.addButton( 'Task3', {
            label: 'Insert Task3',
            command: 'task3',
            toolbar: 'insert'
        });
        CKEDITOR.dialog.add( 'task3Dialog', this.path + 'dialogs/task3.php' );
    }
});