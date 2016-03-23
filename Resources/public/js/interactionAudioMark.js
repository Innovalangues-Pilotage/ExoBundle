$( document ).ready(function() {
    loadAudio();
});

$('#ujm_exobundle_interactionaudiomarktype_audioResource').change(function() {
    loadAudio();
})

function loadAudio()
{
    var audioResource = $("#ujm_exobundle_interactionaudiomarktype_audioResource");
    var nodeId = audioResource.val();
    if (nodeId){
        var audioURL = Routing.generate('claro_file_get_media', {node: nodeId});
        var audio = $("#audio-resource");
        var source = $("#audio-resource-source");
        source.attr("src", audioURL);
        audio.load();
        $("#audio-resource").show();
    } else {
        $("#audio-resource").hide();
    }
}