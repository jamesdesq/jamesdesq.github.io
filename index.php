<!DOCTYPE html>
<html>
  <head>
    <title>My experiment</title>
    <script src="https://unpkg.com/jspsych@7.3.3"></script>
    <script src="https://unpkg.com/@jspsych/plugin-html-keyboard-response@1.1.2"></script>
    <script src="https://unpkg.com/@jspsych/plugin-image-keyboard-response@1.1.2"></script>
	<script src="https://unpkg.com/@jspsych/plugin-html-button-response@1.1.2"></script>
    <script src="https://unpkg.com/@jspsych/plugin-preload@1.1.2"></script>
    <link href="https://unpkg.com/jspsych@7.3.3/css/jspsych.css" rel="stylesheet" type="text/css" />
  </head>
  <body></body>
  <script>

    /* initialize jsPsych */
    var jsPsych = initJsPsych({
      on_finish: function() {
        jsPsych.data.displayData();
      }
    });

    /* create timeline */
    var timeline = [];

    
    var welcome = {
      type: jsPsychHtmlKeyboardResponse,
      stimulus: "Welcome to the experiment. Press any key to begin."
    };
    timeline.push(welcome);

    
    var instructions = {
      type: jsPsychHtmlKeyboardResponse,
      stimulus: `
        <p>In this task, first two random words will appear on the screen.</p>
		<p>These will then disappear and will be replaced by two buttons, one with a * in and one blank.</p>
		<p>Click the button with the * as fast as you can.</p>
        <p>Press any key to begin.</p>
      `,
      post_trial_gap: 2000
    };
    timeline.push(instructions);

	var fixation1 = {
      type: jsPsychHtmlKeyboardResponse,
      stimulus: '<div style="font-size:60px;">+</div>',
      choices: "NO_KEYS",
      trial_duration: function(){
        return jsPsych.randomization.sampleWithoutReplacement([250, 500, 750, 1000, 1250, 1500, 1750, 2000], 1)[0];
      },
      data: {
        task: 'fixation'
      },
      on_finish: function(data) {
        console.log("Has it cached or something?");
        console.log("I am the ", data);
        console.log(window.parent);

        window.parent.postMessage(data, "*");
      }
    };
	timeline.push(fixation1);
	
	var Prompt1 = {
      type: jsPsychHtmlKeyboardResponse,
      stimulus: '<div style="font-size:16px;">Fear &nbsp &nbsp &nbsp &nbsp &nbsp Sandwich</div>',
      choices: "NO_KEYS",
      trial_duration: 500,
    };
	timeline.push(Prompt1);

    var Question1 = {
	  type: jsPsychHtmlButtonResponse,
	  stimulus: '',
	  choices: [' &nbsp &nbsp &nbsp * &nbsp &nbsp &nbsp ', ' &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp '],
	  data: {
		condition: 'Anx',
		answer: 0
      },
	  on_finish: function(data){
		if(data.response == data.answer){
		data.correct = true;
		} else {
		data.correct = false;
        }
	  }
	};
	timeline.push(Question1);
	
	var fixation2 = {
      type: jsPsychHtmlKeyboardResponse,
      stimulus: '<div style="font-size:60px;">+</div>',
      choices: "NO_KEYS",
      trial_duration: function(){
        return jsPsych.randomization.sampleWithoutReplacement([250, 500, 750, 1000, 1250, 1500, 1750, 2000], 1)[0];
      },
      data: {
        task: 'fixation'
      }
    };
	timeline.push(fixation2);
	
	var Prompt2 = {
      type: jsPsychHtmlKeyboardResponse,
      stimulus: '<div style="font-size:16px;">Horror &nbsp &nbsp &nbsp &nbsp &nbsp Apples</div>',
      choices: "NO_KEYS",
      trial_duration: 500,
    };
	timeline.push(Prompt2);

    var Question2 = {
	  type: jsPsychHtmlButtonResponse,
	  stimulus: '',
	  choices: [' &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp ', ' &nbsp &nbsp &nbsp * &nbsp &nbsp &nbsp '],
	  data: {
		condition: 'Neu',
		answer: 1
      },
	  on_finish: function(data){
		if(data.response == data.answer){
      data.correct = true;
		} else {
		data.correct = false;
        }
	  }
	};
	timeline.push(Question2);
	
	var debrief_block = {
      type: jsPsychHtmlKeyboardResponse,
      stimulus: function() {
		  
		var AnxCor = 0;
		var AnxRT = 0;
		var NeuCor = 0;
		var NeuRT = 0;
		
		var AnxQs = jsPsych.data.get().filter({condition: 'Anx'});
        var AnxCorCal = AnxQs.filter({correct: true});
		var AnxCor = AnxCorCal.count();
		var NeuxQs = jsPsych.data.get().filter({condition: 'Neu'});
        var NeuCorCal = NeuxQs.filter({correct: true});
		var NeuCor = NeuCorCal.count();
        var AnxRT = Math.round(AnxQs.select('rt').mean());
		var NeuRT = Math.round(NeuxQs.select('rt').mean());

        window.parent.postMessage("finished", "*");
        return `<p>Anxiety Correct = ${AnxCor} and Anxiety RT = ${AnxRT}</p>
				<p>Neutral Correct = ${NeuCor} and Neutral RT = ${NeuRT}</p>`;

      }
    };
    timeline.push(debrief_block);

    window.addEventListener("load", (event) => {
      jsPsych.run(timeline);
      console.log("page is fully loaded");
    });

    /* start the experiment */

  </script>
</html>
