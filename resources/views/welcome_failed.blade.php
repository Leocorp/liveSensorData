<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SENSOR DATA</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
        
        <link type="text/css" rel="stylesheet" href="//pubnub.github.io/eon/v/eon/0.0.9/eon.css" />
    </head>
    <body class="container-fluid">
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    SENSOR DATA
                </div>

                <div class="links" style="background: red">
                    <a class="" style="color: white" href="">LDR</a>
                </div>
                <br>
                <div id="dbData"> 
	                <lux></lux>
                </div>
                <br>
                <template id="light_intensity_log">
	                <table class="table table-hover" border="1" cellspacing="1" cellpadding="10">
						<tr class="info">
							<td>&nbsp;<span style="font-weight: bold">Timestamp</span>&nbsp;</td>
							<td>&nbsp;<span style="font-weight: bold">Luminosity</span>&nbsp;</td>
						</tr>
						
						<tr v-for="data in ldr_data">
							<td>@{{ data.created_at }}</td>
							<td>@{{ data.luminosity }}</td>
						</tr>
								
				   </table>
                </template>
                <br>
          
                <div id="chart">
				  <div id="light"></div>
				</div>
            </div>
        </div>
	    <script src="//cdn.pubnub.com/pubnub-3.10.2.js"></script>
		<script src="//pubnub.github.io/eon/v/eon/0.0.9/eon.js"></script>
		<script src="https://unpkg.com/vue"></script>
		<script>
			var pubnub = PUBNUB.init({
			    publish_key:'pub-c-2f8fc330-a26c-4a2c-a09b-106329648eb4',
			    subscribe_key: 'sub-c-06e0b79c-aecd-11e7-b96f-72d21da71626'
			});
			var channel = 'lightpath';
			eon.chart({
			  channel: channel,
			  generate: {
			    bindto: '#light',
			    data: {
			      type: 'line',
			      colors: {
			        luminosity: '#663399'
			      }
			    },
			    axis: {
			      x: {
			        type: 'timeseries',
			        tick: {
			          format: '%H:%m:%S',
			          fit: true
			        },
			        label: {
			          text: 'Time',
			        }
			      },
			      y: {
			        label: {
			          text: 'Light Intensity',
			          position: 'outer-middle'
			        },
			        tick: {
			          format: function (d) {
			            var df = Number( d3.format('.2f')(d) );
			            return df;
			          }
			        }
			      }
			  }
			},
			//history:true,
			pubnub: pubnub,
			limit: 30,
			transform: function(m) {
			  return { eon: {
			      luminosity: m.luminosity
			    }}
			  }
			});
			
			
			Vue.component('lux',{
				template: '#light_intensity_log',
				
				data: function(){
					return {
						ldr_data: []
					}
				},
				
				created: function(){
					this.getLDRData();
				},
				
				methods: {
					getLDRData: function(){
						console.log('getLDRDATA');
						$.getJSON("{{ route('ldr') }}", function(ldr_data){
							this.ldr_data = ldr_data;
						}.bind(this));
					},
				}
				
			});
			new Vue({
				el: '#dbData',
				
			});
			
		</script>
    </body>
</html>
