var wavesurfer;

var wavesurferOptions = {
    container: '#waveform',
    waveColor: '#02f', //#172B32',
    progressColor: '#00A1E5',
    height: 128,
    scrollParent: true,
    normalize: true
};

$(document).ready(function () {
    wavesurfer = Object.create(WaveSurfer);
    wavesurfer.init(wavesurferOptions);
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
        
        wavesurfer.load(audioURL);
            wavesurfer.enableDragSelection({
            color: 'rgba(255, 0, 0, 0.1)'
         });

        wavesurfer.on('ready', function () {
            var timeline = Object.create(WaveSurfer.Timeline);
            timeline.init({
                wavesurfer: wavesurfer,
                container: '#wave-timeline'
            });
        }); 
    }
}

function playPause() 
{
    wavesurfer.playPause();
}


function removeRegions(all) 
{
    for (var index in wavesurfer.regions.list) {
        wavesurfer.regions.list[index].remove();
    }
    // if command from delete region button
    if (all) {
        studentRegions = {};
        teacherRegions = {};
    }
}