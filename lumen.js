var PubNub = require('pubnub');

var channel = 'lightpath';
var lumen = 0;

function publish()
{
    pbnb = new PubNub({
        publish_key:'pub-c-2f8fc330-a26c-4a2c-a09b-106329648eb4',
        subscribe_key: 'sub-c-06e0b79c-aecd-11e7-b96f-72d21da71626'
    });
    
    var data = {
        'luminosity' : lumen
    };
    pbnb.publish({
        channel: channel,
        message: data
    });
}

var five = require("johnny-five"),
board, photoresistor;

board = new five.Board();

board.on("ready", function() {

// Create a new `photoresistor` hardware instance.
photoresistor = new five.Sensor({
  pin: "A0",
  freq: 250
});

// Inject the `sensor` hardware into
// the Repl instance's context;
// allows direct command line access
board.repl.inject({
  pot: photoresistor
});

// "data" get the current reading from the photoresistor
photoresistor.on("data", function() {
  lumen = this.value;
});

setInterval(publish, 500);

});



