const allGoogleIcons = [
  "123",
  "3d_rotation",
  "abc",
  "accessibility",
  "accessibility_new",
  "accessible",
  "accessible_forward",
  "account_balance",
  "account_balance_wallet",
  "account_box",
  "account_circle",
  "add_card",
  "add_shopping_cart",
  "add_task",
  "add_to_drive",
  "addchart",
  "admin_panel_settings",
  "ads_click",
  "alarm",
  "alarm_add",
  "alarm_off",
  "alarm_on",
  "all_inbox",
  "all_out",
  "analytics",
  "anchor",
  "android",
  "announcement",
  "api",
  "app_blocking",
  "app_shortcut",
  "arrow_circle_down",
  "arrow_circle_left",
  "arrow_circle_right",
  "arrow_circle_up",
  "arrow_right_alt",
  "article",
  "aspect_ratio",
  "assessment",
  "assignment",
  "assignment_ind",
  "assignment_late",
  "assignment_return",
  "assignment_returned",
  "assignment_turned_in",
  "assured_workload",
  "autorenew",
  "backup",
  "backup_table",
  "balance",
  "batch_prediction",
  "book",
  "book_online",
  "bookmark",
  "bookmark_add",
  "bookmark_added",
  "bookmark_border",
  "bookmark_remove",
  "bookmarks",
  "bug_report",
  "build",
  "build_circle",
  "cached",
  "calendar_today",
  "calendar_view_day",
  "calendar_view_month",
  "calendar_view_week",
  "camera_enhance",
  "cancel_schedule_send",
  "card_giftcard",
  "card_membership",
  "card_travel",
  "change_history",
  "check_circle",
  "check_circle_outline",
  "chrome_reader_mode",
  "circle_notifications",
  "class",
  "close_fullscreen",
  "code",
  "code_off",
  "comment_bank",
  "commit",
  "commute",
  "compare_arrows",
  "compress",
  "contact_page",
  "contact_support",
  "contactless",
  "copyright",
  "credit_card",
  "credit_card_off",
  "css",
  "currency_exchange",
  "dangerous",
  "dashboard",
  "dashboard_customize",
  "data_exploration",
  "data_thresholding",
  "date_range",
  "delete",
  "delete_forever",
  "delete_outline",
  "density_large",
  "density_medium",
  "density_small",
  "description",
  "disabled_by_default",
  "disabled_visible",
  "display_settings",
  "dns",
  "done",
  "done_all",
  "done_outline",
  "donut_large",
  "donut_small",
  "drag_indicator",
  "dynamic_form",
  "eco",
  "edit_calendar",
  "edit_off",
  "eject",
  "euro_symbol",
  "event",
  "event_repeat",
  "event_seat",
  "exit_to_app",
  "expand",
  "explore",
  "explore_off",
  "extension",
  "extension_off",
  "face",
  "face_unlock",
  "fact_check",
  "favorite",
  "favorite_border",
  "fax",
  "feedback",
  "file_present",
  "filter_alt",
  "filter_alt_off",
  "filter_list_alt",
  "find_in_page",
  "find_replace",
  "fingerprint",
  "fit_screen",
  "flaky",
  "flight_land",
  "flight_takeoff",
  "flip_to_back",
  "flip_to_front",
  "flutter_dash",
  "free_cancellation",
  "g_translate",
  "gavel",
  "generating_tokens",
  "get_app",
  "gif",
  "gif_box",
  "grade",
  "grading",
  "group_work",
  "help",
  "help_center",
  "help_outline",
  "hide_source",
  "highlight_alt",
  "highlight_off",
  "history",
  "history_toggle_off",
  "hls",
  "hls_off",
  "home",
  "home_filled",
  "horizontal_split",
  "hotel_class",
  "hourglass_disabled",
  "hourglass_empty",
  "hourglass_full",
  "html",
  "http",
  "https",
  "important_devices",
  "info",
  "info_outline",
  "input",
  "install_desktop",
  "install_mobile",
  "integration_instructions",
  "invert_colors",
  "javascript",
  "join_full",
  "join_inner",
  "join_left",
  "join_right",
  "label",
  "label_important",
  "label_important_outline",
  "label_off",
  "label_outline",
  "language",
  "launch",
  "leaderboard",
  "lightbulb",
  "lightbulb_outline",
  "line_style",
  "line_weight",
  "list",
  "lock",
  "lock_clock",
  "lock_open",
  "lock_outline",
  "lock_reset",
  "login",
  "logout",
  "loyalty",
  "manage_accounts",
  "mark_as_unread",
  "markunread_mailbox",
  "maximize",
  "mediation",
  "minimize",
  "model_training",
  "new_label",
  "next_plan",
  "nightlight_round",
  "no_accounts",
  "not_accessible",
  "not_started",
  "note_add",
  "offline_bolt",
  "offline_pin",
  "online_prediction",
  "opacity",
  "open_in_browser",
  "open_in_full",
  "open_in_new",
  "open_in_new_off",
  "open_with",
  "outbond",
  "outbound",
  "outbox",
  "outlet",
  "pageview",
  "paid",
  "pan_tool",
  "pan_tool_alt",
  "payment",
  "pending",
  "pending_actions",
  "percent",
  "perm_camera_mic",
  "perm_contact_calendar",
  "perm_data_setting",
  "perm_device_information",
  "perm_identity",
  "perm_media",
  "perm_phone_msg",
  "perm_scan_wifi",
  "pets",
  "php",
  "picture_in_picture",
  "picture_in_picture_alt",
  "pin_end",
  "pin_invoke",
  "pinch",
  "plagiarism",
  "play_for_work",
  "polymer",
  "power_settings_new",
  "pregnant_woman",
  "preview",
  "print",
  "privacy_tip",
  "private_connectivity",
  "production_quantity_limits",
  "published_with_changes",
  "query_builder",
  "question_answer",
  "question_mark",
  "quickreply",
  "receipt",
  "record_voice_over",
  "redeem",
  "remove_done",
  "remove_shopping_cart",
  "reorder",
  "report_problem",
  "request_page",
  "restore",
  "restore_from_trash",
  "restore_page",
  "rocket",
  "rocket_launch",
  "room",
  "rounded_corner",
  "rowing",
  "rule",
  "satellite_alt",
  "saved_search",
  "savings",
  "schedule",
  "schedule_send",
  "search",
  "search_off",
  "segment",
  "send_and_archive",
  "sensors",
  "sensors_off",
  "settings",
  "settings_accessibility",
  "settings_applications",
  "settings_backup_restore",
  "settings_bluetooth",
  "settings_brightness",
  "settings_cell",
  "settings_ethernet",
  "settings_input_antenna",
  "settings_input_component",
  "settings_input_composite",
  "settings_input_hdmi",
  "settings_input_svideo",
  "settings_overscan",
  "settings_phone",
  "settings_power",
  "settings_remote",
  "settings_voice",
  "shop",
  "shop_2",
  "shop_two",
  "shopping_bag",
  "shopping_basket",
  "shopping_cart",
  "shopping_cart_checkout",
  "smart_button",
  "source",
  "space_dashboard",
  "speaker_notes",
  "speaker_notes_off",
  "spellcheck",
  "star_rate",
  "stars",
  "sticky_note_2",
  "store",
  "subject",
  "subtitles_off",
  "supervised_user_circle",
  "supervisor_account",
  "support",
  "swap_horiz",
  "swap_horizontal_circle",
  "swap_vert",
  "swap_vertical_circle",
  "swipe",
  "swipe_down",
  "swipe_down_alt",
  "swipe_left",
  "swipe_left_alt",
  "swipe_right",
  "swipe_right_alt",
  "swipe_up",
  "swipe_up_alt",
  "swipe_vertical",
  "switch_access_shortcut",
  "switch_access_shortcut_add",
  "sync_alt",
  "system_update_alt",
  "tab",
  "tab_unselected",
  "table_view",
  "task_alt",
  "terminal",
  "text_rotate_up",
  "text_rotate_vertical",
  "text_rotation_angledown",
  "text_rotation_angleup",
  "text_rotation_down",
  "text_rotation_none",
  "theaters",
  "thumb_down",
  "thumb_down_off_alt",
  "thumb_up",
  "thumb_up_off_alt",
  "thumbs_up_down",
  "timeline",
  "tips_and_updates",
  "toc",
  "today",
  "token",
  "toll",
  "touch_app",
  "tour",
  "track_changes",
  "translate",
  "trending_down",
  "trending_flat",
  "trending_up",
  "try",
  "turned_in",
  "turned_in_not",
  "unpublished",
  "update",
  "update_disabled",
  "upgrade",
  "verified",
  "verified_user",
  "vertical_split",
  "view_agenda",
  "view_array",
  "view_carousel",
  "view_column",
  "view_comfy_alt",
  "view_compact_alt",
  "view_cozy",
  "view_day",
  "view_headline",
  "view_in_ar",
  "view_kanban",
  "view_list",
  "view_module",
  "view_quilt",
  "view_sidebar",
  "view_stream",
  "view_timeline",
  "view_week",
  "visibility",
  "visibility_off",
  "voice_over_off",
  "watch_later",
  "webhook",
  "wifi_protected_setup",
  "work",
  "work_off",
  "work_outline",
  "wysiwyg",
  "youtube_searched_for",
  "zoom_in",
  "zoom_out",
  "add_alert",
  "auto_delete",
  "error",
  "error_outline",
  "notification_important",
  "warning",
  "warning_amber",
  "10k",
  "1k",
  "1k_plus",
  "2k",
  "2k_plus",
  "3k",
  "3k_plus",
  "4k",
  "4k_plus",
  "5g",
  "5k",
  "5k_plus",
  "6k",
  "6k_plus",
  "7k",
  "7k_plus",
  "8k",
  "8k_plus",
  "9k",
  "9k_plus",
  "add_to_queue",
  "airplay",
  "album",
  "art_track",
  "audio_file",
  "av_timer",
  "branding_watermark",
  "call_to_action",
  "closed_caption",
  "closed_caption_disabled",
  "closed_caption_off",
  "control_camera",
  "equalizer",
  "explicit",
  "fast_forward",
  "fast_rewind",
  "featured_play_list",
  "featured_video",
  "fiber_dvr",
  "fiber_manual_record",
  "fiber_new",
  "fiber_pin",
  "fiber_smart_record",
  "forward_10",
  "forward_30",
  "forward_5",
  "games",
  "hd",
  "hearing",
  "hearing_disabled",
  "high_quality",
  "interpreter_mode",
  "library_add",
  "library_add_check",
  "library_books",
  "library_music",
  "loop",
  "mic",
  "mic_none",
  "mic_off",
  "missed_video_call",
  "movie",
  "music_video",
  "new_releases",
  "not_interested",
  "note",
  "pause",
  "pause_circle",
  "pause_circle_filled",
  "pause_circle_outline",
  "play_arrow",
  "play_circle",
  "play_circle_filled",
  "play_circle_outline",
  "play_disabled",
  "playlist_add",
  "playlist_add_check",
  "playlist_add_check_circle",
  "playlist_add_circle",
  "playlist_play",
  "playlist_remove",
  "queue",
  "queue_music",
  "queue_play_next",
  "radio",
  "recent_actors",
  "remove_from_queue",
  "repeat",
  "repeat_on",
  "repeat_one",
  "repeat_one_on",
  "replay",
  "replay_10",
  "replay_30",
  "replay_5",
  "replay_circle_filled",
  "sd",
  "shuffle",
  "shuffle_on",
  "skip_next",
  "skip_previous",
  "slow_motion_video",
  "snooze",
  "sort_by_alpha",
  "speed",
  "stop",
  "stop_circle",
  "subscriptions",
  "subtitles",
  "surround_sound",
  "video_call",
  "video_file",
  "video_label",
  "video_library",
  "video_settings",
  "videocam",
  "videocam_off",
  "volume_down",
  "volume_down_alt",
  "volume_mute",
  "volume_off",
  "volume_up",
  "web",
  "web_asset",
  "web_asset_off",
  "3p",
  "add_ic_call",
  "alternate_email",
  "app_registration",
  "business",
  "call",
  "call_end",
  "call_made",
  "call_merge",
  "call_missed",
  "call_missed_outgoing",
  "call_received",
  "call_split",
  "cancel_presentation",
  "cell_tower",
  "cell_wifi",
  "chat",
  "chat_bubble",
  "chat_bubble_outline",
  "clear_all",
  "co_present",
  "comment",
  "comments_disabled",
  "contact_mail",
  "contact_phone",
  "contacts",
  "desktop_access_disabled",
  "dialer_sip",
  "dialpad",
  "document_scanner",
  "domain_disabled",
  "domain_verification",
  "duo",
  "email",
  "forum",
  "forward_to_inbox",
  "hourglass_bottom",
  "hourglass_top",
  "hub",
  "import_contacts",
  "import_export",
  "invert_colors_off",
  "key",
  "key_off",
  "list_alt",
  "live_help",
  "location_off",
  "location_on",
  "mail_outline",
  "mark_chat_read",
  "mark_chat_unread",
  "mark_email_read",
  "mark_email_unread",
  "mark_unread_chat_alt",
  "message",
  "mobile_screen_share",
  "more_time",
  "nat",
  "no_sim",
  "pause_presentation",
  "person_add_disabled",
  "person_search",
  "phone",
  "phone_disabled",
  "phone_enabled",
  "phonelink_erase",
  "phonelink_lock",
  "phonelink_ring",
  "phonelink_setup",
  "portable_wifi_off",
  "present_to_all",
  "print_disabled",
  "qr_code",
  "qr_code_2",
  "qr_code_scanner",
  "read_more",
  "ring_volume",
  "rss_feed",
  "rtt",
  "screen_share",
  "send_time_extension",
  "sentiment_satisfied_alt",
  "sip",
  "speaker_phone",
  "spoke",
  "stay_current_landscape",
  "stay_current_portrait",
  "stay_primary_landscape",
  "stay_primary_portrait",
  "stop_screen_share",
  "swap_calls",
  "textsms",
  "unsubscribe",
  "voicemail",
  "vpn_key",
  "vpn_key_off",
  "wifi_calling",
  "add",
  "add_box",
  "add_circle",
  "add_circle_outline",
  "add_link",
  "amp_stories",
  "archive",
  "attribution",
  "backspace",
  "ballot",
  "biotech",
  "block",
  "block_flipped",
  "bolt",
  "calculate",
  "change_circle",
  "clear",
  "content_copy",
  "content_cut",
  "content_paste",
  "content_paste_go",
  "content_paste_off",
  "content_paste_search",
  "copy_all",
  "create",
  "delete_sweep",
  "deselect",
  "drafts",
  "dynamic_feed",
  "file_copy",
  "filter_list",
  "filter_list_off",
  "flag",
  "flag_circle",
  "font_download",
  "font_download_off",
  "forward",
  "gesture",
  "how_to_reg",
  "how_to_vote",
  "inbox",
  "insights",
  "inventory",
  "inventory_2",
  "link",
  "link_off",
  "low_priority",
  "mail",
  "markunread",
  "move_to_inbox",
  "next_week",
  "outlined_flag",
  "policy",
  "push_pin",
  "redo",
  "remove",
  "remove_circle",
  "remove_circle_outline",
  "reply",
  "reply_all",
  "report",
  "report_gmailerrorred",
  "report_off",
  "save",
  "save_alt",
  "save_as",
  "select_all",
  "send",
  "shield",
  "sort",
  "square_foot",
  "stacked_bar_chart",
  "stream",
  "tag",
  "text_format",
  "unarchive",
  "undo",
  "upcoming",
  "waves",
  "web_stories",
  "weekend",
  "where_to_vote",
  "1x_mobiledata",
  "30fps",
  "3g_mobiledata",
  "4g_mobiledata",
  "4g_plus_mobiledata",
  "60fps",
  "access_alarm",
  "access_alarms",
  "access_time",
  "access_time_filled",
  "ad_units",
  "add_alarm",
  "add_to_home_screen",
  "air",
  "airplane_ticket",
  "airplanemode_active",
  "airplanemode_inactive",
  "aod",
  "battery_alert",
  "battery_charging_full",
  "battery_full",
  "battery_saver",
  "battery_std",
  "battery_unknown",
  "bloodtype",
  "bluetooth",
  "bluetooth_connected",
  "bluetooth_disabled",
  "bluetooth_drive",
  "bluetooth_searching",
  "brightness_auto",
  "brightness_high",
  "brightness_low",
  "brightness_medium",
  "cable",
  "cameraswitch",
  "credit_score",
  "dark_mode",
  "data_saver_off",
  "data_saver_on",
  "data_usage",
  "developer_mode",
  "device_thermostat",
  "devices",
  "do_not_disturb_on_total_silence",
  "dvr",
  "e_mobiledata",
  "edgesensor_high",
  "edgesensor_low",
  "flashlight_off",
  "flashlight_on",
  "flourescent",
  "fmd_bad",
  "fmd_good",
  "g_mobiledata",
  "gpp_bad",
  "gpp_good",
  "gpp_maybe",
  "gps_fixed",
  "gps_not_fixed",
  "gps_off",
  "graphic_eq",
  "grid_3x3",
  "grid_4x4",
  "grid_goldenratio",
  "h_mobiledata",
  "h_plus_mobiledata",
  "hdr_auto",
  "hdr_auto_select",
  "hdr_off_select",
  "hdr_on_select",
  "lan",
  "lens_blur",
  "light_mode",
  "location_disabled",
  "location_searching",
  "lte_mobiledata",
  "lte_plus_mobiledata",
  "media_bluetooth_off",
  "media_bluetooth_on",
  "medication",
  "medication_liquid",
  "mobile_friendly",
  "mobile_off",
  "mobiledata_off",
  "mode_night",
  "mode_standby",
  "monitor_heart",
  "monitor_weight",
  "nearby_error",
  "nearby_off",
  "network_cell",
  "network_wifi",
  "nfc",
  "nightlight",
  "note_alt",
  "password",
  "pattern",
  "phishing",
  "pin",
  "play_lesson",
  "price_change",
  "price_check",
  "punch_clock",
  "quiz",
  "r_mobiledata",
  "radar",
  "remember_me",
  "reset_tv",
  "restart_alt",
  "reviews",
  "rsvp",
  "screen_lock_landscape",
  "screen_lock_portrait",
  "screen_lock_rotation",
  "screen_rotation",
  "screen_search_desktop",
  "screenshot",
  "sd_storage",
  "security_update",
  "security_update_good",
  "security_update_warning",
  "sell",
  "send_to_mobile",
  "settings_suggest",
  "settings_system_daydream",
  "share_location",
  "shortcut",

  "signal_cellular_alt",
  "signal_cellular_no_sim",
  "signal_cellular_nodata",
  "signal_cellular_null",
  "signal_cellular_off",
  "signal_wifi_bad",
  "signal_wifi_off",
  "signal_wifi_statusbar_null",
  "sim_card_download",
  "splitscreen",
  "sports_score",
  "ssid_chart",
  "storage",
  "storm",
  "summarize",
  "system_security_update",
  "system_security_update_good",
  "system_security_update_warning",
  "task",
  "thermostat",
  "timer_10_select",
  "timer_3_select",
  "tungsten",
  "usb",
  "usb_off",
  "wallpaper",
  "water",
  "widgets",
  "wifi_calling_1",
  "wifi_calling_2",
  "wifi_calling_3",
  "wifi_channel",
  "wifi_find",
  "wifi_lock",
  "wifi_password",
  "wifi_tethering",
  "wifi_tethering_error",
  "wifi_tethering_error_rounded",
  "wifi_tethering_off",
  "add_chart",
  "add_comment",
  "align_horizontal_center",
  "align_horizontal_left",
  "align_horizontal_right",
  "align_vertical_bottom",
  "align_vertical_center",
  "align_vertical_top",
  "area_chart",
  "attach_file",
  "attach_money",
  "auto_graph",
  "bar_chart",
  "border_all",
  "border_bottom",
  "border_clear",
  "border_color",
  "border_horizontal",
  "border_inner",
  "border_left",
  "border_outer",
  "border_right",
  "border_style",
  "border_top",
  "border_vertical",
  "bubble_chart",
  "candlestick_chart",
  "checklist",
  "checklist_rtl",
  "data_array",
  "data_object",
  "drag_handle",
  "draw",
  "edit_note",
  "format_align_center",
  "format_align_justify",
  "format_align_left",
  "format_align_right",
  "format_bold",
  "format_clear",
  "format_color_fill",
  "format_color_reset",
  "format_color_text",
  "format_indent_decrease",
  "format_indent_increase",
  "format_italic",
  "format_line_spacing",
  "format_list_bulleted",
  "format_list_numbered",
  "format_list_numbered_rtl",
  "format_paint",
  "format_quote",
  "format_shapes",
  "format_size",
  "format_strikethrough",
  "format_textdirection_l_to_r",
  "format_textdirection_r_to_l",
  "format_underlined",
  "functions",
  "height",
  "hexagon",
  "highlight",
  "horizontal_distribute",
  "horizontal_rule",
  "insert_chart",
  "insert_chart_outlined",
  "insert_comment",
  "insert_drive_file",
  "insert_emoticon",
  "insert_invitation",
  "insert_link",
  "insert_page_break",
  "insert_photo",
  "line_axis",
  "linear_scale",
  "margin",
  "merge_type",
  "mode",
  "mode_comment",
  "mode_edit",
  "mode_edit_outline",
  "monetization_on",
  "money_off",
  "money_off_csred",
  "move_down",
  "move_up",
  "multiline_chart",
  "notes",
  "numbers",
  "padding",
  "pentagon",
  "pie_chart",
  "pie_chart_outline",
  "pie_chart_outlined",
  "polyline",
  "post_add",
  "publish",
  "query_stats",
  "rectangle",
  "scatter_plot",
  "schema",
  "score",
  "short_text",
  "show_chart",
  "space_bar",
  "square",
  "stacked_line_chart",
  "strikethrough_s",
  "subscript",
  "superscript",
  "table_chart",
  "table_rows",
  "text_decrease",
  "text_fields",
  "text_increase",
  "title",
  "vertical_align_bottom",
  "vertical_align_center",
  "vertical_align_top",
  "vertical_distribute",
  "wrap_text",
  "approval",
  "attach_email",
  "attachment",
  "cloud",
  "cloud_circle",
  "cloud_done",
  "cloud_download",
  "cloud_off",
  "cloud_queue",
  "cloud_sync",
  "cloud_upload",
  "create_new_folder",
  "difference",
  "download",
  "download_done",
  "download_for_offline",
  "downloading",
  "drive_file_move",
  "drive_file_move_outline",
  "drive_file_move_rtl",
  "drive_file_rename_outline",
  "drive_folder_upload",
  "file_download",
  "file_download_done",
  "file_download_off",
  "file_open",
  "file_upload",
  "folder",
  "folder_delete",
  "folder_off",
  "folder_open",
  "folder_shared",
  "folder_zip",
  "format_overline",
  "grid_view",
  "newspaper",
  "request_quote",
  "rule_folder",
  "snippet_folder",
  "text_snippet",
  "topic",
  "upload",
  "upload_file",
  "workspaces",
  "workspaces_filled",
  "workspaces_outline",
  "adf_scanner",
  "browser_not_supported",
  "browser_updated",
  "cast",
  "cast_connected",
  "cast_for_education",
  "computer",
  "connected_tv",
  "desktop_mac",
  "desktop_windows",
  "developer_board",
  "developer_board_off",
  "device_hub",
  "device_unknown",
  "devices_other",
  "dock",
  "earbuds",
  "earbuds_battery",
  "gamepad",
  "headphones",
  "headphones_battery",
  "headset",
  "headset_mic",
  "headset_off",
  "home_max",
  "home_mini",
  "keyboard",
  "keyboard_alt",
  "keyboard_arrow_down",
  "keyboard_arrow_left",
  "keyboard_arrow_right",
  "keyboard_arrow_up",
  "keyboard_backspace",
  "keyboard_capslock",
  "keyboard_command_key",
  "keyboard_control_key",
  "keyboard_double_arrow_down",
  "keyboard_double_arrow_left",
  "keyboard_double_arrow_right",
  "keyboard_double_arrow_up",
  "keyboard_hide",
  "keyboard_option_key",
  "keyboard_return",
  "keyboard_tab",
  "keyboard_voice",
  "laptop",
  "laptop_chromebook",
  "laptop_mac",
  "laptop_windows",
  "memory",
  "monitor",
  "mouse",
  "phone_android",
  "phone_iphone",
  "phonelink",
  "phonelink_off",
  "point_of_sale",
  "power_input",
  "router",
  "scanner",
  "security",
  "sim_card",
  "smart_display",
  "smart_screen",
  "smart_toy",
  "smartphone",
  "speaker",
  "speaker_group",
  "start",
  "tablet",
  "tablet_android",
  "tablet_mac",
  "toys",
  "tv",
  "videogame_asset",
  "videogame_asset_off",
  "watch",
  "watch_off",
  "cloudy_snowing",
  "sensor_door",
  "sensor_window",
  "shield_moon",
  "10mp",
  "11mp",
  "12mp",
  "13mp",
  "14mp",
  "15mp",
  "16mp",
  "17mp",
  "18mp",
  "19mp",
  "20mp",
  "21mp",
  "22mp",
  "23mp",
  "24mp",
  "2mp",
  "30fps_select",
  "3mp",
  "4mp",
  "5mp",
  "60fps_select",
  "6mp",
  "7mp",
  "8mp",
  "9mp",
  "add_a_photo",
  "add_photo_alternate",
  "add_to_photos",
  "adjust",
  "animation",
  "assistant",
  "assistant_photo",
  "audiotrack",
  "auto_awesome",
  "auto_awesome_mosaic",
  "auto_awesome_motion",
  "auto_fix_high",
  "auto_fix_normal",
  "auto_fix_off",
  "auto_stories",
  "autofps_select",
  "bedtime",
  "bedtime_off",
  "blur_circular",
  "blur_linear",
  "blur_off",
  "blur_on",
  "brightness_1",
  "brightness_2",
  "brightness_3",
  "brightness_4",
  "brightness_5",
  "brightness_6",
  "brightness_7",
  "broken_image",
  "brush",
  "burst_mode",
  "camera",
  "camera_alt",
  "camera_front",
  "camera_rear",
  "camera_roll",
  "cases",
  "center_focus_strong",
  "center_focus_weak",
  "circle",
  "collections",
  "collections_bookmark",
  "color_lens",
  "colorize",
  "compare",
  "contrast",
  "control_point",
  "control_point_duplicate",
  "crop",
  "crop_16_9",
  "crop_3_2",
  "crop_5_4",
  "crop_7_5",
  "crop_din",
  "crop_free",
  "crop_landscape",
  "crop_original",
  "crop_portrait",
  "crop_rotate",
  "crop_square",
  "currency_franc",
  "currency_lira",
  "currency_pound",
  "currency_ruble",
  "currency_rupee",
  "currency_yen",
  "currency_yuan",
  "deblur",
  "dehaze",
  "details",
  "dirty_lens",
  "edit",
  "euro",
  "exposure",
  "exposure_neg_1",
  "exposure_neg_2",
  "exposure_plus_1",
  "exposure_plus_2",
  "exposure_zero",
  "face_retouching_natural",
  "face_retouching_off",
  "filter",
  "filter_1",
  "filter_2",
  "filter_3",
  "filter_4",
  "filter_5",
  "filter_6",
  "filter_7",
  "filter_8",
  "filter_9",
  "filter_9_plus",
  "filter_b_and_w",
  "filter_center_focus",
  "filter_drama",
  "filter_frames",
  "filter_hdr",
  "filter_none",
  "filter_tilt_shift",
  "filter_vintage",
  "flare",
  "flash_auto",
  "flash_off",
  "flash_on",
  "flip",
  "flip_camera_android",
  "flip_camera_ios",
  "gradient",
  "grain",
  "grid_off",
  "grid_on",
  "hdr_enhanced_select",
  "hdr_off",
  "hdr_on",
  "hdr_plus",
  "hdr_strong",
  "hdr_weak",
  "healing",
  "hevc",
  "hide_image",
  "image",
  "image_aspect_ratio",
  "image_not_supported",
  "image_search",
  "incomplete_circle",
  "iso",
  "landscape",
  "leak_add",
  "leak_remove",
  "lens",
  "linked_camera",
  "logo_dev",
  "looks",
  "looks_3",
  "looks_4",
  "looks_5",
  "looks_6",
  "looks_one",
  "looks_two",
  "loupe",
  "mic_external_off",
  "mic_external_on",
  "monochrome_photos",
  "motion_photos_auto",
  "motion_photos_off",
  "motion_photos_on",
  "motion_photos_pause",
  "motion_photos_paused",
  "movie_creation",
  "movie_filter",
  "mp",
  "music_note",
  "music_off",
  "nature",
  "nature_people",
  "navigate_before",
  "navigate_next",
  "palette",
  "panorama",
  "panorama_fish_eye",
  "panorama_horizontal",
  "panorama_horizontal_select",
  "panorama_photosphere",
  "panorama_photosphere_select",
  "panorama_vertical",
  "panorama_vertical_select",
  "panorama_wide_angle",
  "panorama_wide_angle_select",
  "photo",
  "photo_album",
  "photo_camera",
  "photo_camera_back",
  "photo_camera_front",
  "photo_filter",
  "photo_library",
  "photo_size_select_actual",
  "photo_size_select_large",
  "photo_size_select_small",
  "picture_as_pdf",
  "portrait",
  "raw_off",
  "raw_on",
  "receipt_long",
  "remove_red_eye",
  "rotate_90_degrees_ccw",
  "rotate_90_degrees_cw",
  "rotate_left",
  "rotate_right",
  "shutter_speed",
  "slideshow",
  "straighten",
  "style",
  "switch_camera",
  "switch_video",
  "tag_faces",
  "texture",
  "thermostat_auto",
  "timelapse",
  "timer",
  "timer_10",
  "timer_3",
  "timer_off",
  "tonality",
  "transform",
  "tune",
  "video_camera_back",
  "video_camera_front",
  "video_stable",
  "view_comfy",
  "view_compact",
  "vignette",
  "vrpano",
  "wb_auto",
  "wb_cloudy",
  "wb_incandescent",
  "wb_iridescent",
  "wb_shade",
  "wb_sunny",
  "wb_twilight",
  "360",
  "add_business",
  "add_location",
  "add_location_alt",
  "add_road",
  "agriculture",
  "airline_stops",
  "airlines",
  "alt_route",
  "atm",
  "attractions",
  "badge",
  "bakery_dining",
  "beenhere",
  "bike_scooter",
  "breakfast_dining",
  "brunch_dining",
  "bus_alert",
  "car_rental",
  "car_repair",
  "castle",
  "category",
  "celebration",
  "church",
  "cleaning_services",
  "compass_calibration",
  "connecting_airports",
  "delivery_dining",
  "departure_board",
  "design_services",
  "diamond",
  "dinner_dining",
  "directions",
  "directions_bike",
  "directions_boat",
  "directions_boat_filled",
  "directions_bus",
  "directions_bus_filled",
  "directions_car",
  "directions_car_filled",
  "directions_railway",
  "directions_railway_filled",
  "directions_run",
  "directions_subway",
  "directions_subway_filled",
  "directions_transit",
  "directions_transit_filled",
  "directions_walk",
  "dry_cleaning",
  "edit_attributes",
  "edit_location",
  "edit_location_alt",
  "edit_road",
  "egg",
  "egg_alt",
  "electric_bike",
  "electric_car",
  "electric_moped",
  "electric_rickshaw",
  "electric_scooter",
  "electrical_services",
  "emergency",
  "ev_station",
  "factory",
  "fastfood",
  "festival",
  "flight",
  "flight_class",
  "forest",
  "fork_left",
  "fork_right",
  "fort",
  "hail",
  "handyman",
  "hardware",
  "home_repair_service",
  "hotel",
  "hvac",
  "icecream",
  "kebab_dining",
  "layers",
  "layers_clear",
  "liquor",
  "local_activity",
  "local_airport",
  "local_atm",
  "local_bar",
  "local_cafe",
  "local_car_wash",
  "local_convenience_store",
  "local_dining",
  "local_drink",
  "local_fire_department",
  "local_florist",
  "local_gas_station",
  "local_grocery_store",
  "local_hospital",
  "local_hotel",
  "local_laundry_service",
  "local_library",
  "local_mall",
  "local_movies",
  "local_offer",
  "local_parking",
  "local_pharmacy",
  "local_phone",
  "local_pizza",
  "local_play",
  "local_police",
  "local_post_office",
  "local_printshop",
  "local_see",
  "local_shipping",
  "local_taxi",
  "lunch_dining",
  "map",
  "maps_ugc",
  "medical_services",
  "menu_book",
  "merge",
  "miscellaneous_services",
  "mode_of_travel",
  "money",
  "moped",
  "mosque",
  "moving",
  "multiple_stop",
  "museum",
  "my_location",
  "navigation",
  "near_me",
  "near_me_disabled",
  "nightlife",
  "no_meals",
  "no_meals_ouline",
  "no_transfer",
  "not_listed_location",
  "park",
  "pedal_bike",
  "person_pin",
  "person_pin_circle",
  "pest_control",
  "pest_control_rodent",
  "pin_drop",
  "place",
  "plumbing",
  "railway_alert",
  "ramen_dining",
  "ramp_left",
  "ramp_right",
  "rate_review",
  "restaurant",
  "restaurant_menu",
  "roundabout_left",
  "roundabout_right",
  "route",
  "run_circle",
  "sailing",
  "satellite",
  "set_meal",
  "signpost",
  "snowmobile",
  "soup_kitchen",
  "stadium",
  "store_mall_directory",
  "straight",
  "streetview",
  "subway",
  "synagogue",
  "takeout_dining",
  "taxi_alert",
  "temple_buddhist",
  "temple_hindu",
  "terrain",
  "theater_comedy",
  "traffic",
  "train",
  "tram",
  "transfer_within_a_station",
  "transit_enterexit",
  "trip_origin",
  "turn_left",
  "turn_right",
  "turn_sharp_left",
  "turn_sharp_right",
  "turn_slight_left",
  "turn_slight_right",
  "two_wheeler",
  "u_turn_left",
  "u_turn_right",
  "volunteer_activism",
  "warehouse",
  "wine_bar",
  "wrong_location",
  "zoom_in_map",
  "zoom_out_map",
  "app_settings_alt",
  "apps",
  "apps_outage",
  "arrow_back",
  "arrow_back_ios",
  "arrow_back_ios_new",
  "arrow_downward",
  "arrow_drop_down",
  "arrow_drop_down_circle",
  "arrow_drop_up",
  "arrow_forward",
  "arrow_forward_ios",
  "arrow_left",
  "arrow_right",
  "arrow_upward",
  "assistant_direction",
  "assistant_navigation",
  "campaign",
  "cancel",
  "check",
  "chevron_left",
  "chevron_right",
  "close",
  "double_arrow",
  "east",
  "expand_circle_down",
  "expand_less",
  "expand_more",
  "first_page",
  "fullscreen",
  "fullscreen_exit",
  "home_work",
  "last_page",
  "legend_toggle",
  "maps_home_work",
  "menu",
  "menu_open",
  "more_horiz",
  "more_vert",
  "north",
  "north_east",
  "north_west",
  "offline_share",
  "payments",
  "pivot_table_chart",
  "refresh",
  "south",
  "south_east",
  "south_west",
  "subdirectory_arrow_left",
  "subdirectory_arrow_right",
  "switch_left",
  "switch_right",
  "unfold_less",
  "unfold_more",
  "waterfall_chart",
  "west",
  "account_tree",
  "adb",
  "add_call",
  "airline_seat_flat",
  "airline_seat_flat_angled",
  "airline_seat_individual_suite",
  "airline_seat_legroom_extra",
  "airline_seat_legroom_normal",
  "airline_seat_legroom_reduced",
  "airline_seat_recline_extra",
  "airline_seat_recline_normal",
  "bluetooth_audio",
  "confirmation_number",
  "directions_off",
  "disc_full",
  "do_disturb",
  "do_disturb_alt",
  "do_disturb_off",
  "do_disturb_on",
  "do_not_disturb",
  "do_not_disturb_alt",
  "do_not_disturb_off",
  "do_not_disturb_on",
  "drive_eta",
  "enhanced_encryption",
  "event_available",
  "event_busy",
  "event_note",
  "folder_special",
  "imagesearch_roller",
  "live_tv",
  "mms",
  "more",
  "network_check",
  "network_locked",
  "no_encryption",
  "no_encryption_gmailerrorred",
  "ondemand_video",
  "personal_video",
  "phone_bluetooth_speaker",
  "phone_callback",
  "phone_forwarded",
  "phone_in_talk",
  "phone_locked",
  "phone_missed",
  "phone_paused",
  "power",
  "power_off",
  "priority_high",
  "running_with_errors",
  "sd_card",
  "sd_card_alert",
  "sim_card_alert",
  "sms",
  "sms_failed",
  "support_agent",
  "sync",
  "sync_disabled",
  "sync_lock",
  "sync_problem",
  "system_update",
  "tap_and_play",
  "time_to_leave",
  "tv_off",
  "vibration",
  "voice_chat",
  "vpn_lock",
  "wc",
  "wifi",
  "wifi_off",
  "ac_unit",
  "airport_shuttle",
  "all_inclusive",
  "apartment",
  "baby_changing_station",
  "backpack",
  "balcony",
  "bathtub",
  "beach_access",
  "bento",
  "bungalow",
  "business_center",
  "cabin",
  "carpenter",
  "casino",
  "chalet",
  "charging_station",
  "checkroom",
  "child_care",
  "child_friendly",
  "corporate_fare",
  "cottage",
  "countertops",
  "crib",
  "do_not_step",
  "do_not_touch",
  "dry",
  "elevator",
  "escalator",
  "escalator_warning",
  "family_restroom",
  "fence",
  "fire_extinguisher",
  "fitness_center",
  "food_bank",
  "foundation",
  "free_breakfast",
  "gite",
  "golf_course",
  "grass",
  "holiday_village",
  "hot_tub",
  "house",
  "house_siding",
  "houseboat",
  "iron",
  "kitchen",
  "meeting_room",
  "microwave",
  "night_shelter",
  "no_backpack",
  "no_cell",
  "no_drinks",
  "no_flash",
  "no_food",
  "no_meeting_room",
  "no_photography",
  "no_stroller",
  "other_houses",
  "pool",
  "rice_bowl",
  "roofing",
  "room_preferences",
  "room_service",
  "rv_hookup",
  "smoke_free",
  "smoking_rooms",
  "soap",
  "spa",
  "sports_bar",
  "stairs",
  "storefront",
  "stroller",
  "tapas",
  "tty",
  "umbrella",
  "villa",
  "wash",
  "water_damage",
  "wheelchair_pickup",
  "bathroom",
  "bed",
  "bedroom_baby",
  "bedroom_child",
  "bedroom_parent",
  "blender",
  "camera_indoor",
  "camera_outdoor",
  "chair",
  "chair_alt",
  "coffee",
  "coffee_maker",
  "dining",
  "door_back",
  "door_front",
  "door_sliding",
  "doorbell",
  "feed",
  "flatware",
  "garage",
  "light",
  "living",
  "manage_search",
  "podcasts",
  "shower",
  "table_bar",
  "table_restaurant",
  "window",
  "yard",
  "6_ft_apart",
  "add_moderator",
  "add_reaction",
  "architecture",
  "back_hand",
  "boy",
  "cake",
  "catching_pokemon",
  "clean_hands",
  "co2",
  "compost",
  "connect_without_contact",
  "construction",
  "cookie",
  "coronavirus",
  "cruelty_free",
  "deck",
  "domain",
  "domain_add",
  "downhill_skiing",
  "edit_notifications",
  "elderly",
  "elderly_woman",
  "emoji_emotions",
  "emoji_events",
  "emoji_flags",
  "emoji_food_beverage",
  "emoji_nature",
  "emoji_objects",
  "emoji_people",
  "emoji_symbols",
  "emoji_transportation",
  "engineering",
  "facebook",
  "female",
  "fireplace",
  "fitbit",
  "follow_the_signs",
  "front_hand",
  "girl",
  "group",
  "group_add",
  "group_off",
  "group_remove",
  "groups",
  "health_and_safety",
  "heart_broken",
  "hiking",
  "history_edu",
  "hive",
  "ice_skating",
  "interests",
  "ios_share",
  "kayaking",
  "king_bed",
  "kitesurfing",
  "location_city",
  "luggage",
  "male",
  "man",
  "masks",
  "military_tech",
  "mood",
  "mood_bad",
  "nights_stay",
  "no_luggage",
  "nordic_walking",
  "notification_add",
  "notifications",
  "notifications_active",
  "notifications_none",
  "notifications_off",
  "notifications_paused",
  "outdoor_grill",
  "pages",
  "paragliding",
  "party_mode",
  "people",
  "people_alt",
  "people_outline",
  "person",
  "person_add",
  "person_add_alt",
  "person_add_alt_1",
  "person_off",
  "person_outline",
  "person_remove",
  "person_remove_alt_1",
  "personal_injury",
  "piano",
  "piano_off",
  "pix",
  "plus_one",
  "poll",
  "precision_manufacturing",
  "psychology",
  "public",
  "public_off",
  "real_estate_agent",
  "recommend",
  "recycling",
  "reduce_capacity",
  "remove_moderator",
  "safety_divider",
  "sanitizer",
  "scale",
  "school",
  "science",
  "self_improvement",
  "sentiment_dissatisfied",
  "sentiment_neutral",
  "sentiment_satisfied",
  "sentiment_very_dissatisfied",
  "sentiment_very_satisfied",
  "share",
  "sick",
  "single_bed",
  "skateboarding",
  "sledding",
  "snowboarding",
  "snowshoeing",
  "social_distance",
  "south_america",
  "sports",
  "sports_baseball",
  "sports_basketball",
  "sports_cricket",
  "sports_esports",
  "sports_football",
  "sports_golf",
  "sports_handball",
  "sports_hockey",
  "sports_kabaddi",
  "sports_martial_arts",
  "sports_mma",
  "sports_motorsports",
  "sports_rugby",
  "sports_soccer",
  "sports_tennis",
  "sports_volleyball",
  "surfing",
  "switch_account",
  "thumb_down_alt",
  "thumb_up_alt",
  "transgender",
  "travel_explore",
  "vaccines",
  "water_drop",
  "waving_hand",
  "whatsapp",
  "whatshot",
  "woman",
  "workspace_premium",
  "check_box",
  "check_box_outline_blank",
  "indeterminate_check_box",
  "radio_button_checked",
  "radio_button_unchecked",
  "star",
  "star_border",
  "star_border_purple500",
  "star_half",
  "star_outline",
  "star_purple500",
  "toggle_off",
  "toggle_on",
];

const a2020menuCreatorOptions = {
  data() {
    return {
      loading: true,
      screenWidth: window.innerWidth,
      translations: uipTranslations,
      dataConnect: uipMasterPrefs.dataConnect,
      user: {
        allMenus: [],
        currentMenu: {
          items: [],
          name: "",
          status: true,
          roleMode: "inclusive",
          appliedTo: [],
        },
        currentItem: [],
      },
      master: {
        menuItems: [],
        searchString: "",
      },
      ui: {
        activeTab: "items",
        editingMode: false,
        editPanel: false,
      },
    };
  },
  created: function () {
    window.addEventListener("resize", this.getScreenWidth);
    var self = this;
    self.getMenuItems();
  },
  computed: {
    originalMenu() {
      var originaltmen = this.master.menuItems;
      return originaltmen;
    },
    filteredMenu() {
      var currentmen = this.user.currentMenu.items;
      return currentmen;
    },
  },
  mounted: function () {
    this.getMenus();
    this.loading = false;
  },
  methods: {
    itemsMoved() {
      items = this.user.currentMenu.items;
      for (let i = 0; i < items.length; i++) {
        if (!items[i].submenu) {
          items[i].submenu = [];
        }
      }
    },
    cloneMenuItem(menuitem) {
      return JSON.parse(JSON.stringify(menuitem));
    },
    exportMenu(themenu) {
      self = this;
      ALLoptions = JSON.stringify(themenu);

      var today = new Date();
      var dd = String(today.getDate()).padStart(2, "0");
      var mm = String(today.getMonth() + 1).padStart(2, "0"); //January is 0!
      var yyyy = today.getFullYear();

      date_today = mm + "_" + dd + "_" + yyyy;
      filename = "uipress_menu_" + date_today + ".json";

      var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(ALLoptions);
      var dlAnchorElem = document.getElementById("uipress-export-menus");
      dlAnchorElem.setAttribute("href", dataStr);
      dlAnchorElem.setAttribute("download", filename);
      dlAnchorElem.click();
    },
    import_menu() {
      self = this;

      var thefile = jQuery("#uipress_import_menu")[0].files[0];

      if (thefile.type != "application/json") {
        window.alert("Please select a valid JSON file.");
        return;
      }

      if (thefile.size > 100000) {
        window.alert("File is to big.");
        return;
      }

      var file = document.getElementById("uipress_import_menu").files[0];
      var reader = new FileReader();
      reader.readAsText(file, "UTF-8");

      reader.onload = function (evt) {
        json_settings = evt.target.result;
        parsed = JSON.parse(json_settings);

        if (parsed != null) {
          parsed.id = null;
          ///GOOD TO GO;
          self.user.currentMenu = parsed;
          uipNotification("Menu imported", { pos: "bottom-left", status: "success" });
          self.saveSettings();
        } else {
          uipNotification("something wrong", { pos: "bottom-left", status: "danger" });
        }
      };
    },
    createNewMenu() {
      this.user.currentMenu.items = [];
      this.user.currentMenu.id = "";
      this.user.currentMenu.name = "Draft";
      this.user.currentMenu.status = true;
      this.user.currentMenu.roleMode = "inclusive";
      this.user.currentMenu.appliedTo = [];
      this.ui.editingMode = true;
    },
    confirmDelete(themenu) {
      self = this;
      if (confirm(self.translations.confirmDelete)) {
        self.deleteMenu(themenu);
      }
    },
    switchStatus(menuid, menustatus) {
      self = this;

      jQuery.ajax({
        url: a2020_menucreator_ajax.ajax_url,
        type: "post",
        data: {
          action: "uipress_switch_menu_status",
          security: a2020_menucreator_ajax.security,
          menuid: menuid,
          status: menustatus,
        },
        success: function (response) {
          data = JSON.parse(response);

          if (data.error) {
            ///SOMETHING WENT WRONG
            uipNotification(data.error, { pos: "bottom-left", status: "danger" });
            return;
          }

          uipNotification(data.message, { pos: "bottom-left", status: "success" });
        },
      });
    },
    duplicateMenu(themenu) {
      self = this;

      if (!themenu) {
        return;
      }

      jQuery.ajax({
        url: a2020_menucreator_ajax.ajax_url,
        type: "post",
        data: {
          action: "uipress_duplicate_menu",
          security: a2020_menucreator_ajax.security,
          menu: themenu,
        },
        success: function (response) {
          data = JSON.parse(response);

          if (data.error) {
            ///SOMETHING WENT WRONG
            uipNotification(data.error, { pos: "bottom-left", status: "danger" });
            return;
          }

          uipNotification(data.message, { pos: "bottom-left", status: "success" });

          self.getMenus();
        },
      });
    },
    deleteMenu(themenu) {
      self = this;

      if (!themenu.id) {
        return;
      }

      jQuery.ajax({
        url: a2020_menucreator_ajax.ajax_url,
        type: "post",
        data: {
          action: "uipress_delete_menu",
          security: a2020_menucreator_ajax.security,
          menuid: themenu.id,
        },
        success: function (response) {
          data = JSON.parse(response);

          if (data.error) {
            ///SOMETHING WENT WRONG
            uipNotification(data.error, { pos: "bottom-left", status: "danger" });
            return;
          }

          uipNotification(data.message, { pos: "bottom-left", status: "success" });

          self.getMenus();
        },
      });
    },
    openMenu(themenu) {
      this.user.currentMenu = themenu;
      this.ui.editingMode = true;
    },
    getMenus() {
      self = this;

      jQuery.ajax({
        url: a2020_menucreator_ajax.ajax_url,
        type: "post",
        data: {
          action: "uipress_get_menus",
          security: a2020_menucreator_ajax.security,
        },
        success: function (response) {
          data = JSON.parse(response);

          if (data.error) {
            ///SOMETHING WENT WRONG
            uipNotification(data.error, { pos: "bottom-left", status: "danger" });
            return;
          }

          self.user.allMenus = data.menus;
        },
      });
    },

    getMenuItems() {
      self = this;

      jQuery.ajax({
        url: a2020_menucreator_ajax.ajax_url,
        type: "post",
        data: {
          action: "uipress_get_menu_items",
          security: a2020_menucreator_ajax.security,
        },
        success: function (response) {
          data = JSON.parse(response);

          if (data.error) {
            ///SOMETHING WENT WRONG
            uipNotification(data.error, { pos: "bottom-left", status: "danger" });
            return;
          }
          self.master.menuItems = data.menu.menu;
        },
      });
    },
    saveSettings() {
      self = this;

      //console.log(self.user.currentMenu);

      menuitems = JSON.stringify(self.user.currentMenu);

      jQuery.ajax({
        url: a2020_menucreator_ajax.ajax_url,
        type: "post",
        data: {
          action: "uipress_save_menu",
          security: a2020_menucreator_ajax.security,
          menu: menuitems,
        },
        success: function (response) {
          data = JSON.parse(response);

          if (data.error) {
            ///SOMETHING WENT WRONG
            uipNotification(data.error, { pos: "bottom-left", status: "danger" });
            return;
          }
          self.user.currentMenu.id = data.menuID;
          self.getMenus();

          uipNotification(data.message, { pos: "bottom-left", status: "success" });
        },
      });
    },
    onChange() {},
    addDivider() {
      item = {
        name: "",
        type: "sep",
      };

      this.user.currentMenu.items.push(item);
    },
    addBlank() {
      item = {
        name: "Blank",
        type: "menu",
        href: "#",
        submenu: [],
        icon: "<span class='material-icons-outlined a2020-menu-icon'>check_box_outline_blank</span>",
      };

      this.user.currentMenu.items.push(item);
    },
    getDataFromComp(originalcode, editedcode) {
      return editedcode;
    },
    getScreenWidth() {
      this.screenWidth = window.innerWidth;
    },
    isSmallScreen() {
      if (this.screenWidth < 1000) {
        return true;
      } else {
        return false;
      }
    },
    addToMenu(item) {
      item.type = "menu";

      if (!item.submenu || !Array.isArray(item.submenu)) {
        item.submenu = [];
      } else {
        for (let i = 0; i < item.submenu.length; i++) {
          item.submenu[i].submenu = [];
        }
      }
      this.user.currentMenu.items.push(JSON.parse(JSON.stringify(item)));
    },
    editMenuItem(item) {
      this.user.currentItem = item;

      if (this.user.currentItem.blankPage && this.user.currentItem.blankPage != "") {
        option = this.user.currentItem.blankPage;
        if (option == "1" || option == true || option == "true") {
          this.user.currentItem.blankPage = true;
        } else {
          this.user.currentItem.blankPage = false;
        }
      }

      this.ui.editPanel = true;
    },
    removeMenuItem(currentindex) {
      this.user.currentMenu.items.splice(currentindex, 1);
    },
    removeSubMenuItem(currentindex, parentindex) {
      this.user.currentMenu.items[parentindex].submenu.splice(currentindex, 1);
    },
    getdatafromIcon(chosenicon) {
      if (chosenicon == "removeicon") {
        returndata = "";
      } else {
        returndata = '<span class="material-icons-outlined a2020-menu-icon">' + chosenicon + "</span>";
      }
      return returndata;
    },
  },
};

const a2020menuCreator = Vue.createApp(a2020menuCreatorOptions);

/////////////////////////
//Multi Select Component
/////////////////////////
a2020menuCreator.component("multi-select", {
  data: function () {
    return {
      thisSearchInput: "",
      options: [],
      notFoundMessage: "",
      ui: {
        dropOpen: false,
        searching: false,
      },
    };
  },
  props: {
    selected: Array,
    name: String,
    placeholder: String,
    single: Boolean,
  },
  watch: {
    thisSearchInput: function (newValue, oldValue) {
      self = this;

      if (newValue.length > 0) {
        self.ui.searching = true;
        jQuery.ajax({
          url: a2020_menucreator_ajax.ajax_url,
          type: "post",
          data: {
            action: "uipress_menu_creator_get_users_and_roles",
            security: a2020_menucreator_ajax.security,
            searchString: newValue,
          },
          success: function (response) {
            data = JSON.parse(response);

            if (data.error) {
              ///SOMETHING WENT WRONG
              uipNotification(data.error, { pos: "bottom-left", status: "danger" });
              return;
            }

            self.options = data.roles;
            self.notFoundMessage = data.notfound;
            self.ui.searching = false;
          },
        });
      }
    },
  },
  methods: {
    //////TITLE: ADDS A SLECTED OPTION//////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////DESCRIPTION: ADDS A SELECTED OPTION FROM OPTIONS
    addSelected(selectedoption, options) {
      if (this.single == true) {
        options[0] = selectedoption;
      } else {
        options.push(selectedoption);
      }
    },
    //////TITLE: REMOVES A SLECTED OPTION//////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////DESCRIPTION: ADDS A SELECTED OPTION FROM OPTIONS
    removeSelected(option, options) {
      const index = options.indexOf(option);
      if (index > -1) {
        options = options.splice(index, 1);
      }
    },

    //////TITLE:  CHECKS IF SELECTED OR NOT//////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////DESCRIPTION: ADDS A SELECTED OPTION FROM OPTIONS
    ifSelected(option, options) {
      const index = options.indexOf(option);
      if (index > -1) {
        return false;
      } else {
        return true;
      }
    },
    //////TITLE:  CHECKS IF IN SEARCH//////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////DESCRIPTION: CHECKS IF ITEM CONTAINS STRING
    ifInSearch(option, searchString) {
      item = option.toLowerCase();
      string = searchString.toLowerCase();

      if (item.includes(string)) {
        return true;
      } else {
        return false;
      }
    },
    onClickOutside(event) {
      const path = event.path || (event.composedPath ? event.composedPath() : undefined);
      // check if the MouseClick occurs inside the component
      if (path && !path.includes(this.$el) && !this.$el.contains(event.target)) {
        this.closeThisComponent(); // whatever method which close your component
      }
    },
    openThisComponent() {
      this.ui.dropOpen = true; // whatever codes which open your component
      // You can also use Vue.$nextTick or setTimeout
      requestAnimationFrame(() => {
        document.documentElement.addEventListener("click", this.onClickOutside, false);
      });
    },
    closeThisComponent() {
      this.ui.dropOpen = false; // whatever codes which close your component
      document.documentElement.removeEventListener("click", this.onClickOutside, false);
    },
  },
  template:
    '<div class="uip-position-relative" @click="openThisComponent">\
    <div class="uip-margin-bottom-xs uip-padding-left-xxs uip-padding-right-xxs uip-padding-top-xxs uip-background-default uip-border uip-border-round uip-w-100p uip-cursor-pointer uip-h-32 uip-border-box" > \
      <div class="uip-flex uip-flex-center">\
        <div class="uip-flex-grow uip-margin-right-s">\
          <div v-if="selected.length < 1" style="margin-top:2px;">\
            <span class="uip-text-muted">{{name}}...</span>\
          </div>\
          <span v-if="selected.length > 0" v-for="select in selected" class="uip-background-primary-wash uip-border-round uip-padding-xxs uip-display-inline-block uip-margin-right-xxs uip-margin-bottom-xxs">\
            <div class="uip-text-normal">\
              {{select}}\
              <span class="uip-margin-left-xxs" href="#" @click="removeSelected(select,selected)">x</span>\
            </div>\
          </span>\
        </div>\
        <span class="material-icons-outlined uip-text-muted">expand_more</span>\
      </div>\
    </div>\
    <div v-if="ui.dropOpen" class="uip-position-absolute uip-background-default uip-border uip-border-round uip-w-100p uip-border-box uip-z-index-9">\
      <div class="uip-flex uip-background-default uip-border-bottom uip-padding-xs uip-border-round">\
        <span class="material-icons-outlined uip-text-muted uip-margin-right-xs">search</span>\
        <input class="uip-blank-input uip-flex-grow" type="search"  \
        :placeholder="placeholder" v-model="thisSearchInput" autofocus>\
      </div>\
      <div class="uip-loading-box" v-if="ui.searching">\
        <div class="uip-loader"></div>\
      </div>\
      <div class="">\
        <template v-for="option in options">\
          <div  class="uip-background-default uip-padding-xs hover:uip-background-muted uip-border-bottom" \
          @click="addSelected(option.name, selected)" \
          v-if="ifSelected(option.name, selected) && ifInSearch(option.name, thisSearchInput)" \
          style="cursor: pointer">\
            <div class="uip-flex uip-flex-row uip-flex-center">\
              <div class="uip-background-dark uip-border-circle uip-w-28 uip-h-28 uip-flex uip-flex-center uip-flex-middle uip-margin-right-xs">\
                <span class="uip-text-inverse uip-text-m uip-no-select uip-line-height-0 material-icons-outlined">{{option.icon}}</span>\
              </div>\
              <div class="uip-flex-grow">\
                <div class="uip-text-bold uip-text-emphasis">{{option.label}}</div>\
                <div class="uip-text-muted">{{option.type}}</div>\
              </div>\
            </div>\
          </div>\
        </template>\
        <div v-if="options.length < 1 && thisSearchInput.length > 0" class="uip-padding-s uip-text-center uip-text-muted">\
         {{notFoundMessage + " "}}<span class="uip-text-bold">{{thisSearchInput}}</span>\
        </div>\
      </div>\
    </div>\
  </div>',
});

a2020menuCreator.component("icon-select", {
  emits: ["iconchange"],
  props: {
    menuitemicon: String,
    translations: Object,
  },
  data: function () {
    return {
      thisSearchInput: "",
      options: allGoogleIcons,
      currentPage: 0,
      iconsPerPage: 56,
      totalIcons: 0,
      maxPages: 0,
      ui: {
        options: false,
      },
    };
  },
  watch: {
    thisSearchInput: function (newValue, oldValue) {},
  },
  computed: {
    allIcons() {
      let self = this;
      masteroptions = self.options;
      returndata = [];
      temparray = [];
      searchinput = self.thisSearchInput.toLowerCase();

      if (self.currentPage < 0) {
        self.currentPage = 0;
      }
      self.totalIcons = self.options.length;
      self.maxPages = Math.ceil(self.options.length / this.iconsPerPage);

      if (self.currentPage > self.maxPages) {
        self.currentPage = self.maxPages;
      }

      startPos = self.currentPage * self.iconsPerPage;
      endPos = startPos + self.iconsPerPage;

      if (searchinput.length > 0) {
        self.currentPage = 0;

        for (let i = 0; i < masteroptions.length; i++) {
          name = masteroptions[i].toLowerCase();
          if (name.includes(searchinput)) {
            temparray.push(masteroptions[i]);
          }
        }

        returndata = temparray.slice(startPos, endPos);
        self.totalIcons = returndata.length;
        self.maxPages = Math.ceil(returndata.length / this.iconsPerPage);
      } else {
        returndata = this.options.slice(startPos, endPos);
      }

      return returndata;
    },
  },
  methods: {
    chosenicon(thicon) {
      this.$emit("iconchange", thicon);
    },
    removeIcon() {
      thicon = "removeicon";
      this.$emit("iconchange", thicon);
    },
    //////TITLE: ADDS A SLECTED OPTION//////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////DESCRIPTION: ADDS A SELECTED OPTION FROM OPTIONS
    addSelected(selectedoption, options) {
      if (this.single == true) {
        options[0] = selectedoption;
      } else {
        options.push(selectedoption);
      }
    },
    //////TITLE: REMOVES A SLECTED OPTION//////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////DESCRIPTION: ADDS A SELECTED OPTION FROM OPTIONS
    removeSelected(option, options) {
      const index = options.indexOf(option);
      if (index > -1) {
        options = options.splice(index, 1);
      }
    },

    //////TITLE:  CHECKS IF SELECTED OR NOT//////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////DESCRIPTION: ADDS A SELECTED OPTION FROM OPTIONS
    ifSelected(option, options) {
      const index = options.indexOf(option);
      if (index > -1) {
        return false;
      } else {
        return true;
      }
    },
    //////TITLE:  CHECKS IF IN SEARCH//////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////DESCRIPTION: CHECKS IF ITEM CONTAINS STRING
    ifInSearch(option, searchString) {
      item = option.toLowerCase();
      string = searchString.toLowerCase();

      if (item.includes(string)) {
        return true;
      } else {
        return false;
      }
    },
    onClickOutside(event) {
      const path = event.path || (event.composedPath ? event.composedPath() : undefined);
      // check if the MouseClick occurs inside the component
      if (path && !path.includes(this.$el) && !this.$el.contains(event.target)) {
        this.closeThisComponent(); // whatever method which close your component
      }
    },
    openThisComponent() {
      this.ui.options = this.ui.options != true; // whatever codes which open your component
      // You can also use Vue.$nextTick or setTimeout
      requestAnimationFrame(() => {
        document.documentElement.addEventListener("click", this.onClickOutside, false);
      });
    },
    closeThisComponent() {
      this.ui.options = false; // whatever codes which close your component
      document.documentElement.removeEventListener("click", this.onClickOutside, false);
    },
    nextPage() {
      this.currentPage = this.currentPage + 1;
    },
    previousPage() {
      this.currentPage = this.currentPage - 1;
    },
  },
  template:
    '<div>\
      <div class="uip-flex">\
        <span v-if="menuitemicon"\
        class="material-icons-outlined uip-background-muted uip-padding-xxs uip-border-round hover:uip-background-grey uip-cursor-pointer uip-margin-right-xs"\
        v-html="menuitemicon" ></span>\
        <button @click="openThisComponent" class="uip-button-default" type="button">{{translations.chooseIcon}}</button>\
      </div>\
      <div v-if="ui.options" \
      class="uip-position-absolute uip-padding-s uip-background-default uip-border-round uip-shadow uip-drop-bottom uip-w-300">\
        <!-- SEARCH COMP -->\
        <div class="uip-margin-bottom-m uip-padding-xxs uip-background-muted uip-border-round">\
          <div class="uip-flex uip-flex-center">\
            <span class="uip-margin-right-xs uip-text-muted">\
              <span class="material-icons-outlined">manage_search</span>\
            </span> \
            <input type="search" v-model="thisSearchInput" :placeholder="translations.search" class="uip-blank-input uip-min-width-0 uip-flex-grow">\
          </div>\
        </div>\
        <!-- END SEARCH COMP -->\
        <div class="uip-flex uip-flex-wrap uip-flex-start uip-margin-bottom-s">\
          <template v-for="option in allIcons">\
             <span class="uip-margin-right-xs uip-margin-bottom-xs material-icons-outlined uip-background-muted uip-padding-xxs uip-border-round hover:uip-background-grey uip-cursor-pointer uip-flex-no-grow uip-max-w-32" @click="chosenicon(option)">\
               {{option}}\
             </span>\
          </template>\
        </div>\
        <div class="uip-flex">\
          <button v-if="totalIcons > iconsPerPage" class="uip-button-default material-icons-outlined uip-margin-right-xs" @click="previousPage()" type="button">chevron_left</button>\
          <button v-if="totalIcons > iconsPerPage" class="uip-button-default material-icons-outlined" @click="nextPage()" type="button">chevron_right</button>\
          <div class="uip-flex-grow uip-text-right">\
            <button @click="removeIcon()" class="uip-button-danger" type="button">Clear Icon</button>\
          </div>\
        </div>\
      </div>\
    </div>',
});

a2020menuCreator.component("uip-dropdown", {
  props: {
    type: String,
    icon: String,
    pos: String,
    translation: String,
    size: String,
    primary: Boolean,
  },
  data: function () {
    return {
      modelOpen: false,
    };
  },
  mounted: function () {},
  methods: {
    onClickOutside(event) {
      const path = event.path || (event.composedPath ? event.composedPath() : undefined);
      // check if the MouseClick occurs inside the component
      if (path && !path.includes(this.$el) && !this.$el.contains(event.target)) {
        this.closeThisComponent(); // whatever method which close your component
      }
    },
    openThisComponent() {
      this.modelOpen = this.modelOpen != true; // whatever codes which open your component
      // You can also use Vue.$nextTick or setTimeout
      requestAnimationFrame(() => {
        document.documentElement.addEventListener("click", this.onClickOutside, false);
      });
    },
    closeThisComponent() {
      this.modelOpen = false; // whatever codes which close your component
      document.documentElement.removeEventListener("click", this.onClickOutside, false);
    },
    getClass() {
      if (this.pos == "botton-left") {
        return "uip-margin-top-s uip-right-0";
      }
      if (this.pos == "botton-center") {
        return "uip-margin-top-s uip-right-center";
      }
      if (this.pos == "top-left") {
        return "uip-margin-bottom-s uip-right-0 uip-bottom-100p";
      }
    },
    getPaddingClass() {
      if (!this.size) {
        return "uip-padding-xs";
      }
      if (this.size == "small") {
        return "uip-padding-xxs";
      }
      if (this.size == "large") {
        return "uip-padding-s";
      }
      return "uip-padding-xs";
    },
    getPrimaryClass() {
      if (!this.primary) {
        return "uip-button-default";
      }
      if (this.primary) {
        return "uip-button-primary uip-text-bold";
      }
      return "uip-button-default";
    },
  },
  template:
    '<div class="uip-position-relative">\
      <div class="uip-display-inline-block">\
        <div v-if="type == \'icon\'" @click="openThisComponent" class="uip-background-muted uip-border-round hover:uip-background-grey uip-cursor-pointer  material-icons-outlined" type="button" :class="getPaddingClass()">{{icon}}</div>\
        <button v-if="type == \'button\'" @click="openThisComponent" class="uip-button-default" :class="[getPaddingClass(), getPrimaryClass() ]" type="button">{{translation}}</button>\
      </div>\
      <div v-if="modelOpen" :class="getClass()"\
      class="uip-position-absolute uip-padding-s uip-background-default uip-border-round uip-shadow uip-min-w-200 uip-z-index-9999">\
        <slot></slot>\
      </div>\
    </div>',
});

a2020menuCreator.component("draggable", vuedraggable);

a2020menuCreator.mount("#menu-creator-app");
