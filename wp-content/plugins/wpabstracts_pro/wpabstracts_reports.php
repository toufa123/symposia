<?php defined('ABSPATH') or die("ERROR: You do not have permission to access this page"); 

$events = wpabstracts_get_events();
?>


<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load("visualization", "1", {packages:["corechart"]});

	function drawAbstractsReport(stats) {

		var preData = [
			['<?php _e('Status', 'wpabstracts'); ?>', 'Status']
		];

		stats.results.forEach(function(stat){
			preData.push([stat.name, parseInt(stat.count, 10)]);
		});

		var data = google.visualization.arrayToDataTable(preData);

		var options = {
			hAxis: {title: '<?php _e('STATUS', 'wpabstracts'); ?>', titleTextStyle: {color: 'black'}},
			vAxis: {title: '<?php _e('COUNT', 'wpabstracts'); ?>', titleTextStyle: {color: 'black'}, maxValue: parseInt(stats.total, 10) + 1, format: '#' }
		};

		var chart = new google.visualization.ColumnChart(document.getElementById('wpabstracts_status_report'));
		chart.draw(data, options);
	}

	function drawReviewsReport(stats) {

		var preData = [
			['<?php _e('Status', 'wpabstracts'); ?>', 'Status']
		];

		stats.results.forEach(function(stat){
			preData.push([stat.name, parseInt(stat.count, 10)]);
		});

		var data = google.visualization.arrayToDataTable(preData);

		var options = {
			hAxis: {title: '<?php _e('STATUS', 'wpabstracts'); ?>', titleTextStyle: {color: 'black'}},
			vAxis: {title: '<?php _e('COUNT', 'wpabstracts'); ?>', titleTextStyle: {color: 'black'}, maxValue: parseInt(stats.total, 10) + 1, format: '#' }
		};

		var chart = new google.visualization.ColumnChart(document.getElementById('wpabstracts_review_report'));
		chart.draw(data, options);
	}

	function changeEvent(eventId) {
		var data = {
			action: 'export_event_stats',
			event_id: parseInt(eventId, 10)
		};
		jQuery.post(wpabstracts.ajaxurl, data).done(function (_data) {
			
			const data = JSON.parse(_data);
			
			drawAbstractsReport(data.abstracts);
			
			drawReviewsReport(data.reviews);

			// display abstracts exports
			var abstract_export = jQuery("#abstract_export").html("");
			data.abstracts.results.forEach(function(status) {
				var statusLink = jQuery("<a>" + status['name'] + " ("+status['count'] +")</a>");
				statusLink.attr("href", "?page=wpabstracts&tab=reports&task=download&type=abstracts&status="+status['id']+"&event="+eventId);
				jQuery("<p></p>").html(statusLink).appendTo(abstract_export);
			});
			var allAbstractLink = jQuery("<a><strong>All Abstracts (" + data.abstracts.total + ")</strong></a>");
			allAbstractLink.attr("href", "?page=wpabstracts&tab=reports&task=download&type=abstracts&status=-1&event="+eventId);
			jQuery("<p></p>").html(allAbstractLink).appendTo(abstract_export);

			// display review exports
			var review_export = jQuery("#review_export").html("");
			data.reviews.results.forEach(function(status) {
				var statusLink = jQuery("<a>" + status['name'] + " ("+status['count'] +")</a>");
				statusLink.attr("href", "?page=wpabstracts&tab=reports&task=download&type=reviews&status="+status['id']+"&event="+eventId);
				jQuery("<p></p>").html(statusLink).appendTo(review_export);
			});
			var allReviewLink = jQuery("<a><strong>All Review (" + data.reviews.total + ")</strong></a>");
			allReviewLink.attr("href", "?page=wpabstracts&tab=reports&task=download&type=reviews&status=-1&event="+eventId);
			jQuery("<p></p>").html(allReviewLink).appendTo(review_export);
		
		});
	}
</script>
<br>	

<div class="wpabstracts container-fluid wpabstracts-admin-container">

    <div class="wpabstracts row">

        <div class="wpabstracts col-xs-12 col-md-9">
            <div class="wpabstracts panel panel-primary">
                <div class="wpabstracts panel panel-heading">
                    <h4><?php _e('Abstracts by Status', 'wpabstracts');?></h4>
                </div>
                <div class="wpabstracts panel panel-body">
                    <div id="wpabstracts_status_report"></div>
                </div>
            </div>

             <div class="wpabstracts panel panel-primary">
                <div class="wpabstracts panel-heading">
                    <h4><?php _e('Reviews by Status', 'wpabstracts');?></h4>
                </div>
                <div class="wpabstracts panel-body">
                    <div id="wpabstracts_review_report"></div>
                </div>
            </div>
        </div>

        <div class="wpabstracts col-xs-12 col-md-3">

			<div class="wpabstracts panel panel-primary">
                <div class="wpabstracts panel-heading">
                    <h4><?php _e('Select an Event', 'wpabstracts');?></h4>
                </div>
                <div class="wpabstracts panel-body">
					<select name="report_event" id="report_event" class="wpabstracts form-control" onchange="changeEvent(this.value)">
						<option value="" style="display:none;"><?php echo apply_filters('wpabstracts_title_filter', __('Select an Event','wpabstracts'), 'select_event');?></option>
						<?php foreach($events as $event){ ?>
							<option value="<?php echo esc_attr($event->event_id);?>"><?php echo esc_attr($event->name);?></option>
						<?php } ?>
					</select>
                </div>
            </div>

            <div class="wpabstracts panel panel-primary">
                <div class="wpabstracts panel-heading">
                    <h4><?php _e('Abstracts by Status', 'wpabstracts');?></h4>
                </div>
                <div class="wpabstracts panel-body" id="abstract_export"></div>
            </div>

            <div class="wpabstracts panel panel-primary">
                <div class="wpabstracts panel-heading">
                    <h4><?php _e('Reviews by Status', 'wpabstracts');?></h4>
                </div>
				<div class="wpabstracts panel-body" id="review_export"></div>
            </div>
        </div>
    </div>
</div>
