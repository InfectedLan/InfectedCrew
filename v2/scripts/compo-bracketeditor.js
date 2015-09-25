var bracketSource = null;
var bracket = null;
var timePromptCallback = function() {};
/* var compoId = 0; */

function offsetRenderer(offsets, index) {
    return '<div class="addMatchButton"><a href="javascript:addMatch(' + index + ')">Lag en ny match her</a></div>';
}

function customMatchRenderer(match, clans) {
    var html = [];
    html.push('<div class="bracket_header">');
    html.push('Match id ' + match.id);
    html.push('<a href="javascript:setTag(' + match.id + ')">');
    if(typeof match.metadata.tag !== 'undefined') {
	html.push(', tag: ' + match.metadata.tag);
    } else {
	html.push(', no tag');
    }
    html.push('</a>');
    html.push('</div>');
    
    for(var i = 0; i < match.participants.length; i++) {
	if(i != 0) {
	    html.push('<div class="bracket_vs">vs</div>');
	}
	html.push('<div class="bracket_participant">' +  getParticipantString(match.participants[i], clans) +'</div>');
    }
    if(match.participants.length < 2) {
	html.push('<div class="bracket_participant"><a href="javascript:addParticipant(' + match.id + ')">Legg til deltager</a></div>');
    }
    html.push('<div class="bracket_participant"><a href="javascript:deleteMatch(' + match.id + ')">Slett match</a></div>');
    return html.join("");
}

function setTag(id) {
    api_setTag(id, prompt("Hva skal taggen settes til?"));
}

function deleteMatch(id) {
    if(confirm('Er du sikker på at du vil slette matchen? Dette kan kuke opp ting ganske hardt!')) {
	api_deleteMatch(id);
    }
}

function renderFinished(offsets) {
    $("#editor-canvas").append('<div class="bracketOffset"><div class="addMatchButton"><a href="javascript:addMatch(' + offsets.length + ')">Lag en ny match i ny kolonne</a></div></div>');
}

function customTimeFormatter(time, offsetId) {
    return '<a href="javascript:beginChangeOffsetTime(' + offsetId + ');">' + time + '</a>';
}

function initBracketEditor() {
    bracketSource = new DataSource(compoId);
    bracket = bracketSource.derive("editor-canvas", '.*', 110, 70, customMatchRenderer, renderFinished, offsetRenderer, null, customTimeFormatter);
}
var targetOffsetId = 0;
function beginChangeOffsetTime(offsetId) {
    targetOffsetId = offsetId;
    showTimePrompt(changeOffsetTime);
}

function changeOffsetTime(time) {
    var done = 0;
    var target = bracket.generatedOffsets[targetOffsetId].items.length;
    var items = bracket.generatedOffsets[targetOffsetId].items;
    for(var i = 0; i < target; i++) {
	$.getJSON('../api/json/match/setMatchTime.php?id=' + items[i].id + "&scheduledTime=" + time, function(data) {
	    done++;
	    if(done == target) {
		bracketSource.refresh();
	    }
	    if(!data.result) {
		error("Noe gikk galt: " + data.message);
	    }
	});
    }
}

$(document).ready(function() {
    $("#participantTypeClan").change(function(){
	if($("#participantTypeClan").val()) {
	    $("#participantIdSelector").fadeOut();
	    $("#participantMatchSelector").fadeIn();
	}
    });
    $("#participantTypeWalkover").change(function() {
	if($("#participantTypeWalkover").val()) {
	    $("#participantIdSelector").fadeOut();
	    $("#participantMatchSelector").fadeOut();
	}
    });
    $("#participantTypeWinner").change(function() {
	if($("#participantTypeWalkover").val()) {
	    $("#participantIdSelector").fadeIn();
	    $("#participantMatchSelector").fadeOut();
	}
    });
    $("#participantTypeLooser").change(function() {
	if($("#participantTypeWalkover").val()) {
	    $("#participantIdSelector").fadeIn();
	    $("#participantMatchSelector").fadeOut();
	}
    });
    
});
var matchParticipantTarget = 0;
function addParticipant(matchId) {
    $("#participantPrompt").fadeIn();
    matchParticipantTarget = matchId;
}

function addParticipantCallback() {
    var type = $('.participantType:checked').val();
    //console.log("Got type: " + type);
    if(type==participant_type_clan) {
	api_addParticipant(type, $('.participantClan:checked').val(), matchParticipantTarget);
    } else if(type == participant_type_winner || type == participant_type_looser) {
	api_addParticipant(type, $('#participantMatchId').val(), matchParticipantTarget);
    } else {
	api_addParticipant(type, 0, matchParticipantTarget);
    }
}

function showTimePrompt(onComplete) {
    $("#time-prompt").fadeIn();
    timePromptCallback = onComplete;
    
}
function timePromptFinished() {
    var days_split = $("#date_days").val().split("-");
    var hours_split = $("#date_hours").val().split(":");
    var d = new Date(parseInt(days_split[0]), parseInt(days_split[1])-1, parseInt(days_split[2]), parseInt(hours_split[0]), parseInt(hours_split[1]), parseInt(hours_split[2]));
    var unixtime = Math.floor(d.getTime() / 1000);
    timePromptCallback(unixtime);
    $("#time-prompt").fadeOut();
}
function addMatch(offset) {
    //Figure out when the user wants the bracket to be
    var time = 0;
    if(offset < bracket.generatedOffsets.length) {
	time = bracket.generatedOffsets[offset].scheduledTime;
	api_creatematch(compoId, time, "", offset);
    } else {
	showTimePrompt(function(time){
	    api_creatematch(compoId, time, "", offset);
	});
	
    }
}

//Api stuff
function api_creatematch(targetCompoId, scheduledTime, connectData, bracketOffset) {
    $.getJSON('../api/json/match/createMatch.php?id=' + encodeURIComponent(targetCompoId) + '&scheduledTime=' + encodeURIComponent(scheduledTime) + '&connectData=' + encodeURIComponent(connectData) + '&bracketOffset=' + encodeURIComponent(bracketOffset), function(data) {
	    if(data.result) {
		bracketSource.refresh();
	    } else {
		error(data.message);
	    }
	});

}

function api_addParticipant(matchType, id, targetMatchId) {
    console.log("Got match type: " + matchType + ", id " + id + ", targetMatch: " + targetMatchId);
    $.getJSON('../api/json/match/addParticipant.php?match=' + encodeURIComponent(targetMatchId) + "&type=" + encodeURIComponent(matchType) + "&id=" + encodeURIComponent(id), function(data){
	if(data.result) {
	    bracketSource.refresh();
	} else {
	    error(data.message);
	}
    });
}

function api_deleteMatch(id) {
    $.getJSON('../api/json/match/deleteMatch.php?id=' + id, function(data){
	if(data.result) {
	    bracketSource.refresh();
	} else {
	    error("Noe gikk galt: " + data.message);
	}
    });
}

function api_setTag(id, value) {
    $.getJSON('../api/json/match/setMetadata.php?id=' + id + '&key=tag&value=' + encodeURIComponent(value), function(data){
	if(data.result) {
	    bracketSource.refresh();
	} else {
	    error(data.message);
	}
    });
}


function getParticipantString(participant, clans) {
    if(participant.type == participant_type_clan) {
	for(var i = 0; i < clans.length; i++) {
	    if(clans[i].id == participant.id) {
		return clans[i].tag;
	    }
	}
    } else if(participant.type == participant_type_winner) {
	return "Winner of " + participant.participantId;
    } else if(participant.type == participant_type_looser) {
	return "Looser of " + participant.participantId;
    } else if(participant.type == participant_type_walkover) {
	return "Walkover";
    }
}
