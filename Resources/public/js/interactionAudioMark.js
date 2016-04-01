var ws;
var wsOptions = {
    container: '#waveform',
    progressColor: '#00A1E5',
    height: 128,
    scrollParent: true,
    normalize: true
};
var startSelect = "input[data-field='start']";
var endSelect = "input[data-field='end']"

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
       showRegion(region);
    });

    ws.on('region-in', function(region) {
       showRegion(region);
    });

    ws.on('region-out', function(region) {
       hideRegion(region);
    });

    loadAudio();

    
});

$('#ujm_exobundle_interactionaudiomarktype_audioResource').change(function() {
    loadAudio();
})


$( "#regions" ).on( "click", ".remove-region", function() {
    var regionId = $(this).parent().attr("id");
    removeRegion(regionId);
});

function loadAudio(){
    var audioResource = $("#ujm_exobundle_interactionaudiomarktype_audioResource");
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

function removeRegions(all) {
    for (var index in ws.regions.list) {
        removeRegion(ws.regions.list[index].id);
    }

    $("#regions tbody").html("");

    return;
}

function createRegion(region){
    if ( $( "#"+region.id ).length == 0 ) {
        var collection = $("#regions");
        var prototype = collection.attr("data-prototype");
        addAudioMarkForm(prototype, collection, region);
    }

    return;
};

function updateRegion(region){
    $("#"+region.id + " " + startSelect).val(region.start);
    $("#"+region.id + " " + endSelect).val(region.end);

    sortRegion();

    showRegion(region);

    return;
};

function removeRegion(regionId){
    for (var index in ws.regions.list) {
        if (ws.regions.list[index].id === regionId ) {
            ws.regions.list[index].remove();
            $("#"+regionId).remove();
            break;
        };
    }

    return;
}

function addAudioMarkForm(prototype, collection, region){
    var newForm = prototype.replace(/__name__/g, collection.find(".region").length);
    var newFormLi = $('<tr id="'+region.id+'" class="region"></tr>').append("<td>" + newForm + "</td>");
    collection.append(newFormLi);

    addRemoveBtn(region.id);

    $("#"+region.id + " " + startSelect).val(region.start);
    $("#"+region.id + " " + endSelect).val(region.end);
}


function createSavedRegions(){   
    $("#regions .region").each(function( index ) {
        var start = $(this).find(startSelect).val();
        var end = $(this).find(endSelect).val();
        var id = "audiomark-" + $(this).attr("data-mark-id");

        addRemoveBtn(id);

        var opt = {
            id: id,
            start: start,
            end: end 
        };

        ws.addRegion(opt);
    });

    sortRegion();
}


function sortRegion(){
    var regionsContainer = $("#regions");
    var regions = regionsContainer.find(".region");

    regions.sort(function(a,b){
        var astart = Number($(a).find(startSelect).val());
        var bstart = Number($(b).find(startSelect).val());

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

function showRegion(region){
    $(".region").css("background-color", "white");
    $("#"+region.id).css("background-color", "#00A1E5");
}

function hideRegion(region){
    $("#"+region.id).css("background-color", "white");
}

function addRemoveBtn(regionId) {
    var btn = $('<a data-region-id="'+regionId+'" class="btn btn-default btn-xs" onClick="event.preventDefault(); removeRegion(\''+regionId+'\')" href="#"><span class="fa fa-trash"></span> remove</a>');
    $("#"+regionId).append(btn);
}