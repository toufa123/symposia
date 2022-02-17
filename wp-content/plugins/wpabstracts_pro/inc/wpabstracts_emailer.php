<?php

class WPAbstracts_Emailer{

	protected $abstract = null;
	protected $user = null;
	protected $event = null;
	protected $template = null;
	protected $submitter = null;
	protected $reviews = null;

	public function __construct($aid, $user_id, $template_id) {
		global $wpdb;
		$wpdb->hide_errors();
		if($aid){
			$this->abstract = wpabstracts_get_abstract($aid);
			$this->reviews = wpabstracts_get_reviews('abstract_id', $aid);
		}
		if($this->abstract && $this->abstract->event){
			$this->event = wpabstracts_get_event($this->abstract->event);
		}
		if($user_id){
			$this->user = get_user_by('ID', $user_id);
		}
		if($template_id){
			$this->template = wpabstracts_get_email_template($template_id);
		}
		if($this->abstract->submit_by){
			$this->submitter = get_user_by('ID', $this->abstract->submit_by);
		}
	}

	private function format_reviews() {
		$reviews = "";
		foreach($this->reviews as $key => $review) {
			$reviews .= "<p>" . ++$key . "). " . stripslashes(wp_filter_nohtml_kses($review->comments)) . "</p>";
		}
		return $reviews;
	}

	private function filter($text){

		$keys = array(
			'{DISPLAY_NAME}',
			'{USERNAME}',
			'{USER_EMAIL}',
			'{ABSTRACT_ID}',
			'{ABSTRACT_TITLE}',
			'{ABSTRACT_KEYWORDS}',
			'{ABSTRACT_TOPIC}',
			'{SUBMITTER_NAME}',
			'{SUBMITTER_EMAIL}',
			'{EVENT_NAME}',
			'{EVENT_START}',
			'{EVENT_END}',
			'{PRESENTER_PREF}',
			'{REVIEW_COMMENTS}',
			'{SITE_NAME}',
			'{SITE_URL}',
			'{ONE_WEEK_LATER}',
			'{TWO_WEEKS_LATER}'
		);

		$display_name = $this->user ? $this->user->display_name : "";
		$user_login = $this->user ? $this->user->user_login : "";
		$user_email = $this->user ? $this->user->user_email: "";
		$abstract_id = $this->abstract ? $this->abstract->abstract_id : "";
		$abstract_title = $this->abstract ? $this->abstract->title : "";
		$abstract_keywords = $this->abstract ? $this->abstract->keywords : "";
		$abstract_topic = $this->abstract ? $this->abstract->topic : "";
		$submitter_name = $this->submitter ? $this->submitter->display_name : "";
		$submitter_email = $this->submitter  ? $this->submitter->user_login : "";
		$event_name = $this->event ? $this->event->name : "";
		$event_start = $this->event ? $this->event->start_date : "";
		$event_end = $this->event ? $this->event->end_date : "";
		$abstract_pref = $this->abstract ? $this->abstract->presenter_preference : "";
		$reviews = $this->reviews ? $this->format_reviews() : "";
		$site_name = get_option('blogname');
		$site_url = home_url();
		$one_week_later = date_i18n(get_option('date_format'), (60 * 60 * 24 * 7) + strtotime(current_time('mysql')));
		$two_weeks_later = date_i18n(get_option('date_format'), ((60 * 60 * 24 * 7) * 2) + strtotime(current_time('mysql')));

		$values = array(
			$display_name,
			$user_login,
			$user_email,
			$abstract_id,
			$abstract_title,
			$abstract_keywords,
			$abstract_topic,
			$submitter_name,
			$submitter_email,
			$event_name,
			$event_start,
			$event_end,
			$abstract_pref,
			$reviews,
			$site_name,
			$site_url,
			$one_week_later,
			$two_weeks_later
		);
		return str_replace($keys, $values, $text);
	}

	public function send(){
		$to = apply_filters('wpabstracts_emailer_to', $this->user->user_email, $this->abstract->abstract_id);
		$subject = apply_filters('wpabstracts_emailer_subject', $this->filter($this->template->subject), $this->abstract->abstract_id);
		$headers = apply_filters('wpabstracts_emailer_headers', __('From:', 'wpabstracts') . $this->template->from_name . " <" . $this->template->from_email . "> \r\n", $this->abstract->abstract_id);
		$message = apply_filters('wpabstracts_emailer_message', $this->filter(wpautop(stripslashes($this->template->message))), $this->abstract->abstract_id);
		// if template has include_submission enable, attach generated PDF as attachments 
		$attachment = '';
		if($this->template->include_submission) {
			$attachment = wpabstracts_download_pdf($this->abstract->abstract_id, $is_email = true);
		}
		add_filter('wp_mail_content_type', 'wpabstracts_set_html_content_type');
		$success = wp_mail($to, $subject, $message, $headers, array($attachment));
		remove_filter('wp_mail_content_type', 'wpabstracts_set_html_content_type');
		return $success;
	}

}
