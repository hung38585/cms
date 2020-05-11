CKEDITOR.dialog.add( 'task3Dialog', function( editor ) {
    return {
        title: 'HiHi',
        minWidth: 400,
        minHeight: 150,
        contents: [{
		    id: 'task3',
		    label: 'Basic Settings',
		    elements: [
			{
			    type: 'radio',
			    id: 'typetext',
			    label: 'Chọn đi cưng!',
			    items: [ [ 'H1', 'H1' ], [ 'H2', 'H2' ], [ 'H3', 'H3' ], [ 'H4', 'H4' ], [ 'H5', 'H5' ], [ 'H6', 'H6' ] ],
			    style: 'color: green',
			    //'default': '<h1></h1>',
			    onClick: function() {
			        // this = CKEDITOR.ui.dialog.radio
			        // alert(this.getValue() );
			        var set = this.getDialog().getContentElement("task3","result");
			        set.setValue(this.getValue());
			    }
			    //var document = this.getElement().getDocument();
				    //var element2 = document.getById('showtemplate');
				    //if(element2){
				    	//element2.setAttribute("src","/../../../../../"+this.getValue());
					//}
			},
		    {
		        type: 'text',
		        id: 'result',
		        label: 'Của cưng nè!',
		        setup: function(){
                    this.enable();
		        },
		        //validate: CKEDITOR.dialog.validate.notEmpty( "Explanation field cannot be empty." )
		    },
		    
			]},
		]
    };
});