<?

/* ================================================================================================================== */
/* DBO CLASS FILE FOR MODULE 'config' =========================================== AUTO-CREATED ON 07/12/2014 14:23:48 */
/* ================================================================================================================== */

/* IMPORTANT: This file is generated only in the first DBO sync, what means you should edit only via text editor. */

if(!class_exists('config'))
{
	class config extends dbo
	{
		/* smart constructor: will perform load() upon numeric argument and loadAll() upon string argument */
		function __construct($foo = '')
		{
			parent::__construct('config');
			if($foo != '')
			{
				if(is_numeric($foo))
				{
					$this->id = $foo;
					$this->load();
				}
				elseif(is_string($foo))
				{
					$this->loadAll($foo);
				}
			}
		}

		//your methods here

		function getBreadcrumbIdentifier()
		{
			return "Alterar";
		}

		function getSiteTitulo()
		{
			return $this->site_titulo;
		}

		static function init()
		{
			global $_conf;
			if(!is_object($_conf))
			{
				$_conf = new config(1);
			}
		}

		static function renderMap($params = array())
		{
			extract($params);

			$latitude = $latitude ? $latitude : siteConfig()->latitude;
			$longitude = $longitude ? $longitude : siteConfig()->longitude;
			$map_marker_texto = $map_marker_texto ? $map_marker_texto : siteConfig()->map_marker_texto;
			$height = $height ? $height : '500px';

			ob_start();
			?>
			<div id="map-canvas" style="height: <?= $height ?>;"></div>
			<script>
				var directionDisplay;
				var directionsService = new google.maps.DirectionsService();
				var route = false;
				var map;
				var marker;
				var geocoder;

				function initializeMap(latitude, longitude, label_content) {

					if(typeof $('#map-canvas')[0] != 'undefined'){

						directionsDisplay = new google.maps.DirectionsRenderer();
						geocoder = new google.maps.Geocoder();

						var mapOptions = {
							center: new google.maps.LatLng(latitude, longitude),
							zoom: 16,
							mapTypeId: google.maps.MapTypeId.ROADMAP,
							scrollwheel: false,
							disableDefaultUI: true
						};

						map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
						directionsDisplay.setMap(map);
						directionsDisplay.setPanel(document.getElementById("directions-panel"));

						var icon = 'images/marker.png';

						var marker = new MarkerWithLabel({
							icon: icon,
							position: map.getCenter(),
							map: map,
							draggable: false,
							labelContent: label_content,
							labelAnchor: new google.maps.Point(-40, 60),
							labelClass: "map-label", // the CSS class for the label
							labelInBackground: true
						});

					}
				}

				function calcRoute() {
					if (marker) marker.setMap(null);
					route = true;
					var start = document.getElementById("rota-inicio").value;
					var end = document.getElementById("rota-termino").value;
					console.log(start);
					console.log(end);
					var request = {
					   origin:start,
					   destination:end,
					   travelMode: google.maps.DirectionsTravelMode.DRIVING
					};

					directionsService.route(request, function(response, status) {
						console.log(google.maps.DirectionsStatus);
					 if (status == google.maps.DirectionsStatus.OK) {
					   directionsDisplay.setDirections(response);
					 }
				   });
				}

				$(document).ready(function(){
					initializeMap('<?= $latitude ?>', '<?= $longitude ?>', '<?= $map_marker_texto ?>');
				}) //doc.ready

			</script>
			<?php
			return ob_get_clean();
		}

		static function siteMediaShareFunctions()
		{
			ob_start();
			?>
			<script>
				function shareTwitter(url, text) {
					open('http://twitter.com/share?url=' + url + '&text=' + text, 'tshare', 'height=400,width=550,resizable=1,toolbar=0,menubar=0,status=0,location=0');
				}

				function shareFacebook(url, text, image) {
					open('http://facebook.com/sharer.php?s=100&p[url]=' + url + '&p[images][0]=' + image + '&p[title]=' + text, 'fbshare', 'height=380,width=660,resizable=0,toolbar=0,menubar=0,status=0,location=0,scrollbars=0');
				}

				function shareGooglePlus(url) {
					open('https://plus.google.com/share?url=' + url, 'gshare', 'height=270,width=630,resizable=0,toolbar=0,menubar=0,status=0,location=0,scrollbars=0');
				}
			</script>
			<?php
			return ob_get_clean();
		}

		function renderSocialItems($params = array())
		{
			/* Params:
			   - fixed_width: true | false - os icones são mostrados com larguras exatas
			*/
			global $_system;
			extract($params);

			$social_items = array(
				'facebook' => array(
					'icon' => 'facebook',
				),
				'twitter' => array(
					'icon' => 'twitter',
				),
				'instagram' => array(
					'icon' => 'instagram',
				),
				'youtube' => array(
					'icon' => 'youtube-play',
					'title' => 'YouTube',
				),
				'linkedin' => array(
					'icon' => 'linkedin',
					'title' => 'LinedIn',
				),
				'google_plus' => array(
					'icon' => 'google-plus',
					'title' => 'Google+',
				),
			);

			$_system['site_config']['social_items'] = array_merge($social_items, (array)$_system['site_config']['social_items']);

			$midias_sociais = siteConfig()->_midias_sociais->decode();

			$return = '';
			foreach($_system['site_config']['social_items'] as $item => $data)
			{
				if(strlen(trim($midias_sociais[$item]['url']))) //checa se existe este campo preenchido na configuração atual
				{
					$slug = str_replace("_", "-", $item);
					$title = $data['title'] ? $data['title'] : ucfirst($slug);
					$icon = $data['icon'] ? $data['icon'] : $slug;
					$url = $data['url'] ? $data['url'] : $midias_sociais[$item]['url'];
					$return .= '<li class="'.$slug.'"><a href="'.$url.'" target="_blank"><i class="fa '.($fixed_width ? 'fa-fw' : '').' fa-'.$icon.'"></i><span>'.$title.'</span></a></li>';

				}
			}
			return $return;
		}

	} //class declaration
} //if ! class exists

?>
