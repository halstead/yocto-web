<?php

function convertState($strInput, $strFormat='name', $blnDefaultToInput=false) {
	$arrStates = array(
		array('code'=>'01', 'abbrev'=>'AL', 'name'=>'Alabama'),
		array('code'=>'02', 'abbrev'=>'AK', 'name'=>'Alaska'),
		array('code'=>'04', 'abbrev'=>'AZ', 'name'=>'Arizona'),
		array('code'=>'05', 'abbrev'=>'AR', 'name'=>'Arkansas'),
		array('code'=>'06', 'abbrev'=>'CA', 'name'=>'California'),
		array('code'=>'08', 'abbrev'=>'CO', 'name'=>'Colorado'),
		array('code'=>'09', 'abbrev'=>'CT', 'name'=>'Connecticut'),
		array('code'=>'10', 'abbrev'=>'DE', 'name'=>'Delaware'),
		array('code'=>'11', 'abbrev'=>'DC', 'name'=>'District of Columbia'),
		array('code'=>'11', 'abbrev'=>'DC', 'name'=>'Washington DC'),
		array('code'=>'11', 'abbrev'=>'DC', 'name'=>'Washington D.C.'),
		array('code'=>'12', 'abbrev'=>'FL', 'name'=>'Florida'),
		array('code'=>'13', 'abbrev'=>'GA', 'name'=>'Georgia'),
		array('code'=>'15', 'abbrev'=>'HI', 'name'=>'Hawaii'),
		array('code'=>'16', 'abbrev'=>'ID', 'name'=>'Idaho'),
		array('code'=>'17', 'abbrev'=>'IL', 'name'=>'Illinois'),
		array('code'=>'18', 'abbrev'=>'IN', 'name'=>'Indiana'),
		array('code'=>'19', 'abbrev'=>'IA', 'name'=>'Iowa'),
		array('code'=>'20', 'abbrev'=>'KS', 'name'=>'Kansas'),
		array('code'=>'21', 'abbrev'=>'KY', 'name'=>'Kentucky'),
		array('code'=>'22', 'abbrev'=>'LA', 'name'=>'Louisiana'),
		array('code'=>'23', 'abbrev'=>'ME', 'name'=>'Maine'),
		array('code'=>'24', 'abbrev'=>'MD', 'name'=>'Maryland'),
		array('code'=>'25', 'abbrev'=>'MA', 'name'=>'Massachusetts'),
		array('code'=>'26', 'abbrev'=>'MI', 'name'=>'Michigan'),
		array('code'=>'27', 'abbrev'=>'MN', 'name'=>'Minnesota'),
		array('code'=>'28', 'abbrev'=>'MS', 'name'=>'Mississippi'),
		array('code'=>'29', 'abbrev'=>'MO', 'name'=>'Missouri'),
		array('code'=>'30', 'abbrev'=>'MT', 'name'=>'Montana'),
		array('code'=>'31', 'abbrev'=>'NE', 'name'=>'Nebraska'),
		array('code'=>'32', 'abbrev'=>'NV', 'name'=>'Nevada'),
		array('code'=>'33', 'abbrev'=>'NH', 'name'=>'New Hampshire'),
		array('code'=>'34', 'abbrev'=>'NJ', 'name'=>'New Jersey'),
		array('code'=>'35', 'abbrev'=>'NM', 'name'=>'New Mexico'),
		array('code'=>'36', 'abbrev'=>'NY', 'name'=>'New York'),
		array('code'=>'37', 'abbrev'=>'NC', 'name'=>'North Carolina'),
		array('code'=>'38', 'abbrev'=>'ND', 'name'=>'North Dakota'),
		array('code'=>'39', 'abbrev'=>'OH', 'name'=>'Ohio'),
		array('code'=>'40', 'abbrev'=>'OK', 'name'=>'Oklahoma'),
		array('code'=>'41', 'abbrev'=>'OR', 'name'=>'Oregon'),
		array('code'=>'42', 'abbrev'=>'PA', 'name'=>'Pennsylvania'),
		array('code'=>'44', 'abbrev'=>'RI', 'name'=>'Rhode Island'),
		array('code'=>'45', 'abbrev'=>'SC', 'name'=>'South Carolina'),
		array('code'=>'46', 'abbrev'=>'SD', 'name'=>'South Dakota'),
		array('code'=>'47', 'abbrev'=>'TN', 'name'=>'Tennessee'),
		array('code'=>'48', 'abbrev'=>'TX', 'name'=>'Texas'),
		array('code'=>'49', 'abbrev'=>'UT', 'name'=>'Utah'),
		array('code'=>'50', 'abbrev'=>'VT', 'name'=>'Vermont'),
		array('code'=>'51', 'abbrev'=>'VA', 'name'=>'Virginia'),
		array('code'=>'53', 'abbrev'=>'WA', 'name'=>'Washington'),
		array('code'=>'54', 'abbrev'=>'WV', 'name'=>'West Virginia'),
		array('code'=>'55', 'abbrev'=>'WI', 'name'=>'Wisconsin'),
		array('code'=>'56', 'abbrev'=>'WY', 'name'=>'Wyoming'),
		array('code'=>'60', 'abbrev'=>'AS', 'name'=>'American Samoa'),
		array('code'=>'66', 'abbrev'=>'GU', 'name'=>'Guam'),
		array('code'=>'69', 'abbrev'=>'MP', 'name'=>'Northern Mariana Islands'),
		array('code'=>'72', 'abbrev'=>'PR', 'name'=>'Puerto Rico'),
		array('code'=>'78', 'abbrev'=>'VI', 'name'=>'Virgin Islands'),
		array('code'=>'64', 'abbrev'=>'FM', 'name'=>'Federated States of Micronesia'),
		array('code'=>'68', 'abbrev'=>'MH', 'name'=>'Marshall Islands'),
		array('code'=>'70', 'abbrev'=>'PW', 'name'=>'Palau')
	);
 
	$strOutput = ($blnDefaultToInput) ? $strInput : '';
	$strFormat = strtolower(trim($strFormat));
 
	foreach ($arrStates as $arrState) {
		foreach ($arrState as $strValue) {
			if (strtolower($strValue) == strtolower(trim($strInput))) {
				if ($strFormat == 'code') {
					$strOutput = $arrState['code'];
				}
				else if ($strFormat == 'abbrev') {
					$strOutput = $arrState['abbrev'];
				}
				else {
					$strOutput = $arrState['name'];
				}
				break;
			}
		}
	}
 
	return $strOutput;
}

		
function convertCountry($code) {
    switch ($code) {
        case 'AF': return 'Afghanistan';
        case 'AX': return 'Aland Islands';
        case 'AL': return 'Albania';
        case 'DZ': return 'Algeria';
        case 'AS': return 'American Samoa';
        case 'AD': return 'Andorra';
        case 'AO': return 'Angola';
        case 'AI': return 'Anguilla';
        case 'AQ': return 'Antarctica';
        case 'AG': return 'Antigua and Barbuda';
        case 'AR': return 'Argentina';
        case 'AM': return 'Armenia';
        case 'AW': return 'Aruba';
        case 'AU': return 'Australia';
        case 'AT': return 'Austria';
        case 'AZ': return 'Azerbaijan';
        case 'BS': return 'Bahamas the';
        case 'BH': return 'Bahrain';
        case 'BD': return 'Bangladesh';
        case 'BB': return 'Barbados';
        case 'BY': return 'Belarus';
        case 'BE': return 'Belgium';
        case 'BZ': return 'Belize';
        case 'BJ': return 'Benin';
        case 'BM': return 'Bermuda';
        case 'BT': return 'Bhutan';
        case 'BO': return 'Bolivia';
        case 'BA': return 'Bosnia and Herzegovina';
        case 'BW': return 'Botswana';
        case 'BV': return 'Bouvet Island (Bouvetoya)';
        case 'BR': return 'Brazil';
        case 'IO': return 'British Indian Ocean Territory (Chagos Archipelago)';
        case 'VG': return 'British Virgin Islands';
        case 'BN': return 'Brunei Darussalam';
        case 'BG': return 'Bulgaria';
        case 'BF': return 'Burkina Faso';
        case 'BI': return 'Burundi';
        case 'KH': return 'Cambodia';
        case 'CM': return 'Cameroon';
        case 'CA': return 'Canada';
        case 'CV': return 'Cape Verde';
        case 'KY': return 'Cayman Islands';
        case 'CF': return 'Central African Republic';
        case 'TD': return 'Chad';
        case 'CL': return 'Chile';
        case 'CN': return 'China';
        case 'CX': return 'Christmas Island';
        case 'CC': return 'Cocos (Keeling) Islands';
        case 'CO': return 'Colombia';
        case 'KM': return 'Comoros the';
        case 'CD': return 'Congo';
        case 'CG': return 'Congo the';
        case 'CK': return 'Cook Islands';
        case 'CR': return 'Costa Rica';
        case 'CI': return 'Cote d\'Ivoire';
        case 'HR': return 'Croatia';
        case 'CU': return 'Cuba';
        case 'CY': return 'Cyprus';
        case 'CZ': return 'Czech Republic';
        case 'DK': return 'Denmark';
        case 'DJ': return 'Djibouti';
        case 'DM': return 'Dominica';
        case 'DO': return 'Dominican Republic';
        case 'EC': return 'Ecuador';
        case 'EG': return 'Egypt';
        case 'SV': return 'El Salvador';
        case 'GQ': return 'Equatorial Guinea';
        case 'ER': return 'Eritrea';
        case 'EE': return 'Estonia';
        case 'ET': return 'Ethiopia';
        case 'FO': return 'Faroe Islands';
        case 'FK': return 'Falkland Islands (Malvinas)';
        case 'FJ': return 'Fiji the Fiji Islands';
        case 'FI': return 'Finland';
        case 'FR': return 'France, French Republic';
        case 'GF': return 'French Guiana';
        case 'PF': return 'French Polynesia';
        case 'TF': return 'French Southern Territories';
        case 'GA': return 'Gabon';
        case 'GM': return 'Gambia the';
        case 'GE': return 'Georgia';
        case 'DE': return 'Germany';
        case 'GH': return 'Ghana';
        case 'GI': return 'Gibraltar';
        case 'GR': return 'Greece';
        case 'GL': return 'Greenland';
        case 'GD': return 'Grenada';
        case 'GP': return 'Guadeloupe';
        case 'GU': return 'Guam';
        case 'GT': return 'Guatemala';
        case 'GG': return 'Guernsey';
        case 'GN': return 'Guinea';
        case 'GW': return 'Guinea-Bissau';
        case 'GY': return 'Guyana';
        case 'HT': return 'Haiti';
        case 'HM': return 'Heard Island and McDonald Islands';
        case 'VA': return 'Holy See (Vatican City State)';
        case 'HN': return 'Honduras';
        case 'HK': return 'Hong Kong';
        case 'HU': return 'Hungary';
        case 'IS': return 'Iceland';
        case 'IN': return 'India';
        case 'ID': return 'Indonesia';
        case 'IR': return 'Iran';
        case 'IQ': return 'Iraq';
        case 'IE': return 'Ireland';
        case 'IM': return 'Isle of Man';
        case 'IL': return 'Israel';
        case 'IT': return 'Italy';
        case 'JM': return 'Jamaica';
        case 'JP': return 'Japan';
        case 'JE': return 'Jersey';
        case 'JO': return 'Jordan';
        case 'KZ': return 'Kazakhstan';
        case 'KE': return 'Kenya';
        case 'KI': return 'Kiribati';
        case 'KP': return 'Democratic Peoples Republic of Korea (North)';
        case 'KR': return 'Republic of Korea (South)';
        case 'KW': return 'Kuwait';
        case 'KG': return 'Kyrgyz Republic';
        case 'LA': return 'Lao';
        case 'LV': return 'Latvia';
        case 'LB': return 'Lebanon';
        case 'LS': return 'Lesotho';
        case 'LR': return 'Liberia';
        case 'LY': return 'Libyan Arab Jamahiriya';
        case 'LI': return 'Liechtenstein';
        case 'LT': return 'Lithuania';
        case 'LU': return 'Luxembourg';
        case 'MO': return 'Macao';
        case 'MK': return 'Macedonia';
        case 'MG': return 'Madagascar';
        case 'MW': return 'Malawi';
        case 'MY': return 'Malaysia';
        case 'MV': return 'Maldives';
        case 'ML': return 'Mali';
        case 'MT': return 'Malta';
        case 'MH': return 'Marshall Islands';
        case 'MQ': return 'Martinique';
        case 'MR': return 'Mauritania';
        case 'MU': return 'Mauritius';
        case 'YT': return 'Mayotte';
        case 'MX': return 'Mexico';
        case 'FM': return 'Micronesia';
        case 'MD': return 'Moldova';
        case 'MC': return 'Monaco';
        case 'MN': return 'Mongolia';
        case 'ME': return 'Montenegro';
        case 'MS': return 'Montserrat';
        case 'MA': return 'Morocco';
        case 'MZ': return 'Mozambique';
        case 'MM': return 'Myanmar';
        case 'NA': return 'Namibia';
        case 'NR': return 'Nauru';
        case 'NP': return 'Nepal';
        case 'AN': return 'Netherlands Antilles';
        case 'NL': return 'Netherlands the';
        case 'NC': return 'New Caledonia';
        case 'NZ': return 'New Zealand';
        case 'NI': return 'Nicaragua';
        case 'NE': return 'Niger';
        case 'NG': return 'Nigeria';
        case 'NU': return 'Niue';
        case 'NF': return 'Norfolk Island';
        case 'MP': return 'Northern Mariana Islands';
        case 'NO': return 'Norway';
        case 'OM': return 'Oman';
        case 'PK': return 'Pakistan';
        case 'PW': return 'Palau';
        case 'PS': return 'Palestinian Territory';
        case 'PA': return 'Panama';
        case 'PG': return 'Papua New Guinea';
        case 'PY': return 'Paraguay';
        case 'PE': return 'Peru';
        case 'PH': return 'Philippines';
        case 'PN': return 'Pitcairn Islands';
        case 'PL': return 'Poland';
        case 'PT': return 'Portugal, Portuguese Republic';
        case 'PR': return 'Puerto Rico';
        case 'QA': return 'Qatar';
        case 'RE': return 'Reunion';
        case 'RO': return 'Romania';
        case 'RU': return 'Russian Federation';
        case 'RW': return 'Rwanda';
        case 'BL': return 'Saint Barthelemy';
        case 'SH': return 'Saint Helena';
        case 'KN': return 'Saint Kitts and Nevis';
        case 'LC': return 'Saint Lucia';
        case 'MF': return 'Saint Martin';
        case 'PM': return 'Saint Pierre and Miquelon';
        case 'VC': return 'Saint Vincent and the Grenadines';
        case 'WS': return 'Samoa';
        case 'SM': return 'San Marino';
        case 'ST': return 'Sao Tome and Principe';
        case 'SA': return 'Saudi Arabia';
        case 'SN': return 'Senegal';
        case 'RS': return 'Serbia';
        case 'SC': return 'Seychelles';
        case 'SL': return 'Sierra Leone';
        case 'SG': return 'Singapore';
        case 'SK': return 'Slovakia (Slovak Republic)';
        case 'SI': return 'Slovenia';
        case 'SB': return 'Solomon Islands';
        case 'SO': return 'Somalia, Somali Republic';
        case 'ZA': return 'South Africa';
        case 'GS': return 'South Georgia and the South Sandwich Islands';
        case 'ES': return 'Spain';
        case 'LK': return 'Sri Lanka';
        case 'SD': return 'Sudan';
        case 'SR': return 'Suriname';
        case 'SJ': return 'Svalbard & Jan Mayen Islands';
        case 'SZ': return 'Swaziland';
        case 'SE': return 'Sweden';
        case 'CH': return 'Switzerland, Swiss Confederation';
        case 'SY': return 'Syrian Arab Republic';
        case 'TW': return 'Taiwan';
        case 'TJ': return 'Tajikistan';
        case 'TZ': return 'Tanzania';
        case 'TH': return 'Thailand';
        case 'TL': return 'Timor-Leste';
        case 'TG': return 'Togo';
        case 'TK': return 'Tokelau';
        case 'TO': return 'Tonga';
        case 'TT': return 'Trinidad and Tobago';
        case 'TN': return 'Tunisia';
        case 'TR': return 'Turkey';
        case 'TM': return 'Turkmenistan';
        case 'TC': return 'Turks and Caicos Islands';
        case 'TV': return 'Tuvalu';
        case 'UG': return 'Uganda';
        case 'UA': return 'Ukraine';
        case 'AE': return 'United Arab Emirates';
        case 'GB': return 'United Kingdom';
        case 'US': return 'United States of America';
		case 'USA': return 'United States of America';
        case 'UM': return 'United States Minor Outlying Islands';
        case 'VI': return 'United States Virgin Islands';
        case 'UY': return 'Uruguay, Eastern Republic of';
        case 'UZ': return 'Uzbekistan';
        case 'VU': return 'Vanuatu';
        case 'VE': return 'Venezuela';
        case 'VN': return 'Vietnam';
        case 'WF': return 'Wallis and Futuna';
        case 'EH': return 'Western Sahara';
        case 'YE': return 'Yemen';
        case 'ZM': return 'Zambia';
        case 'ZW': return 'Zimbabwe';
    }
    return false;
}