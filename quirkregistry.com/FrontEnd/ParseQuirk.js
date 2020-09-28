function parseQuirk(obj) {
	try {
		//Determine what the weakness will be
		var finalLimit = limitCalc(obj["Power"]["Limit"],
			obj["Weak"]["Desc"],
			obj["Weak"]["URL"]);
		
		// Determines how the quirk will manifest
		var channel = activationCalc(obj["Body"]["Part"],
			obj["action"],
			obj["activity"]['activity'],
			obj["Power"]["Special"]);
		
		
		//generate the stats and stat chart
		var stats = generateStats();
		setChart(stats);
		
		//generate range based on value from chart
		var min = Math.floor((stats['range']['num'] - 25) / 2);
		var max = Math.floor((stats['range']['num']) / 2);
		if(min >= 50) {
			max = max * 4 
		}

		var range = min + Math.floor((max - min + 1) * Math.random());
		
		setPowerData(obj["Power"]["Name"],
			obj["Power"]["Desc"],
			finalLimit,
			obj["Weak"]["Desc"],
			obj["Weak"]["URL"],
			channel,
			range,
			obj["Power"]["Special"]);
		
		constructOutput(obj["Power"]["Name"],
			obj["Power"]["Desc"],
			finalLimit,
			channel,
			range,
			obj["Power"]["Special"]);
			
		return true;
	} catch(e) {
		console.log(e);
		return false;
	}
}

function setPowerData(powerName, powerDesc, finalLimit, medDesc, medURL, channel, range, special) {
	data = "Ability: " + powerName + "\r\n";
	data += "Desc: " + powerDesc + "\r\n";
	data += "Condition: " + channel + "\r\n";
	
	//if the medical limit is chosen, then add that to clipboard, else use the determined value of limit
	if(finalLimit.indexOf(medDesc) >= 0) {
		data += "Limit: [" + medDesc + "](" + medURL + ")\r\n";
	} else {
		data += "Limit: " + finalLimit + "\r\n";
	}
	
	data += "Range: " + range + "m\r\n";
	
	$("#copy").data("power", data);
}

// Creates the final format for the quirk to be displayed to the user
function constructOutput(powerName, powerDesc, finalLimit, activation, range, special) {
	$("#name").text(name);
	$("#powerName").text(powerName);
	$("#powerDesc").text(powerDesc);
	$("#activation").text(activation);
	$("#limit").html(finalLimit);
	
	$("#range").text(range + "m");
	$("#rangeCapsule").show();
}

// Determine what the weakness will be.  If its the disease, the url for the
// page element will be set here
function limitCalc(limitPower, limitDisease, url) {
	var finalLimit = "";
	var limitChance = Math.random();
	
	if(limitPower == "None" || limitChance < 0.3) {
		finalLimit = "<a target='_blank' href=" + url + ">" + limitDisease + "</a>";
	} else {
		finalLimit = limitPower;
	}
	
	return finalLimit;
}

// Determines how the quirk is activated, whether body or certain activity
function activationCalc(body, action, activity, special) {
	var manifestation = "";
	var manChance = Math.random();

	if (manChance > 0.3) {
		manifestation = manBodyPart(body, action);
	} else {
		manifestation = activity.charAt(0).toUpperCase() + activity.slice(1);
	}
	
	return manifestation;
}

// Constructs the body part used for the quirk and if there's an extra step
// in activating the power.
function manBodyPart(body, action) {
	var bodyChance = Math.random();
	var bodyActivation = "";
	
	// gives a chance that the whole body could be the source of the quirk.
	// the website doesn't have whole body as an option 
	if(bodyChance < 0.1) {
		bodyActivation = "Whole body";
	} else if(bodyChance > 0.1 && bodyChance < 0.4) {
		bodyActivation = "At users will";
	} else {
		bodyActivation = body.charAt(0).toUpperCase() + body.slice(1);
	}
	
	// Adds an optional descriptor to the body part to be, for example, "'move' your arm"
	var extraDescriptor = Math.random();

	if(extraDescriptor < 0.3 && bodyActivation != "At users will") {
		bodyActivation = action['action'] + " your " + bodyActivation;
	}
	
	return bodyActivation;
}

function searchForSong() {
	var params = 
	{
		term: document.getElementById('search-keyword').value,
		callback: 'handleTunesSearchResults'
	};
	
	var params = urlEncode(params);

	var url = 'https://itunes.apple.com/search?' + params;
	var html = '<script src="' + url + '"><\/script>';
	jQuery('head').append(html);
}

//Formats the users request to suite a url
function urlEncode(obj) {
	var s = '';
	for (var key in obj) {
		s += encodeURIComponent(key) + '=' + encodeURIComponent(obj[key]) + '&';
	}
	if (s.length > 0) {
		s = s.substr(0, s.length - 1);
	}

	return (s);
}

//Used to store all names from the albums into an array that is a global variable
function handleTunesSearchResults(arg) {
	var results = arg.results;
	//Deletes all entries in the array just to make sure that no entries are leftover.  I think that was an issue.
	Name = []
	var html = '';
	for (var i = 0; i < results.length; i++) 
	{
		var item = results[i];
		var obj =
		{
			source: 0,
			track_name: item.trackCensoredName,
		};
		Name[i] = obj.track_name;	
		
		//gets rid of useless descriptions in parenthesis
		if(Name[i].indexOf("(") != -1)
		{
			Name[i] = Name[i].substr(0,Name[i].indexOf("("))
		}
	}
	
	index = Math.floor((Math.random() * (Name.length - 1)));
	while(Name[index] == "Intro")
	{
		index = Math.floor((Math.random() * (Name.length - 1)));
	}
	
	//If the search was unsuccessful, then tell the user that, don't just show undefined
	var finalName = "";
	if(typeof Name[index] == 'undefined')
	{
		finalName = "Country Roads";
	}
	else
	{
		finalName = Name[index];
	}

	//set name and store value for copy to clipboard
	nameHtml = "<div class='quirkHeader'>Name</div><div class=quirkDiv id='name'>" + finalName + "</div>";
	$("#nameField").html(nameHtml);
	$("#copy").data("name", "Stand Name: " + finalName + "\r\n");
}

//generate the stat values and save them to clipboard sections
function generateStats() {
	var stats = ["E", "D", "C", "B", "A"];
	var statsNum = [25, 50, 75, 100, 125];
	var power = Math.floor((Math.random() * 5))
	var speed = Math.floor((Math.random() * 5))
	var range = Math.floor((Math.random() * 5))
	var durability = Math.floor((Math.random() * 5))
	var precision = Math.floor((Math.random() * 5))
	var potential = Math.floor((Math.random() * 5))
	
	var statClip = "Power: " + stats[power] + "\n";
	statClip += "Speed: " + stats[speed] + "\n";
	statClip += "Range: " + stats[range] + "\n";
	statClip += "Durability: " + stats[durability] + "\n";
	statClip += "Precision: " + stats[precision] + "\n";
	statClip += "Potential: " + stats[potential] + "\n";
	
	$("#copy").data("stats", statClip);
	
	return {'power': {'index': power, 'num': statsNum[power]}
	, 'speed': {'index': speed, 'num': statsNum[speed]}
	, 'range': {'index': range, 'num': statsNum[range]}
	, 'durability': {'index': durability, 'num': statsNum[durability]}
	, 'precision': {'index': precision, 'num': statsNum[precision]}
	, 'potential': {'index': potential, 'num': statsNum[potential]}}
}

//creates the stand stat chart, using increments of 25 to denote stat labels
function setChart(stats) {	
	let myConfig = {
	  title: {
		text: 'Stats',
		fontSize: 24,
		fontColor: '#91cb7e'
	  },
	  plot: {
		aspect: "area"
	  },
	  type: "radar",
	  'background-color': "none",
	  series: [{
		values: [stats['power']['num']
		, stats['speed']['num']
		, stats['range']['num']
		, stats['durability']['num']
		, stats['precision']['num']
		, stats['potential']['num']],
		'line-color': "red",
		'background-color': "red",
		marker: {
			type: "star5",
			'background-color': "red",
			'border-color': "red"
		 }
	  }],
	  'scale-v': {
		values: "0:125:25",
		labels: ['', 'E', 'D', 'C', 'B', 'A'],
		item: { //To style your scale labels.
		  'font-color': "white",
		  'font-family': "Montserrat",
		  'font-size':12,
		  'font-weight': "bold",
		  'font-style': "italic"
		},
		guide: {
			'background-color': "#91cb7e #452274"
		}
	  },
	  'scale-k': {
		labels: ['Power', 'Speed', 'Range', 'Durability', 'Precision', 'Potential'],
		item: { //To style your scale labels.
		  'font-color': "white",
		  'font-family': "Montserrat",
		  'font-size':10,
		  'font-weight': "bold",
		  'font-style': "italic"
		}
	  },
	  tooltip: {
		  visible: false
	  }
	};
	
	zingchart.render({
	  id: 'standStats',
	  data: myConfig,
	});
}