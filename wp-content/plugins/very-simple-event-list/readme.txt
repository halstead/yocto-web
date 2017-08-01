=== Very Simple Event List ===
Contributors: Guido07111975
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=donation%40guidovanderleest%2enl
Version: 6.4
License: GNU General Public License v3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Requires at least: 4.6
Tested up to: 4.8
Stable tag: trunk
Tags: simple, event, events, event list, event manager


This is a very simple plugin to display a list of events. Use a shortcode to display events on a page or use the widget.


== DESCRIPTION ==
= About =
This is a very simple plugin to display a list of events. 

Use a shortcode to display events on a page or use the widget.

= How to use =
After installation go to Events and start adding your events.

* Use shortcode `[vsel]` to display upcoming events (including today)
* Use shortcode `[vsel-past-events]` to display past events
* Use shortcode `[vsel-current-events]` to display current events
* Use shortcode `[vsel-all-events]` to display all events

= Widget =
You can also list events in your sidebar using the widget.

The pagination is hidden because it's not working properly in a widget. 

But you can set a link to a page with all events.

= Settingspage =
Via Settings > VSEL you can:

* Keep events and settings when uninstalling plugin
* Show a summary instead of all content
* Link title to the event page
* Hide elements in event list

= Shortcode attributes =
Display events from certain categories: `[vsel event_cat="first-category, second-category"]`

You should enter the category slug. The slug is not always the same as the name of the category.

Set amount of events per page: `[vsel posts_per_page=5]` 

This will overwrite amount set in Settings > Reading.

Change default order from ascending to descending: `[vsel order=desc]`

Change default order from descending to ascending: `[vsel order=asc]`

The default order of the upcoming and current events list is ascending. The default order of the past and all events list is descending.

= Change labels =
* Change date label: `[vsel date_label="Event date: %s"]`
* Change start date label: `[vsel start_label="Event start date: %s"]`
* Change end date label: `[vsel end_label="Event end date: %s"]`
* Change time label: `[vsel time_label="Event time: %s"]` 
* Change location label: `[vsel location_label="Event location: %s"]` 
* Hide a label: `[vsel date_label="%s"]`
* Multiple attributes: `[vsel posts_per_page=5 date_label="Event date: %s"]` 

= Widget attributes =
The widget supports the same attributes. Enter them without shortcode itself and without brackets.

= Examples =
* Events from certain categories: `event_cat="first-category, second-category"`
* Set amount of events per page: `posts_per_page=5`
* Change default events order: `order=desc`
* Change date label: `date_label="Event date: %s"`
* Hide a label: `date_label="%s"`
* Multiple attributes: `posts_per_page=5 date_label="Event date: %s"` 

= Single event =
Template file single.php (and in some cases single-event.php) is being used to display a single event. This file is located in your theme folder.

= Event category page =
By default template file archive.php is being used. This file is located in your theme folder. My plugin does not support this file, so the event category page will not be displayed properly.

The event category page will only display properly when using the shortcode on a page.

Example: `[vsel event_cat="first-category, second-category"]`

= Uninstall =
If you uninstall plugin via WP dashboard all events and settings will be removed from database.

It removes all posts with (custom) post type "event".

You can avoid this via Settings > VSEL.

= Question? = 
Please take a look at the FAQ section.

= Translation =
Not included but plugin supports WordPress language packs.

More [translations](https://translate.wordpress.org/projects/wp-plugins/very-simple-event-list) are very welcome!

= Credits =
Without the WordPress codex and help from the WordPress community I was not able to develop this plugin, so: thank you!

Enjoy!


== INSTALLATION ==
Installation info is moved to Description section because of the new plugin directory.


== Frequently Asked Questions ==
= How can I change date format? =
You can set date format in WP dashboard via Settings > General.

The datepicker and date input field in backend accept 2 numeric date formats: "day-month-year" (30-01-2016) and "year-month-day" (2016-01-30).

If the date format set in WP dashboard is not numeric it will be changed into 1 of these 2 numeric date formats.  

= How do I set plugin language? =
Plugin uses the WP Dashboard language, set in Settings > General.

If plugin language pack is not available, language fallback will be English.

= How do I style the event list? =
It mostly depends on the stylesheet of your theme.

You can change style (CSS) using for example the [Very Simple Custom Style](https://wordpress.org/plugins/very-simple-custom-style) plugin.

For non CSS related customizations of the single event display you can add a file called single-event.php in your theme folder and customize this file to your needs.

= What do you mean with current events? =
Current events are events I can visit today. So this can be an one-day or multi-day event.

= Are events also listed on time? =
No, because input field for time is a regular text input this is not possible.
 
= Can I set the number of events on a page? =
Yes, this is possible.

You can find more info about this at the Description section.

= Can I display a summary instead of all content? =
Yes, this is possible.

You can find more info about this at the Description section.

= Can I change the summary lenght? =
Yes, you can set a custom summary while adding an event. This will replace the default summary (if activated).

= Can I display certain events only? =
Besides listing upcoming, current or past events you can display events from certain categories.

You can find more info about this at the Description section.

= Is it possible to display events in a widget? =
Yes, this is possible.

You can find more info about this at the Description section.

= Why is the page with all events not displayed properly? =
Go to the page in your dashboard and check the shortcode in "Text" mode. 

If you have added the shortcode in "Visual" mode, it might be wrapped in HTML tags, such as: `<script>[vsel]</script>`

You should remove the HTML tags and resave the page.

= Why is a single event not displayed properly? =
You can find more info about this at the Description section.

= Why is the event category page not displayed properly? =
You can find more info about this at the Description section.

= Why a 404 (nothing found) when I click the pagination? =
This might be caused because the slug of your page ends with "event". This causes a conflict with my plugin. 

You should change this slug into something else. The slug is not always the same as the name of the page.

= Why a 404 (nothing found) when I click the title link? =
This is mostly caused by a wrong permalink setting. Please reset it via Settings > Permalinks. 

= Can I hide the event labels? =
Yes, this is possible.

You can find more info about this at the Description section.

= What does "Link to more info" mean? =
While adding an event you can add a link (an URL) to a post, page or website.

This can be useful in case additional info is available on another site.

And you can label this link. Default label is "More info".

= What does "Link to all events" mean? =
While adding a widget you can add a link (an URL) to a page with all events.

This can be useful because in most cases the widget only lists a few events.

And you can label this link. Default label is "All events".

= Can I use multiple shortcodes on the same page? =
Yes, as far as I know there are no issues when using multiple shortcodes.

= Why an error message instead of a date? =
It's displayed in case start date begins after end date. To solve this please reset date.

= Why no start date in dashboard? =
Because I have added this feature in version 4.1.

All events posted before version 4.1 have 1 date only. But you can set a start and end date for each event afterwards.

= Why no meta, image or categories while adding an event? =
If these boxes are not present, they might be unchecked in Screen Options.

= How do I list events in a template file? =
* Upcoming events: `<?php echo do_shortcode( '[vsel]' ); ?>` 
* Past events: `<?php echo do_shortcode( '[vsel-past-events]' ); ?>`
* Current events: `<?php echo do_shortcode( '[vsel-current-events]' ); ?>`
* All events: `<?php echo do_shortcode( '[vsel-all-events]' ); ?>`

= What happens with my events when I uninstall VSEL? =
You can find more info about this at the Description section.

= How can I make a donation? =
You like my plugin and you're willing to make a donation? Nice! There's a PayPal donate link on the WordPress plugin page and my website.

= Other question or comment? =
Please open a topic in plugin forum.


== Changelog ==
= Version 6.4 =
* fixed pagination when event list is on static front page (thanks BC and Alan)
* changed custom query names into unique ones to avoid conflict
* wrapped meta variables date, time and location in span tags
* now you can apply custom css to the variable itself instead to whole label
* added css class to no events text: no-events
* updated all shortcode files
* updated file vsel-style
* updated file vsel-datepicker (new jquery version)
* updated FAQ

= Version 6.3 =
* file vsel: fixed mistake in user permission check
* new option: hide date on page or in widget
* new option: hide link to more info on page with all events
* updated all shortcode files
* updated file vsel-style
* minor textual changes

= Version 6.2 =
* minor textual changes
* updated readme file
* new screenshots on wp.org

= Version 6.1 =
* added shortcode attribute to change default order of events (asc or desc)
* for more info please check readme file
* bugfix: changed time format in shortcode files

For all versions please check file changelog.


== Screenshots == 
1. Very Simple Event List (Twenty Seventeen theme).
2. Very Simple Event List single event (Twenty Seventeen theme).
3. Very Simple Event List widget (Twenty Seventeen theme).
4. Very Simple Event List (dashboard).
5. Very Simple Event List single event (dashboard).
6. Very Simple Event List widget (dashboard).
7. Very Simple Event List settingspage (dashboard).