var ws;
var wsOptions = {
    container: '#waveform',
    waveColor: '#172B32', //#172B32',
    progressColor: '#00A1E5',
    height: 128,
    scrollParent: true,
    normalize: true
};

$(document).ready(function () {
    ws = Object.create(WaveSurfer);
    ws.init(wsOptions);

    ws.on('region-update-end', function(region) {
       updateRegion(region);
    });

    ws.on('region-created', function(region) {
       createRegion(region);
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
            color: 'rgba(255, 0, 0, 0.3)'
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

    $("#regions").html("");

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
    $("#"+region.id+" [data-field='start']").val(region.start);
    $("#"+region.id+" [data-field='end']").val(region.end);

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

    $("#"+region.id+" [data-field='start']").val(region.start);
    $("#"+region.id+" [data-field='end']").val(region.end);
}


function createSavedRegions(){   
    $("#regions .region").each(function( index ) {
        var start = $(this).find("input[data-field='start']").val();
        var end = $(this).find("input[data-field='end']").val();
        var id = "audiomark-" + $(this).attr("data-mark-id");

        var opt = {
            id: id,
            start: start,
            end: end 
        };

        ws.addRegion(opt);
    });
}