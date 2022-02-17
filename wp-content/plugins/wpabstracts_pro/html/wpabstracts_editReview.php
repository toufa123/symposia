<div class="wpabstracts container-fluid">
	<h3>
		<?php echo apply_filters('wpabstracts_title_filter', __('Edit Review','wpabstracts'), 'edit_review'); ?>
		<button type="button" onclick="wpabstracts_validateReview();" class="wpabstracts btn btn-primary"><?php _e('Submit', 'wpabstracts'); ?></button>
	</h3>
	<form method="post" enctype="multipart/form-data" id="wpabs_review_form">
		<input type="hidden" name="abs_id" value="<?php echo $abstract->abstract_id; ?>" />
		<input type="hidden" name="abs_title" value="<?php echo $abstract->title; ?>" />
		<div class="wpabstracts row">
			<div class="wpabstracts col-xs-12 col-sm-12 col-md-8">
				<div class="wpabstracts panel panel-default">
					<div class="wpabstracts panel-heading">
						<strong><?php echo apply_filters('wpabstracts_title_filter', __('Abstract Information','wpabstracts'), 'abstract_information'); ?></strong>
					</div>
					<div class="wpabstracts panel-body">
						<p><strong><?php _e('Title','wpabstracts');?></strong>: <?php echo esc_attr(htmlspecialchars($abstract->title)); ?></p>
						<?php if (get_option('wpabstracts_show_description')) { ?>
							<p><strong><?php _e('Description','wpabstracts');?></strong>:</p>
							<?php echo wpautop(stripslashes($abstract->text)); ?>
						<?php } ?>
					</div>
				</div>
				<?php if (get_option('wpabstracts_show_keywords')) { ?>
					<div class="wpabstracts panel panel-default">
						<div class="wpabstracts panel-heading">
							<strong><?php echo apply_filters('wpabstracts_title_filter', __('Keywords','wpabstracts'), 'keywords'); ?></strong>
						</div>
						<div class="wpabstracts panel-body">
							<?php
							if($abstract->keywords){
								echo esc_attr(htmlspecialchars($abstract->keywords));
							}else{
								echo apply_filters('wpabstracts_title_filter', __('No keywords found', 'wpabstracts'), 'no_keywords');
							} ?>
						</div>
					</div>
				<?php } ?>
				<?php if (get_option('wpabstracts_show_author') && !get_option('wpabstracts_blind_review')) { ?>
					<div class="wpabstracts panel panel-default">
						<div class="wpabstracts panel-heading">
							<strong><?php echo apply_filters('wpabstracts_title_filter', __('Author Information','wpabstracts'), 'author_information'); ?></strong>
						</div>
						<div class="wpabstracts panel-body">
							<?php
							$authors_name = explode(" | ", $abstract->author);
							$authors_emails = explode(" | ", $abstract->author_email);
							$authors_affiliation = explode(" | ", $abstract->author_affiliation);
							foreach ($authors_name as $id => $key) {
								$authors[$key] = array(
									'name'  => $authors_name[$id],
									'email' => $authors_emails[$id],
									'affiliation' => $authors_affiliation[$id],
								);
							}
							foreach($authors as $author){ ?>
								<p><strong><?php echo apply_filters('wpabstracts_title_filter', __('Name','wpabstracts'), 'author_name');?></strong>: <?php echo esc_attr($author['name']);?></p>
								<p><strong><?php echo apply_filters('wpabstracts_title_filter', __('Email','wpabstracts'), 'author_email');?></strong>: <?php echo esc_attr($author['email']);?></p>
								<p><strong><?php echo apply_filters('wpabstracts_title_filter', __('Affiliation','wpabstracts'), 'author_affiliation'); ?></strong>: <?php echo esc_attr($author['affiliation']);?></p>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
				<?php if (get_option('wpabstracts_show_presenter') && !get_option('wpabstracts_blind_review')) { ?>
					<div class="wpabstracts panel panel-default">
						<div class="wpabstracts panel-heading">
							<strong><?php echo apply_filters('wpabstracts_title_filter', __('Presenter Information','wpabstracts'), 'presenter_information'); ?></strong>
						</div>
						<div class="wpabstracts panel-body">
                        <?php
							$presenter_names = explode(" | ", $abstract->presenter);
							$presenter_emails = explode(" | ", $abstract->presenter_email);
							$presenter_preferences = explode(" | ", $abstract->presenter_preference);
							foreach ($presenter_names as $id => $key) {
								$presenters[$key] = array(
									'name'  => $presenter_names[$id],
									'email' => $presenter_emails[$id],
									'preference' => $presenter_preferences[$id],
								);
							}
							foreach($presenters as $presenter){ ?>
								<p><strong><?php echo apply_filters('wpabstracts_title_filter', __('Name','wpabstracts'), 'presenter_name');?></strong>: <?php echo esc_attr($presenter['name']);?></p>
								<p><strong><?php echo apply_filters('wpabstracts_title_filter', __('Email','wpabstracts'), 'presenter_email');?></strong>: <?php echo esc_attr($presenter['email']); ?></p>
								<p><strong><?php echo apply_filters('wpabstracts_title_filter', __('Preference','wpabstracts'), 'presenter_preference');?></strong>: <?php echo esc_attr($presenter['preference']); ?></p>
							<?php } ?>
						</div>
					</div>
				<?php } ?>

				<div class="wpabstracts panel panel-default">
					<div class="wpabstracts panel-heading">
						<strong><?php echo apply_filters('wpabstracts_title_filter', __('Attachments','wpabstracts'), 'attachments'); ?></strong>
					</div>
					<div class="wpabstracts panel-body">
						<?php if (count($attachments) < 1) { ?>
							<p><?php echo apply_filters('wpabstracts_title_filter', __('No Attachments submitted', 'wpabstracts'), 'no_attachments');?></p>
						<?php }
						else{
							foreach($attachments as $attachment) { ?>
								<a href="?task=download&type=attachment&id=<?php echo $attachment->attachment_id; ?>">
								<?php echo $attachment->filename ?>
								<span class="wpabstracts glyphicon glyphicon-download-alt" aria-hidden="true"></span></a><br>
							<?php } ?>
						<?php } ?>
					</div>
				</div>

				<div class="wpabstracts panel panel-default">
					<div class="wpabstracts panel-heading">
						<strong><?php echo apply_filters('wpabstracts_title_filter', __('Add Comments','wpabstracts'), 'add_comments');?></strong>
					</div>
					<div class="wpabstracts panel-body" id="abs_review_comments_error">
						<?php
						$settings = array( 'media_buttons' => false, 'wpautop'=>true, 'dfw' => true, 'editor_height' => 90, 'quicktags' => true);
						wp_editor(stripslashes($review->comments), 'abs_comments', $settings);
						?>
					</div>
				</div>

				<?php if(get_option('wpabstracts_review_attachments')){ ?>
					<div class="wpabstracts panel panel-default">

						<div class="wpabstracts panel-heading">
							<h5><?php echo apply_filters('wpabstracts_title_filter', __('Reviewer Attachment','wpabstracts'), 'review_attachments');?></h5>
						</div>

						<div class="wpabstracts panel-body">

							<div class="wpabstracts form-group" id="review_attactments">
								<dd><?php echo apply_filters('wpabstracts_title_filter', __('Use this option to upload a document to this review.', 'wpabstracts'), 'reviewer_attachment_help'); ?></dd>
								<dd><?php echo apply_filters('wpabstracts_title_filter', __('Supported formats','wpabstracts'), 'supported_formats');?>: <strong><?php echo implode(' ', explode(' ', get_option('wpabstracts_permitted_attachments'))); ?></strong></dd>
								<dd><?php echo apply_filters('wpabstracts_title_filter', __('Maximum attachment size','wpabstracts'), 'max_attachment_size'); ?>: <strong><?php echo number_format((get_option('wpabstracts_max_attach_size') / 1048576)); ?>MB</strong></dd>

								<?php $review_atts = wpabstracts_get_review_attachment($review->review_id); ?>
								<?php if (!is_object($review_atts)) { ?>
									<dl class="wpabstracts form-group">
										<dd><input type="file" name="review_attachment"></dd>
									</dl>
								<?php } else { ?>
									<div id="review_attachment_<?php echo $review_atts->att_id;?>">
										<?php echo $review_atts->filename ?>
										<a class="wpabstracts btn btn-info" href="?task=download&type=review_attachment&id=<?php echo $review_atts->att_id; ?>">
										<span class="wpabstracts glyphicon glyphicon-download-alt" aria-hidden="true"></span>
										</a>
										<a class="wpabstracts btn btn-danger" href="javascript:wpabstracts_remove_review_attachment(<?php echo $review_atts->att_id; ?>)">
										<span class="wpabstracts glyphicon glyphicon-remove" aria-hidden="true"></span>
										</a>
									</div>
								<?php } ?>
							</div>
		
						</div>
					</div>
				<?php } ?>

			</div>

			<div class="wpabstracts col-xs-12 col-md-4">
				<div class="wpabstracts panel panel-default">
					<div class="wpabstracts panel-heading">
						<strong><?php _e('Relevance','wpabstracts');?></strong>
					</div>
					<div class="wpabstracts panel-body" id="abs_relevance_error">
						<div class="wpabstracts radio">
							<label class="wpabstracts radio">
								<input type='radio' name='abs_relevance' value='Excellent' <?php checked($review->relevance, "Excellent"); ?> /> <?php _e('Excellent', 'wpabstracts'); ?>
							</label>
							<label class="wpabstracts radio">
								<input type='radio' name='abs_relevance'  value='Good' <?php checked($review->relevance, "Good"); ?> /> <?php _e('Good', 'wpabstracts'); ?>
							</label>
							<label class="wpabstracts radio">
								<input type='radio' name='abs_relevance' value='Average' <?php checked($review->relevance, "Average"); ?> /> <?php _e('Average', 'wpabstracts'); ?>
							</label>
							<label class="wpabstracts radio">
								<input type='radio' name='abs_relevance' value='Poor' <?php checked($review->relevance, "Poor" ); ?> /> <?php _e('Poor', 'wpabstracts'); ?>
							</label>
						</div>
					</div>
				</div>

				<div class="wpabstracts panel panel-default">
					<div class="wpabstracts panel-heading">
						<strong><?php _e('Quality','wpabstracts');?></strong>
					</div>
					<div class="wpabstracts panel-body" id="abs_quality_error">
						<div class="wpabstracts radio">
							<label class="wpabstracts radio">
								<input type='radio' name='abs_quality' value='Excellent' <?php checked($review->quality, "Excellent"); ?> /> <?php _e('Excellent', 'wpabstracts'); ?>
							</label>
							<label class="wpabstracts radio">
								<input type='radio' name='abs_quality' value='Good' <?php checked($review->quality, "Good"); ?> /> <?php _e('Good', 'wpabstracts'); ?>
							</label>
							<label class="wpabstracts radio">
								<input type='radio' name='abs_quality' value='Average' <?php checked($review->quality,"Average"); ?> /> <?php _e('Average', 'wpabstracts'); ?>
							</label>
							<label class="wpabstracts radio">
								<input type='radio' name='abs_quality' value='Poor' <?php checked($review->quality, "Poor"); ?> /> <?php _e('Poor', 'wpabstracts'); ?>
							</label>
						</div>
					</div>
				</div>

				<div class="wpabstracts panel panel-default">
					<div class="wpabstracts panel-heading">
						<strong><?php _e('Suggest Type','wpabstracts');?></strong>
					</div>
					<div class="wpabstracts panel-body" id="abs_recommendation_error">
						<div class="wpabstracts radio">
							<span style="color:#999;">(<?php _e('Request', 'wpabstracts');?>: <?php echo $abstract->presenter_preference; ?>)</span><br>
							<?php
							$presenter_preference = explode(',', get_option('wpabstracts_presenter_preference'));
							foreach($presenter_preference as $preference){ ?>
								<label class="wpabstracts radio"><input type='radio' name='abs_recommendation' value='<?php echo $preference; ?>' <?php checked($review->recommendation, trim($preference)); ?> /> <?php echo $preference; ?></label>
							<?php } ?>
						</div>
					</div>
				</div>

				<div class="wpabstracts panel panel-default">
					<div class="wpabstracts panel-heading">
						<strong><?php _e('Suggest Status','wpabstracts');?></strong>
					</div>
					<div class="wpabstracts panel-body" id="abs_status_error">
						<div class="wpabstracts radio">
							<?php $statuses = wpabstracts_get_statuses(); ?>
							<?php foreach($statuses as $key => $status){ ?>
								<label class="wpabstracts radio"><input type='radio' name='abs_status' value='<?php echo $status->id;?>' <?php checked($review->status , $status->id)  ?> /> <?php echo $status->name;?></label>
							<?php } ?>
						</div>
					</div>
				</div>

				<div class="wpabstracts panel panel-default">
					<div class="wpabstracts panel-heading">
						<strong><?php _e('Additional Information','wpabstracts');?></strong>
					</div>
					<div class="wpabstracts panel-body">
						<div class="wpabstracts form-group">
							<p><strong><?php echo apply_filters('wpabstracts_title_filter', __('Submitted','wpabstracts'), 'submitted'); ?>:  </strong><?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($abstract->submit_date)); ?></p>
							<p><strong><?php echo apply_filters('wpabstracts_title_filter', __('Event','wpabstracts'), 'event');?>: </strong><?php echo $event->name; ?></p>
							<p><strong><?php echo apply_filters('wpabstracts_title_filter', __('Topic','wpabstracts'), 'topic'); ?>: </strong><?php echo $abstract->topic; ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
