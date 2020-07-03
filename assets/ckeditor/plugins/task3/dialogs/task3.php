<?php  
include  dirname(__DIR__,5)."/config/config.php";
$connect = mysqli_connect(host_name, user_name, db_password, db_name);
$sql = "SELECT * From temp";
$result = mysqli_query($connect, $sql);
$temps = array();
while($row = mysqli_fetch_assoc($result)){
    $temps[] = $row;
}
?>
CKEDITOR.dialog.add( 'task3Dialog', function( editor ) {
    return {
        title: 'HiHi',
        minWidth: 450,
        minHeight: 200,
        contents: [{
		    id: 'task3',
		    label: 'Basic Settings',
		    elements: [
		    {
		    	type: "vbox",
		    	id: "page",
		    	children:[{
		    		type:"hbox",
		    		widths: ["30%", "65%"],
                    children: [{
                    	id: "changetemp",
                    	type: "select",
                    	label: "Change temp",
                    	items: [['select',''], <?php foreach ($temps as $key => $temp) {
					    	echo "['".$temp['name']."','".$temp['content']."'],";
					    } ?>],
					    "default": "",
					    onChange: function(){
					    	var document = this.getElement().getDocument();
						    var element = document.getById('showtemp');
						    if (element) {
						        element.setHtml(this.getValue());
						    }
						}
                	},
                	{
                		id: "showtemp",
                		type: "html",
                		html: '<h4 style="margin-top: 6px;">Show temp</h4><div id="showtemp"></div>'
                	}]
		    	}]
		    }
			]},
		],
		onOk: function(){
			var document = this.getElement().getDocument();
		    var element = document.getById('showtemp');
			editor.insertHtml(element.getText());
			element.setText('');
		}
    };
});