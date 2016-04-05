var ws;
var wsOptions = {
    container: '#waveform',
    progressColor: '#00A1E5',
    height: 128,
    scrollParent: true,
    normalize: true,
    interact: true
};
var startSelect = "input[data-field='start']";
var endSelect = "input[data-field='end']";
var leftSelect = "input[data-field='leftTolerancy']";
var rightSelect = "input[data-field='rightTolerancy']";
var regionsContainer = $("#regions");
var audioResource = $('#ujm_exobundle_interactionaudiomarktype_audioResource');

$(document).ready(function () {
    ws = Object.create(WaveSurfer);
    ws.init(wsOptions);

    ws.on('region-update-end', function(region) {
       updateRegion(region);
    });

    ws.on('region-created', function(region) {
       createRegion(region);
    });

    ws.on('region-click', function(region) {
       highlightRegion(region.id);
    });

    ws.on('region-in', function(region) {
       highlightRegion(region.id);
    });

    ws.on('region-out', function(region) {
       hideRegion(region);
    });

    audioResource.change(function() {
        loadAudio();
    });

    loadAudio();
});


function loadAudio(){
    var nodeId = audioResource.val();
    if (nodeId){
        var audioURL = Routing.generate('claro_file_get_media', {node: nodeId});
        ws.load(audioURL);
            ws.enableDragSelection({
        });
        ws.on('ready', function () {
            var timeline = Object.create(WaveSurfer.Timeline);
            timeline.init({
                wavesurfer: ws,
                container: '#wave-timeline'
            });
            createSavedRegions();
        }); 
    }

    return;
}

function playPause() {
    ws.playPause();

    return;
}

function playRegion(regionId){
    var region = getRegion(regionId);
    var start = getStart(region);
    var end = getEnd(region);
    highlightRegion(regionId);
    ws.play(start, end);

    return;
}

function playWithTolerancy(regionId){
    var region = getRegion(regionId);
    var start = getStart(region);
    var end = getEnd(region);
    var left = getLeft(region);
    var right = getRight(region);
    var totalTime = ws.getDuration();
    highlightRegion(regionId);
    start = (start - left < 0) ? 0 : start - left;
    end = (end + right > totalTime) ? totalTime : end + right; 

    ws.play(start, end);
}

function removeRegions(all) {
    for (var index in ws.regions.list) {
        removeRegion(ws.regions.list[index].id);
    }
    regionsContainer.find("tbody").html("");

    return;
}

function createRegion(region){
    var regionHtml = getRegion(region.id);
    if (regionHtml.length == 0){
        var prototype = regionsContainer.attr("data-prototype");
        addAudioMarkForm(prototype, region);
    }

    return;
};

function updateRegion(region){
    var regionHtml = getRegion(region.id);

    setStart(regionHtml, region.start);
    setEnd(regionHtml, region.end);
    sortRegion();
    highlightRegion(region.id);

    return;
};

function removeRegion(regionId){
    var region = getRegion(regionId);
    for (var index in ws.regions.list) {
        if (ws.regions.list[index].id === regionId ) {
            ws.regions.list[index].remove();
            region.remove();
            break;
        };
    }

    return;
}

function createSavedRegions(){
    var regions = getRegions();

    regions.each(function( index ) {
        region = $(this);
        var start = getStart(region);
        var end = getEnd(region);
        var id = "audiomark-" + $(this).attr("data-mark-id");

        addBtns(id);

        var opt = {
            id: id,
            start: start,
            end: end 
        };

        ws.addRegion(opt);
    });
    sortRegion();

    return;
}

function sortRegion(){
    var regions = getRegions();

    regions.sort(function(a,b){
        var astart = getStart(a);
        var bstart = getStart(b);

        if(astart > bstart) {
            return 1;
        }
        if(astart < bstart) {
            return -1;
        }
        return 0;
    });

    regions.detach().appendTo(regionsContainer);    

    return;
}

function highlightRegion(regionId){
    var regions = getRegions();
    var region = getRegion(regionId);
    regions.css("background-color", "white");
    region.css("background-color", "#00A1E5");

    return;
}

function hideRegion(region){
    var regions = getRegions();
    var region = getRegion(region.id);
    region.css("background-color", "white");

    return;
}


/**************************
    DOM ELEMENTS GENERATION
***************************/

function addBtns(regionId){
    var region = getRegion(regionId);
    var playBtn = '<a data-region-id="'+regionId+'" class="btn btn-default btn-sm" onClick="event.preventDefault(); playRegion(\''+regionId+'\')" href="#"><span class="fa fa-play"></span> Play</a>';
    var PlayWithTolerancyBtn = '<a data-region-id="'+regionId+'" class="btn btn-default btn-sm" onClick="event.preventDefault(); playWithTolerancy(\''+regionId+'\')" href="#"><span class="fa fa-play"></span> Play with tolerancy</a>';
    var removeBtn = '<a data-region-id="'+regionId+'" class="btn btn-default btn-danger btn-sm" onClick="event.preventDefault(); removeRegion(\''+regionId+'\')" href="#"><span class="fa fa-trash"></span> Remove</a>';
    region.append("<td>"+ playBtn + "&nbsp;" + PlayWithTolerancyBtn + "&nbsp;" + removeBtn + "</td>");

    return;
}


function addAudioMarkForm(prototype, region){
    var newForm = prototype.replace(/__name__/g, regions.length);
    var newFormLi = $('<tr id="'+region.id+'" class="region"></tr>').append("<td>" + newForm + "</td>");
    regionsContainer.append(newFormLi);

    addBtns(region.id);

    var regionHtml = getRegion(region.id);
    setStart(regionHtml, region.start);
    setEnd(regionHtml, region.end);

    return;
}

/**************************
    UTILS
***************************/

function getRegion(regionId){
    return $("#"+regionId);
}

function getRegions(){
    return regionsContainer.find(".region");
}

function getStart(region){
    return Number($(region).find(startSelect).val());
}

function getEnd(region){
    return Number($(region).find(endSelect).val());
}

function getLeft(region){
    return Number($(region).find(leftSelect).val() / 1000);
}

function getRight(region){
    return Number($(region).find(rightSelect).val() / 1000);
}

function getEnd(region){
    return Number($(region).find(endSelect).val());
}


function setStart(region, start){
    return region.find(startSelect).val(start);
}

function setEnd(region, end){
    return region.find(endSelect).val(end)
}

