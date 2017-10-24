<?php

namespace Roots\Sage\Extras;

use Roots\Sage\Setup;
use WP_Query;

/**
 * Add <body> classes
 */
function body_class($classes) {
  // Add page slug if it doesn't exist
  if (is_single() || is_page() && !is_front_page()) {
    if (!in_array(basename(get_permalink()), $classes)) {
      $classes[] = basename(get_permalink());
    }
  }

  // Add class if sidebar is active
  if (Setup\display_sidebar()) {
    $classes[] = 'sidebar-primary';
  }

  return $classes;
}
add_filter('body_class', __NAMESPACE__ . '\\body_class');

/**
 * Clean up the_excerpt()
 */
function excerpt_more() {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
}
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');

function get_custom_excerpt($limit, $source = null){ // Custom Excerpt function by character count

    if($source == "content" ? ($excerpt = get_the_content()) : ($excerpt = get_the_excerpt()));
	    $excerpt = preg_replace(" (\[.*?\])",'',$excerpt);
	    $excerpt = strip_shortcodes($excerpt);
	    $excerpt = strip_tags($excerpt);
	    $excerpt = substr($excerpt, 0, $limit);
	    $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
	    $excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
	    //$excerpt = $excerpt.'... <a href="'.get_permalink($post->ID).'">more</a>';
		$excerpt = $excerpt . '...';
    return $excerpt;
}

add_action('init', __NAMESPACE__ . '\\setup_init', 0);
 
function setup_init() {
	
	add_theme_support( 'post-thumbnails' );
	
	add_image_size('block-thumbnail', 360, 200, true);
	add_image_size('work-thumbnail', 360, 360, true);
}



//add_action( 'add_meta_boxes', __NAMESPACE__ . '\\cd_meta_box_add' );
// function cd_meta_box_add()
// {
	// //debug_to_console('add meta box');
    // add_meta_box( 'location-info-meta-box', 'Location Information Fields', __NAMESPACE__ . '\\cd_meta_box_cb', 'array("events",jobs")', 'normal', 'high' );
// }


add_action( 'add_meta_boxes', __NAMESPACE__ . '\\cd_meta_box_add' );

function cd_meta_box_add($postType) {
	$types = array('events', 'jobs', 'members');
	if(in_array($postType, $types)){
		add_meta_box(
				'location-info-meta-box',
				'Location Information Fields',
				__NAMESPACE__ . '\\cd_meta_box_cb',
				$postType,
				'normal', 
				'high'
		);
	}
}

function cd_meta_box_cb()
{
	//debug_to_console('meta box callback');
    // $post is already set, and contains an object: the WordPress post
    global $post;
    $values = get_post_custom( $post->ID );
    $textCity = isset( $values['my_meta_box_city_text'] ) ? $values['my_meta_box_city_text'] : ' ';
    $selectedState = isset( $values['my_meta_box_state_select'] ) ? esc_attr( $values['my_meta_box_state_select'][0] ) : '';
	$selectedCountry = isset( $values['my_meta_box_country_select'] ) ? esc_attr( $values['my_meta_box_country_select'][0] ) : '';
    //$check = isset( $values['my_meta_box_check'] ) ? esc_attr( $values['my_meta_box_check'] ) : '';
 	
    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
    ?>
	<!-- <p>Location Fields
	</p> -->
    <p>
        <div class="rwmb-label"><label for="my_meta_box_city_text">City</label></div>
        <div class="rwmb-input"><input type="text" name="my_meta_box_city_text" id="my_meta_box_city_text" value="<?php echo $textCity[0]; ?>" /></div>
    </p>
    <p>
        <div class="rwmb-label"><label for="my_meta_box_state_select">State</label></div>
        <div class="rwmb-input">
	        <select name="my_meta_box_state_select" id="my_meta_box_state_select">
				<option> - Select Province/State - </option>
				<option value="None" <?php selected( $selectedState, "None" ); ?> >Not Applicable</option> 
				<option value="AL" <?php selected( $selectedState, "AL" ); ?> >Alabama</option> 
				<option value="AK" <?php selected( $selectedState, "AK" ); ?> >Alaska</option> 
				<option value="AZ" <?php selected( $selectedState, "AZ" ); ?> >Arizona</option> 
				<option value="AR" <?php selected( $selectedState, "AR" ); ?> >Arkansas</option> 
				<option value="CA" <?php selected( $selectedState, "CA" ); ?> >California</option> 
				<option value="CO" <?php selected( $selectedState, "CO" ); ?> >Colorado</option> 
				<option value="CT" <?php selected( $selectedState, "CT" ); ?> >Connecticut</option> 
				<option value="DE" <?php selected( $selectedState, "DE" ); ?> >Delaware</option> 
				<option value="DC" <?php selected( $selectedState, "DC" ); ?> >District Of Columbia</option> 
				<option value="FL" <?php selected( $selectedState, "FL" ); ?> >Florida</option> 
				<option value="GA" <?php selected( $selectedState, "GA" ); ?> >Georgia</option> 
				<option value="HI" <?php selected( $selectedState, "HI" ); ?> >Hawaii</option> 
				<option value="ID" <?php selected( $selectedState, "ID" ); ?> >Idaho</option> 
				<option value="IL" <?php selected( $selectedState, "IL" ); ?> >Illinois</option> 
				<option value="IN" <?php selected( $selectedState, "IN" ); ?> >Indiana</option> 
				<option value="IA" <?php selected( $selectedState, "IA" ); ?> >Iowa</option> 
				<option value="KS" <?php selected( $selectedState, "KS" ); ?> >Kansas</option> 
				<option value="KY" <?php selected( $selectedState, "KY" ); ?> >Kentucky</option> 
				<option value="LA" <?php selected( $selectedState, "LA" ); ?> >Louisiana</option> 
				<option value="ME" <?php selected( $selectedState, "ME" ); ?> >Maine</option> 
				<option value="MD" <?php selected( $selectedState, "MD" ); ?> >Maryland</option> 
				<option value="MA" <?php selected( $selectedState, "MA" ); ?> >Massachusetts</option> 
				<option value="MI" <?php selected( $selectedState, "MI" ); ?> >Michigan</option> 
				<option value="MN" <?php selected( $selectedState, "MN" ); ?> >Minnesota</option> 
				<option value="MS" <?php selected( $selectedState, "MS" ); ?> >Mississippi</option> 
				<option value="MO" <?php selected( $selectedState, "MO" ); ?> >Missouri</option> 
				<option value="MT" <?php selected( $selectedState, "MT" ); ?> >Montana</option> 
				<option value="NE" <?php selected( $selectedState, "NE" ); ?> >Nebraska</option> 
				<option value="NV" <?php selected( $selectedState, "NV" ); ?> >Nevada</option> 
				<option value="NH" <?php selected( $selectedState, "NH" ); ?> >New Hampshire</option> 
				<option value="NJ" <?php selected( $selectedState, "NJ" ); ?> >New Jersey</option> 
				<option value="NM" <?php selected( $selectedState, "NM" ); ?> >New Mexico</option> 
				<option value="NY" <?php selected( $selectedState, "NY" ); ?> >New York</option> 
				<option value="NC" <?php selected( $selectedState, "NC" ); ?> >North Carolina</option> 
				<option value="ND" <?php selected( $selectedState, "ND" ); ?> >North Dakota</option> 
				<option value="OH" <?php selected( $selectedState, "OH" ); ?> >Ohio</option> 
				<option value="OK" <?php selected( $selectedState, "OK" ); ?> >Oklahoma</option> 
				<option value="OR" <?php selected( $selectedState, "OR" ); ?> >Oregon</option> 
				<option value="PA" <?php selected( $selectedState, "PA" ); ?> >Pennsylvania</option> 
				<option value="RI" <?php selected( $selectedState, "RI" ); ?> >Rhode Island</option> 
				<option value="SC" <?php selected( $selectedState, "SC" ); ?> >South Carolina</option> 
				<option value="SD" <?php selected( $selectedState, "SD" ); ?> >South Dakota</option> 
				<option value="TN" <?php selected( $selectedState, "TN" ); ?> >Tennessee</option> 
				<option value="TX" <?php selected( $selectedState, "TX" ); ?> >Texas</option> 
				<option value="UT" <?php selected( $selectedState, "UT" ); ?> >Utah</option> 
				<option value="VT" <?php selected( $selectedState, "VT" ); ?> >Vermont</option> 
				<option value="VA" <?php selected( $selectedState, "VA" ); ?> >Virginia</option> 
				<option value="WA" <?php selected( $selectedState, "WA" ); ?> >Washington</option> 
				<option value="WV" <?php selected( $selectedState, "WV" ); ?> >West Virginia</option> 
				<option value="WI" <?php selected( $selectedState, "WI" ); ?> >Wisconsin</option> 
				<option value="WY" <?php selected( $selectedState, "WY" ); ?> >Wyoming</option>
				<option> ---------------- </option>
				<option value="AB" <?php selected( $selectedState, 'AB' ); ?> >Alberta</option>
				<option value="BC" <?php selected( $selectedState, "BC" ); ?> >British Columbia</option>
				<option value="MB" <?php selected( $selectedState, "MB" ); ?> >Manitoba</option>
				<option value="NB" <?php selected( $selectedState, "NB" ); ?> >New Brunswick</option>
				<option value="NL" <?php selected( $selectedState, "NL" ); ?> >Newfoundland and Labrador</option>
				<option value="NS" <?php selected( $selectedState, "NS" ); ?> >Nova Scotia</option>
				<option value="NT" <?php selected( $selectedState, "NT" ); ?> >Northwest Territories</option>
				<option value="NU" <?php selected( $selectedState, "NU" ); ?> >Nunavut</option>
				<option value="ON" <?php selected( $selectedState, "ON" ); ?> >Ontario</option>
				<option value="PE" <?php selected( $selectedState, "PE" ); ?> >Prince Edward Island</option>
				<option value="QC" <?php selected( $selectedState, "QC" ); ?> >Quebec</option>
				<option value="SK" <?php selected( $selectedState, "SK" ); ?> >Saskatchewan</option>
				<option value="YT" <?php selected( $selectedState, "YT" ); ?> >Yukon</option>
			</select>
		</div>
    </p>	
    <p>
        <div class="rwmb-label"><label for="my_meta_box_country_select">Country</label></div>
        <div class="rwmb-input">
        	<select name="my_meta_box_country_select" id="my_meta_box_country_select">
        		<option value="US" <?php selected( $selectedCountry, 'US' ); ?> >United States</option>
				<option value="AF" <?php selected( $selectedCountry, "AF" ); ?> >Afghanistan</option>
				<option value="AX" <?php selected( $selectedCountry, "AX" ); ?> >Åland Islands</option>
				<option value="AL" <?php selected( $selectedCountry, "AL" ); ?> >Albania</option>
				<option value="DZ" <?php selected( $selectedCountry, "DZ" ); ?> >Algeria</option>
				<option value="AS" <?php selected( $selectedCountry, "AS" ); ?> >American Samoa</option>
				<option value="AD" <?php selected( $selectedCountry, "AD" ); ?> >Andorra</option>
				<option value="AO" <?php selected( $selectedCountry, "AO" ); ?> >Angola</option>
				<option value="AI" <?php selected( $selectedCountry, "AI" ); ?> >Anguilla</option>
				<option value="AQ" <?php selected( $selectedCountry, "AQ" ); ?> >Antarctica</option>
				<option value="AG" <?php selected( $selectedCountry, "AG" ); ?> >Antigua and Barbuda</option>
				<option value="AR" <?php selected( $selectedCountry, "AR" ); ?> >Argentina</option>
				<option value="AM" <?php selected( $selectedCountry, "AM" ); ?> >Armenia</option>
				<option value="AW" <?php selected( $selectedCountry, "AW" ); ?> >Aruba</option>
				<option value="AU" <?php selected( $selectedCountry, "AU" ); ?> >Australia</option>
				<option value="AT" <?php selected( $selectedCountry, "AT" ); ?> >Austria</option>
				<option value="AZ" <?php selected( $selectedCountry, "AZ" ); ?> >Azerbaijan</option>
				<option value="BS" <?php selected( $selectedCountry, "BS" ); ?> >Bahamas</option>
				<option value="BH" <?php selected( $selectedCountry, "BH" ); ?> >Bahrain</option>
				<option value="BD" <?php selected( $selectedCountry, "BD" ); ?> >Bangladesh</option>
				<option value="BB" <?php selected( $selectedCountry, "BB" ); ?> >Barbados</option>
				<option value="BY" <?php selected( $selectedCountry, "BY" ); ?> >Belarus</option>
				<option value="BE" <?php selected( $selectedCountry, "BE" ); ?> >Belgium</option>
				<option value="BZ" <?php selected( $selectedCountry, "BZ" ); ?> >Belize</option>
				<option value="BJ" <?php selected( $selectedCountry, "BJ" ); ?> >Benin</option>
				<option value="BM" <?php selected( $selectedCountry, "BM" ); ?> >Bermuda</option>
				<option value="BT" <?php selected( $selectedCountry, "BT" ); ?> >Bhutan</option>
				<option value="BO" <?php selected( $selectedCountry, "BO" ); ?> >Bolivia, Plurinational State of</option>
				<option value="BQ" <?php selected( $selectedCountry, "BQ" ); ?> >Bonaire, Sint Eustatius and Saba</option>
				<option value="BA" <?php selected( $selectedCountry, "BA" ); ?> >Bosnia and Herzegovina</option>
				<option value="BW" <?php selected( $selectedCountry, "BW" ); ?> >Botswana</option>
				<option value="BV" <?php selected( $selectedCountry, "BV" ); ?> >Bouvet Island</option>
				<option value="BR" <?php selected( $selectedCountry, "BR" ); ?> >Brazil</option>
				<option value="IO" <?php selected( $selectedCountry, "IO" ); ?> >British Indian Ocean Territory</option>
				<option value="BN" <?php selected( $selectedCountry, "BN" ); ?> >Brunei Darussalam</option>
				<option value="BG" <?php selected( $selectedCountry, "BG" ); ?> >Bulgaria</option>
				<option value="BF" <?php selected( $selectedCountry, "BF" ); ?> >Burkina Faso</option>
				<option value="BI" <?php selected( $selectedCountry, "BI" ); ?> >Burundi</option>
				<option value="KH" <?php selected( $selectedCountry, "KH" ); ?> >Cambodia</option>
				<option value="CM" <?php selected( $selectedCountry, "CM" ); ?> >Cameroon</option>
				<option value="CA" <?php selected( $selectedCountry, "CA" ); ?> >Canada</option>
				<option value="CV" <?php selected( $selectedCountry, "CV" ); ?> >Cape Verde</option>
				<option value="KY" <?php selected( $selectedCountry, "KY" ); ?> >Cayman Islands</option>
				<option value="CF" <?php selected( $selectedCountry, "CF" ); ?> >Central African Republic</option>
				<option value="TD" <?php selected( $selectedCountry, "TD" ); ?> >Chad</option>
				<option value="CL" <?php selected( $selectedCountry, "CL" ); ?> >Chile</option>
				<option value="CN" <?php selected( $selectedCountry, "CN" ); ?> >China</option>
				<option value="CX" <?php selected( $selectedCountry, "CX" ); ?> >Christmas Island</option>
				<option value="CC" <?php selected( $selectedCountry, "CC" ); ?> >Cocos (Keeling) Islands</option>
				<option value="CO" <?php selected( $selectedCountry, "CO" ); ?> >Colombia</option>
				<option value="KM" <?php selected( $selectedCountry, "KM" ); ?> >Comoros</option>
				<option value="CG" <?php selected( $selectedCountry, "CG" ); ?> >Congo</option>
				<option value="CD" <?php selected( $selectedCountry, "CD" ); ?> >Congo, the Democratic Republic of the</option>
				<option value="CK" <?php selected( $selectedCountry, "CK" ); ?> >Cook Islands</option>
				<option value="CR" <?php selected( $selectedCountry, "CR" ); ?> >Costa Rica</option>
				<option value="CI" <?php selected( $selectedCountry, "CI" ); ?> >Côte d'Ivoire</option>
				<option value="HR" <?php selected( $selectedCountry, "HR" ); ?> >Croatia</option>
				<option value="CU" <?php selected( $selectedCountry, "CU" ); ?> >Cuba</option>
				<option value="CW" <?php selected( $selectedCountry, "CW" ); ?> >Curaçao</option>
				<option value="CY" <?php selected( $selectedCountry, "CY" ); ?> >Cyprus</option>
				<option value="CZ" <?php selected( $selectedCountry, "CZ" ); ?> >Czech Republic</option>
				<option value="DK" <?php selected( $selectedCountry, "DK" ); ?> >Denmark</option>
				<option value="DJ" <?php selected( $selectedCountry, "DJ" ); ?> >Djibouti</option>
				<option value="DM" <?php selected( $selectedCountry, "DM" ); ?> >Dominica</option>
				<option value="DO" <?php selected( $selectedCountry, "DO" ); ?> >Dominican Republic</option>
				<option value="EC" <?php selected( $selectedCountry, "EC" ); ?> >Ecuador</option>
				<option value="EG" <?php selected( $selectedCountry, "EG" ); ?> >Egypt</option>
				<option value="SV" <?php selected( $selectedCountry, "SV" ); ?> >El Salvador</option>
				<option value="GQ" <?php selected( $selectedCountry, "GQ" ); ?> >Equatorial Guinea</option>
				<option value="ER" <?php selected( $selectedCountry, "ER" ); ?> >Eritrea</option>
				<option value="EE" <?php selected( $selectedCountry, "EE" ); ?> >Estonia</option>
				<option value="ET" <?php selected( $selectedCountry, "ET" ); ?> >Ethiopia</option>
				<option value="FK" <?php selected( $selectedCountry, "FK" ); ?> >Falkland Islands (Malvinas)</option>
				<option value="FO" <?php selected( $selectedCountry, "FO" ); ?> >Faroe Islands</option>
				<option value="FJ" <?php selected( $selectedCountry, "FJ" ); ?> >Fiji</option>
				<option value="FI" <?php selected( $selectedCountry, "FI" ); ?> >Finland</option>
				<option value="FR" <?php selected( $selectedCountry, "FR" ); ?> >France</option>
				<option value="GF" <?php selected( $selectedCountry, "GF" ); ?> >French Guiana</option>
				<option value="PF" <?php selected( $selectedCountry, "PF" ); ?> >French Polynesia</option>
				<option value="TF" <?php selected( $selectedCountry, "TF" ); ?> >French Southern Territories</option>
				<option value="GA" <?php selected( $selectedCountry, "GA" ); ?> >Gabon</option>
				<option value="GM" <?php selected( $selectedCountry, "GM" ); ?> >Gambia</option>
				<option value="GE" <?php selected( $selectedCountry, "GE" ); ?> >Georgia</option>
				<option value="DE" <?php selected( $selectedCountry, "DE" ); ?> >Germany</option>
				<option value="GH" <?php selected( $selectedCountry, "GH" ); ?> >Ghana</option>
				<option value="GI" <?php selected( $selectedCountry, "GI" ); ?> >Gibraltar</option>
				<option value="GR" <?php selected( $selectedCountry, "GR" ); ?> >Greece</option>
				<option value="GL" <?php selected( $selectedCountry, "GL" ); ?> >Greenland</option>
				<option value="GD" <?php selected( $selectedCountry, "GD" ); ?> >Grenada</option>
				<option value="GP" <?php selected( $selectedCountry, "GP" ); ?> >Guadeloupe</option>
				<option value="GU" <?php selected( $selectedCountry, "GU" ); ?> >Guam</option>
				<option value="GT" <?php selected( $selectedCountry, "GT" ); ?> >Guatemala</option>
				<option value="GG" <?php selected( $selectedCountry, "GG" ); ?> >Guernsey</option>
				<option value="GN" <?php selected( $selectedCountry, "GN" ); ?> >Guinea</option>
				<option value="GW" <?php selected( $selectedCountry, "GW" ); ?> >Guinea-Bissau</option>
				<option value="GY" <?php selected( $selectedCountry, "GY" ); ?> >Guyana</option>
				<option value="HT" <?php selected( $selectedCountry, "HT" ); ?> >Haiti</option>
				<option value="HM" <?php selected( $selectedCountry, "HM" ); ?> >Heard Island and McDonald Islands</option>
				<option value="VA" <?php selected( $selectedCountry, "VA" ); ?> >Holy See (Vatican City State)</option>
				<option value="HN" <?php selected( $selectedCountry, "HN" ); ?> >Honduras</option>
				<option value="HK" <?php selected( $selectedCountry, "HK" ); ?> >Hong Kong</option>
				<option value="HU" <?php selected( $selectedCountry, "HU" ); ?> >Hungary</option>
				<option value="IS" <?php selected( $selectedCountry, "IS" ); ?> >Iceland</option>
				<option value="IN" <?php selected( $selectedCountry, "IN" ); ?> >India</option>
				<option value="ID" <?php selected( $selectedCountry, "ID" ); ?> >Indonesia</option>
				<option value="IR" <?php selected( $selectedCountry, "IR" ); ?> >Iran, Islamic Republic of</option>
				<option value="IQ" <?php selected( $selectedCountry, "IQ" ); ?> >Iraq</option>
				<option value="IE" <?php selected( $selectedCountry, "IE" ); ?> >Ireland</option>
				<option value="IM" <?php selected( $selectedCountry, "IM" ); ?> >Isle of Man</option>
				<option value="IL" <?php selected( $selectedCountry, "IL" ); ?> >Israel</option>
				<option value="IT" <?php selected( $selectedCountry, "IT" ); ?> >Italy</option>
				<option value="JM" <?php selected( $selectedCountry, "JM" ); ?> >Jamaica</option>
				<option value="JP" <?php selected( $selectedCountry, "JP" ); ?> >Japan</option>
				<option value="JE" <?php selected( $selectedCountry, "JE" ); ?> >Jersey</option>
				<option value="JO" <?php selected( $selectedCountry, "JO" ); ?> >Jordan</option>
				<option value="KZ" <?php selected( $selectedCountry, "KZ" ); ?> >Kazakhstan</option>
				<option value="KE" <?php selected( $selectedCountry, "KE" ); ?> >Kenya</option>
				<option value="KI" <?php selected( $selectedCountry, "KI" ); ?> >Kiribati</option>
				<option value="KP" <?php selected( $selectedCountry, "KP" ); ?> >Korea, Democratic People's Republic of</option>
				<option value="KR" <?php selected( $selectedCountry, "KR" ); ?> >Korea, Republic of</option>
				<option value="KW" <?php selected( $selectedCountry, "KW" ); ?> >Kuwait</option>
				<option value="KG" <?php selected( $selectedCountry, "KG" ); ?> >Kyrgyzstan</option>
				<option value="LA" <?php selected( $selectedCountry, "LA" ); ?> >Lao People's Democratic Republic</option>
				<option value="LV" <?php selected( $selectedCountry, "LV" ); ?> >Latvia</option>
				<option value="LB" <?php selected( $selectedCountry, "LB" ); ?> >Lebanon</option>
				<option value="LS" <?php selected( $selectedCountry, "LS" ); ?> >Lesotho</option>
				<option value="LR" <?php selected( $selectedCountry, "LR" ); ?> >Liberia</option>
				<option value="LY" <?php selected( $selectedCountry, "LY" ); ?> >Libya</option>
				<option value="LI" <?php selected( $selectedCountry, "LI" ); ?> >Liechtenstein</option>
				<option value="LT" <?php selected( $selectedCountry, "LT" ); ?> >Lithuania</option>
				<option value="LU" <?php selected( $selectedCountry, "LU" ); ?> >Luxembourg</option>
				<option value="MO" <?php selected( $selectedCountry, "MO" ); ?> >Macao</option>
				<option value="MK" <?php selected( $selectedCountry, "MK" ); ?> >Macedonia, the former Yugoslav Republic of</option>
				<option value="MG" <?php selected( $selectedCountry, "MG" ); ?> >Madagascar</option>
				<option value="MW" <?php selected( $selectedCountry, "MW" ); ?> >Malawi</option>
				<option value="MY" <?php selected( $selectedCountry, "MY" ); ?> >Malaysia</option>
				<option value="MV" <?php selected( $selectedCountry, "MV" ); ?> >Maldives</option>
				<option value="ML" <?php selected( $selectedCountry, "ML" ); ?> >Mali</option>
				<option value="MT" <?php selected( $selectedCountry, "MT" ); ?> >Malta</option>
				<option value="MH" <?php selected( $selectedCountry, "MH" ); ?> >Marshall Islands</option>
				<option value="MQ" <?php selected( $selectedCountry, "MQ" ); ?> >Martinique</option>
				<option value="MR" <?php selected( $selectedCountry, "MR" ); ?> >Mauritania</option>
				<option value="MU" <?php selected( $selectedCountry, "MU" ); ?> >Mauritius</option>
				<option value="YT" <?php selected( $selectedCountry, "YT" ); ?> >Mayotte</option>
				<option value="MX" <?php selected( $selectedCountry, "MX" ); ?> >Mexico</option>
				<option value="FM" <?php selected( $selectedCountry, "FM" ); ?> >Micronesia, Federated States of</option>
				<option value="MD" <?php selected( $selectedCountry, "MD" ); ?> >Moldova, Republic of</option>
				<option value="MC" <?php selected( $selectedCountry, "MC" ); ?> >Monaco</option>
				<option value="MN" <?php selected( $selectedCountry, "MN" ); ?> >Mongolia</option>
				<option value="ME" <?php selected( $selectedCountry, "ME" ); ?> >Montenegro</option>
				<option value="MS" <?php selected( $selectedCountry, "MS" ); ?> >Montserrat</option>
				<option value="MA" <?php selected( $selectedCountry, "MA" ); ?> >Morocco</option>
				<option value="MZ" <?php selected( $selectedCountry, "MZ" ); ?> >Mozambique</option>
				<option value="MM" <?php selected( $selectedCountry, "MM" ); ?> >Myanmar</option>
				<option value="NA" <?php selected( $selectedCountry, "NA" ); ?> >Namibia</option>
				<option value="NR" <?php selected( $selectedCountry, "NR" ); ?> >Nauru</option>
				<option value="NP" <?php selected( $selectedCountry, "NP" ); ?> >Nepal</option>
				<option value="NL" <?php selected( $selectedCountry, "NL" ); ?> >Netherlands</option>
				<option value="NC" <?php selected( $selectedCountry, "NC" ); ?> >New Caledonia</option>
				<option value="NZ" <?php selected( $selectedCountry, "NZ" ); ?> >New Zealand</option>
				<option value="NI" <?php selected( $selectedCountry, "NI" ); ?> >Nicaragua</option>
				<option value="NE" <?php selected( $selectedCountry, "NE" ); ?> >Niger</option>
				<option value="NG" <?php selected( $selectedCountry, "NG" ); ?> >Nigeria</option>
				<option value="NU" <?php selected( $selectedCountry, "NU" ); ?> >Niue</option>
				<option value="NF" <?php selected( $selectedCountry, "NF" ); ?> >Norfolk Island</option>
				<option value="MP" <?php selected( $selectedCountry, "MP" ); ?> >Northern Mariana Islands</option>
				<option value="NO" <?php selected( $selectedCountry, "NO" ); ?> >Norway</option>
				<option value="OM" <?php selected( $selectedCountry, "OM" ); ?> >Oman</option>
				<option value="PK" <?php selected( $selectedCountry, "PK" ); ?> >Pakistan</option>
				<option value="PW" <?php selected( $selectedCountry, "PW" ); ?> >Palau</option>
				<option value="PS" <?php selected( $selectedCountry, "PS" ); ?> >Palestinian Territory, Occupied</option>
				<option value="PA" <?php selected( $selectedCountry, "PA" ); ?> >Panama</option>
				<option value="PG" <?php selected( $selectedCountry, "PG" ); ?> >Papua New Guinea</option>
				<option value="PY" <?php selected( $selectedCountry, "PY" ); ?> >Paraguay</option>
				<option value="PE" <?php selected( $selectedCountry, "PE" ); ?> >Peru</option>
				<option value="PH" <?php selected( $selectedCountry, "PH" ); ?> >Philippines</option>
				<option value="PN" <?php selected( $selectedCountry, "PN" ); ?> >Pitcairn</option>
				<option value="PL" <?php selected( $selectedCountry, "PL" ); ?> >Poland</option>
				<option value="PT" <?php selected( $selectedCountry, "PT" ); ?> >Portugal</option>
				<option value="PR" <?php selected( $selectedCountry, "PR" ); ?> >Puerto Rico</option>
				<option value="QA" <?php selected( $selectedCountry, "QA" ); ?> >Qatar</option>
				<option value="RE" <?php selected( $selectedCountry, "RE" ); ?> >Réunion</option>
				<option value="RO" <?php selected( $selectedCountry, "RO" ); ?> >Romania</option>
				<option value="RU" <?php selected( $selectedCountry, "RU" ); ?> >Russian Federation</option>
				<option value="RW" <?php selected( $selectedCountry, "RW" ); ?> >Rwanda</option>
				<option value="BL" <?php selected( $selectedCountry, "BL" ); ?> >Saint Barthélemy</option>
				<option value="SH" <?php selected( $selectedCountry, "SH" ); ?> >Saint Helena, Ascension and Tristan da Cunha</option>
				<option value="KN" <?php selected( $selectedCountry, "KN" ); ?> >Saint Kitts and Nevis</option>
				<option value="LC" <?php selected( $selectedCountry, "LC" ); ?> >Saint Lucia</option>
				<option value="MF" <?php selected( $selectedCountry, "MF" ); ?> >Saint Martin (French part)</option>
				<option value="PM" <?php selected( $selectedCountry, "PM" ); ?> >Saint Pierre and Miquelon</option>
				<option value="VC" <?php selected( $selectedCountry, "VC" ); ?> >Saint Vincent and the Grenadines</option>
				<option value="WS" <?php selected( $selectedCountry, "WS" ); ?> >Samoa</option>
				<option value="SM" <?php selected( $selectedCountry, "SM" ); ?> >San Marino</option>
				<option value="ST" <?php selected( $selectedCountry, "ST" ); ?> >Sao Tome and Principe</option>
				<option value="SA" <?php selected( $selectedCountry, "SA" ); ?> >Saudi Arabia</option>
				<option value="SN" <?php selected( $selectedCountry, "SN" ); ?> >Senegal</option>
				<option value="RS" <?php selected( $selectedCountry, "RS" ); ?> >Serbia</option>
				<option value="SC" <?php selected( $selectedCountry, "SC" ); ?> >Seychelles</option>
				<option value="SL" <?php selected( $selectedCountry, "SL" ); ?> >Sierra Leone</option>
				<option value="SG" <?php selected( $selectedCountry, "SG" ); ?> >Singapore</option>
				<option value="SX" <?php selected( $selectedCountry, "SX" ); ?> >Sint Maarten (Dutch part)</option>
				<option value="SK" <?php selected( $selectedCountry, "SK" ); ?> >Slovakia</option>
				<option value="SI" <?php selected( $selectedCountry, "SI" ); ?> >Slovenia</option>
				<option value="SB" <?php selected( $selectedCountry, "SB" ); ?> >Solomon Islands</option>
				<option value="SO" <?php selected( $selectedCountry, "SO" ); ?> >Somalia</option>
				<option value="ZA" <?php selected( $selectedCountry, "ZA" ); ?> >South Africa</option>
				<option value="GS" <?php selected( $selectedCountry, "GS" ); ?> >South Georgia and the South Sandwich Islands</option>
				<option value="SS" <?php selected( $selectedCountry, "SS" ); ?> >South Sudan</option>
				<option value="ES" <?php selected( $selectedCountry, "ES" ); ?> >Spain</option>
				<option value="LK" <?php selected( $selectedCountry, "LK" ); ?> >Sri Lanka</option>
				<option value="SD" <?php selected( $selectedCountry, "SD" ); ?> >Sudan</option>
				<option value="SR" <?php selected( $selectedCountry, "SR" ); ?> >Suriname</option>
				<option value="SJ" <?php selected( $selectedCountry, "SJ" ); ?> >Svalbard and Jan Mayen</option>
				<option value="SZ" <?php selected( $selectedCountry, "SZ" ); ?> >Swaziland</option>
				<option value="SE" <?php selected( $selectedCountry, "SE" ); ?> >Sweden</option>
				<option value="CH" <?php selected( $selectedCountry, "CH" ); ?> >Switzerland</option>
				<option value="SY" <?php selected( $selectedCountry, "SY" ); ?> >Syrian Arab Republic</option>
				<option value="TW" <?php selected( $selectedCountry, "TW" ); ?> >Taiwan, Province of China</option>
				<option value="TJ" <?php selected( $selectedCountry, "TJ" ); ?> >Tajikistan</option>
				<option value="TZ" <?php selected( $selectedCountry, "TZ" ); ?> >Tanzania, United Republic of</option>
				<option value="TH" <?php selected( $selectedCountry, "TH" ); ?> >Thailand</option>
				<option value="TL" <?php selected( $selectedCountry, "TL" ); ?> >Timor-Leste</option>
				<option value="TG" <?php selected( $selectedCountry, "TG" ); ?> >Togo</option>
				<option value="TK" <?php selected( $selectedCountry, "TK" ); ?> >Tokelau</option>
				<option value="TO" <?php selected( $selectedCountry, "TO" ); ?> >Tonga</option>
				<option value="TT" <?php selected( $selectedCountry, "TT" ); ?> >Trinidad and Tobago</option>
				<option value="TN" <?php selected( $selectedCountry, "TN" ); ?> >Tunisia</option>
				<option value="TR" <?php selected( $selectedCountry, "TR" ); ?> >Turkey</option>
				<option value="TM" <?php selected( $selectedCountry, "TM" ); ?> >Turkmenistan</option>
				<option value="TC" <?php selected( $selectedCountry, "TC" ); ?> >Turks and Caicos Islands</option>
				<option value="TV" <?php selected( $selectedCountry, "TV" ); ?> >Tuvalu</option>
				<option value="UG" <?php selected( $selectedCountry, "UG" ); ?> >Uganda</option>
				<option value="UA" <?php selected( $selectedCountry, "UA" ); ?> >Ukraine</option>
				<option value="AE" <?php selected( $selectedCountry, "AE" ); ?> >United Arab Emirates</option>
				<option value="GB" <?php selected( $selectedCountry, "GB" ); ?> >United Kingdom</option>
				<option value="USA" <?php selected( $selectedCountry, "US" ); ?> >United States</option>
				<option value="UM" <?php selected( $selectedCountry, "UM" ); ?> >United States Minor Outlying Islands</option>
				<option value="UY" <?php selected( $selectedCountry, "UY" ); ?> >Uruguay</option>
				<option value="UZ" <?php selected( $selectedCountry, "UZ" ); ?> >Uzbekistan</option>
				<option value="VU" <?php selected( $selectedCountry, "VU" ); ?> >Vanuatu</option>
				<option value="VE" <?php selected( $selectedCountry, "VE" ); ?> >Venezuela, Bolivarian Republic of</option>
				<option value="VN" <?php selected( $selectedCountry, "VN" ); ?> >Viet Nam</option>
				<option value="VG" <?php selected( $selectedCountry, "VG" ); ?> >Virgin Islands, British</option>
				<option value="VI" <?php selected( $selectedCountry, "VI" ); ?> >Virgin Islands, U.S.</option>
				<option value="WF" <?php selected( $selectedCountry, "WF" ); ?> >Wallis and Futuna</option>
				<option value="EH" <?php selected( $selectedCountry, "EH" ); ?> >Western Sahara</option>
				<option value="YE" <?php selected( $selectedCountry, "YE" ); ?> >Yemen</option>
				<option value="ZM" <?php selected( $selectedCountry, "ZM" ); ?> >Zambia</option>
				<option value="ZW" <?php selected( $selectedCountry, "ZW" ); ?> >Zimbabwe</option>
			</select>	
        </div>
    </p>
 
    <?php    
}

add_action( 'save_post', __NAMESPACE__ . '\\cd_meta_box_save' );
function cd_meta_box_save( $post_id )
{
	//debug_to_console('meta box save');
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
     
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post', $post_id ) ) return; //'edit_post'
     
    // now we can actually save the data
    $allowed = array( 
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );
     
    // Make sure your data is set before trying to save it
    if( isset( $_POST['my_meta_box_city_text'] ) )
        update_post_meta( $post_id, 'my_meta_box_city_text', wp_kses( $_POST['my_meta_box_city_text'], $allowed ) );
         
    if( isset( $_POST['my_meta_box_state_select'] ) )
        update_post_meta( $post_id, 'my_meta_box_state_select', esc_attr( $_POST['my_meta_box_state_select'] ) );
	
    if( isset( $_POST['my_meta_box_country_select'] ) )
        update_post_meta( $post_id, 'my_meta_box_country_select', esc_attr( $_POST['my_meta_box_country_select'] ) );
}
