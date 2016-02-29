/**
 * Exercise Parameters Controller
 * Manages edition of the parameters of the Exercise
 * @param {Object} exercise - The exercise to Edit
 * @constructor
 */
var ExerciseParametersCtrl = function ExerciseEditCtrl(exercise) {
    this.exercise = exercise;

    // Initialize TinyMCE
    var tinymce = window.tinymce;
    tinymce.claroline.init    = tinymce.claroline.init || {};
    tinymce.claroline.plugins = tinymce.claroline.plugins || {};

    var plugins = [
        'autoresize advlist autolink lists link image charmap print preview hr anchor pagebreak',
        'searchreplace wordcount visualblocks visualchars fullscreen',
        'insertdatetime media nonbreaking table directionality',
        'template paste textcolor emoticons code'
    ];
    var toolbar = 'undo redo | styleselect | bold italic underline | forecolor | alignleft aligncenter alignright | preview fullscreen';

    $.each(tinymce.claroline.plugins, function(key, value) {
        if ('autosave' != key &&  value === true) {
            plugins.push(key);
            toolbar += ' ' + key;
        }
    });

    for (var prop in tinymce.claroline.configuration) {
        if (tinymce.claroline.configuration.hasOwnProperty(prop)) {
            this.tinymceOptions[prop] = tinymce.claroline.configuration[prop];
        }
    }

    this.tinymceOptions.plugins = plugins;
    this.tinymceOptions.toolbar1 = toolbar;

    this.tinymceOptions.format = 'text';
};

// Set up dependency injection
ExerciseParametersCtrl.$inject = [ 'exercise' ];

/**
 * Tiny MCE options
 * @type {object}
 */
ExerciseParametersCtrl.prototype.tinymceOptions = {};

/**
 * The Exercise to edit
 * @type {Object}
 */
ExerciseParametersCtrl.prototype.exercise = null;

// Register controller into AngularJS
angular
    .module('Exercise')
    .controller('ExerciseParametersCtrl', ExerciseParametersCtrl);