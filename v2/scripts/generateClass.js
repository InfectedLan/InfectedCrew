function addRow() {
	numRows++;
	$("#fieldContainer").append('<div id="field' + numRows + '">' + 
		'Navn: <input type="text" id="name' + numRows + '" value="id" />' + 
		'Sql type: <input type="text" id="sql' + numRows + '" value="int" />' + 
		'Sql length: <input type="text" id="length' + numRows + '" value="11" />' +
		'Auto increment: <input type="checkbox" id="autoIncrement' + numRows + '" />' +
		'</div>');
}
function removeRow() {
	if(numRows<=1)
	{
		alert("Du har for få rader for å fjerne flere!");
	}	
	else
	{
		$("#field" + numRows).remove();
		numRows--;
	}
}
//Credits http://stackoverflow.com/questions/1026069/capitalize-the-first-letter-of-string-in-javascript
function capitaliseFirstLetter(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}
function generate() {
	var name = $("#objectName").val();
	//Generer SQL
	var sqlData = [];
	var primaryKey = "";

	sqlData.push('CREATE TABLE IF NOT EXISTS `' + name + 's` (');
	for(var i = 0; i < numRows; i++)
	{
		sqlData.push('`' + $("#name" + (i+1) ).val() + '` ' + 
			$("#sql" + (i+1) ).val() + 
			'(' + $("#length" + (i+1) ).val() + ') NOT NULL'
			 + ( $("#autoIncrement" + (i+1) ).prop('checked') ? ' AUTO_INCREMENT,' : ',' ));

		if($("#autoIncrement" + (i+1) ).prop('checked')) {
			primaryKey = $("#name" + (i+1) ).val();
		}
	}

	sqlData.push('PRIMARY KEY (`' + primaryKey + '`)');
	sqlData.push(') ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;');

	$("#sqlResult").text(sqlData.join('\n'));

	//Generer klasse

	var classData = [];
	classData.push('class ' + capitaliseFirstLetter(name) + ' {');
	for(var i = 0; i < numRows; i++)
	{
		classData.push('	private $' + $("#name" + (i+1) ).val() + ';');
	}
	classData.push('');
		var constHeader = [];
		constHeader.push('	public function __construct(');
		for(var i = 0; i < numRows; i++)
		{
			constHeader.push((i == 0 ? '' : ', ') + '$' + $("#name" + (i+1) ).val());
		}
		constHeader.push(') {');
	classData.push(constHeader.join(''));
	for(var i = 0; i < numRows; i++)
	{
		classData.push('		$this->' + $("#name" + (i+1) ).val() + ' = $' + $("#name" + (i+1) ).val() + ';');
	}
	classData.push('	}');
	for(var i = 0; i < numRows; i++)
	{
		classData.push("");
		classData.push('	public function get' + capitaliseFirstLetter($("#name" + (i+1) ).val()) + '() {');
		classData.push('		return $this->' + $("#name" + (i+1) ).val() + ';');
		classData.push('	}');
		//classData.push('		$this->' + $("#name" + (i+1) ).val() + ' = $' + $("#name" + (i+1) ).val() + ';');
	}

	classData.push('}');

	$("#classResult").text(classData.join('\n'));

	//Generer handler
	var handlerData =[];

	handlerData.push("require_once 'settings.php';");
	handlerData.push("require_once 'mysql.php';");
	handlerData.push("require_once 'objects/" + name.toLowerCase() + ".php';");
	handlerData.push('class ' + capitaliseFirstLetter(name) + 'Handler {');
		handlerData.push('	public static function get' + capitaliseFirstLetter(name) + '($' + primaryKey + ') {');
			handlerData.push('		$con = MySQL::open(Settings::' + $("#dbName").val() + ');');
			handlerData.push('		');
			handlerData.push("		$result = mysqli_query($con, 'SELECT * FROM `' . Settings::" + $("#tableName").val() + " . '` WHERE `" + primaryKey + "` = \\'$" + primaryKey + "\\';');");
			handlerData.push('		');
			handlerData.push('		$row = mysqli_fetch_array($result);');
			handlerData.push('		');
			handlerData.push('		MySQL::close($con);');
			handlerData.push('		');
			handlerData.push('		if($row) {');

			var returnVariables = [];

			returnVariables.push()
			for(var i = 0; i < numRows; i++)
			{
				returnVariables.push((i == 0 ? '' : ', ') + '$row[\'' + $("#name" + (i+1) ).val() + '\']');
			}

			handlerData.push('			return new ' + capitaliseFirstLetter(name) + '(' + returnVariables.join('') + ');');
			handlerData.push('		}');
		handlerData.push('	}');
	handlerData.push('}');

	$("#handlerResult").text(handlerData.join('\n'));
}
var numRows = 1;