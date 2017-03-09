<?php

namespace Affinitybridge\NationBuilder\Endpoint;

use Affinitybridge\NationBuilder\Validator as Validator;

// @see http://nationbuilder.com/people_api

class People extends EndpointAbstract {

  static public function getPersonFields($doSpliceInSubDocs = TRUE) {
    $personFields = [];
    $simpleFields = static::getSimplePersonFields();
    if ($doSpliceInSubDocs) {
      foreach ($simpleFields as $jsonPointer => $fieldDefinition) {
        list($fieldType, $fieldDescription) = $fieldDefinition;
        switch ($fieldType) {
          case Validator::ABBR_PERSON:
            $personFields = array_merge($personFields, static::getPrefixedAbbrPersonFields($jsonPointer));
            break;
          case Validator::ADDRESS:
            $personFields = array_merge($personFields, static::getPrefixedAddressFields($jsonPointer));
            break;
          default:
            $personFields[$jsonPointer] = $fieldDefinition;
            break;
        }
      }
    }
    return $personFields;
  }

  static protected function getSimplePersonFields() {
    return [
      '/active_customer_expires_at' => [Validator::ISO_TIMESTAMP, 'the date at which to consider a customer no longer active'],
      '/active_customer_started_at' => [Validator::ISO_TIMESTAMP, 'the date from which a customer is considered active'],
      '/author_id' => [Validator::INT, 'the resource ID of the person who created this person in the nation'],
      '/author' => [Validator::ABBR_PERSON, 'an abbreviated person resource representing the person who created this person’s record'],
      '/auto_import_id' => [Validator::INT, 'the ID given to a signup when a person is auto imported'],
      '/availability' => [Validator::ISO_TIMESTAMP, 'date and time this person is available (such as for volunteer shifts)'],
      '/ballots' => [Validator::STRING, 'undocumented field'],
      '/banned_at' => [Validator::ISO_TIMESTAMP, 'the time and date this person was banned'],
      '/billing_address' => [Validator::ADDRESS, 'an address resource representing this person’s billing address'],
      '/bio' => [Validator::STRING, 'the bio information that this person provided on their public profile via the “short bio” field'],
      '/birthdate' => [Validator::ISO_DATE, 'this person\'s birth date'],
      '/call_status_id' => [Validator::INT, 'the ID of the call status associated with this person'],
      '/call_status_name' => [Validator::INT, 'the name of the call status associated with this person'],
      '/capital_amount_in_cents' => [Validator::INT, 'the balance of this person’s political or social capital, in cents'],
      '/children_count' => [Validator::INT, 'the number of people assigned to this person'],
      '/church' => [Validator::STRING, 'the church that this person goes to'],
      '/city_district' => [Validator::DISTRICT, 'district field'],
      '/city_sub_district' => [Validator::DISTRICT, 'district field'],
      '/civicrm_id' => [Validator::INT, 'this person’s ID from CiviCRM'],
      '/closed_invoices_amount_in_cents' => [Validator::INT, 'the aggregate amount of all this person’s closed invoices in cents'],
      '/closed_invoices_count' => [Validator::INT, 'the number of closed invoices associated with this person'],
      '/contact_status_id' => [Validator::INT, 'ID of a contact status associated with this person. Possible values: 1: answered, 2: badinfo, 9: inaccessible, 3: leftmessage, 4: meaningfulinteraction, 6: notinterested, 7: noanswer, 8: refused, 5: sendinformation, 0: other'],
      '/contact_status_name' => [Validator::STRING, 'name of a contact status associated with this person: Possible values: answered, badinfo, inaccessible, leftmessage, meaningfulinteraction, notinterested, noanswer, refused, sendinformation, other'],
      '/could_vote_status' => [Validator::BOOLEAN, 'boolean indicating if this person could vote in an election or not, derived from their election-related IDs'],
      '/county_district' => [Validator::DISTRICT, 'district field'],
      '/county_file_id' => [Validator::INT, 'this person’s ID from a county voter file'],
      '/created_at' => [Validator::ISO_TIMESTAMP, 'timestamp representing when this person was created in the nation'],
      '/datatrust_id' => [Validator::INT, 'this person’s Datatrust ID'],
      '/demo' => [Validator::STRING, 'Asian, Black, Hispanic, White, Other, Unknown'],
      '/do_not_call' => [Validator::BOOLEAN, 'this is a boolean flag that lets us know if this person is on a do not call list'],
      '/do_not_contact' => [Validator::BOOLEAN, 'this is a boolean flag that lets us know if this person is on a do not contact list'],
      '/donations_amount_in_cents' => [Validator::INT, 'aggregate amount of all this person’s donations in cents'],
      '/donations_amount_this_cycle_in_cents' => [Validator::INT, 'the aggregate value of the donations this person made this cycle in cents'],
      '/donations_count_this_cycle' => [Validator::INT, 'the number of donations this person made this cycle'],
      '/donations_count' => [Validator::INT, 'the total number of donations made by this person'],
      '/donations_pledged_amount_in_cents' => [Validator::INT, 'the aggregate amount of the donations pledged by this person in cents'],
      '/donations_raised_amount_in_cents' => [Validator::INT, 'the aggregate amount of the donations raised by this person in cents, including their own donations'],
      '/donations_raised_amount_this_cycle_in_cents' => [Validator::INT, 'the aggregate value of all donations raised this cycle by this person, including their own'],
      '/donations_raised_count_this_cycle' => [Validator::INT, 'the number of donations raised this cycle by this person, including their own'],
      '/donations_raised_count' => [Validator::INT, 'the total number of donations raised'],
      '/donations_to_raise_amount_in_cents' => [Validator::INT, 'the goal amount of donations for this person to raise in cents'],
      '/dw_id' => [Validator::STRING, 'this person’s ID from Catalist'],
      '/email1_is_bad' => [Validator::BOOLEAN, 'boolean indicating if email1 for this person is bad'],
      '/email1' => [Validator::EMAIL, 'an email address for this person'],
      '/email2_is_bad' => [Validator::BOOLEAN, 'boolean indicating if email2 for this person is bad'],
      '/email2' => [Validator::EMAIL, 'an email address for this person'],
      '/email3_is_bad' => [Validator::BOOLEAN, 'boolean indicating if email3 for this person is bad'],
      '/email3' => [Validator::EMAIL, 'an email address for this person'],
      '/email4_is_bad' => [Validator::BOOLEAN, 'boolean indicating if email4 for this person is bad'],
      '/email4' => [Validator::EMAIL, 'an email address for this person'],
      '/email_opt_in' => [Validator::BOOLEAN, 'boolean representing whether this person has opted-in to email correspondence'],
      '/email' => [Validator::EMAIL, 'when reading this field clients can expect the person\'s best email address to be returned. A person can have up to 4 email addresses: email1, email2, email3 and email4. The best email address is the one that is not marked as bad and is also marked as primary, that is, it is referenced by the primary_email_id field. When writing this field, its value will be assigned to one of email1, email2, email3 and email4 and it will be marked as primary. If all 4 email fields are already populated then the first one marked as bad will be overwritten. If none of the 4 email fields are marked as bad then the value of email will be dropped. In this case one of the 4 email fields and the primary_email_id have to be directly updated.'],
      '/employer' => [Validator::STRING, 'the name of the company for which this person works'],
      '/ethnicity' => [Validator::STRING, 'the ethnicity of this person as free text'],
      '/external_id' => [Validator::STRING, 'a string representing an external identifier for this person'],
      '/facebook_address' => [Validator::STRING, 'this person’s address based on their Facebook profile'],
      '/facebook_profile_url' => [Validator::URL, 'the URL of this person’s Facebook profile'],
      '/facebook_updated_at' => [Validator::ISO_TIMESTAMP, 'the date and time this person\'s Facebook info was last updated'],
      '/facebook_username' => [Validator::STRING, 'this person\'s Facebook username'],
      '/fax_number' => [Validator::PHONE, 'the fax number associated with this person'],
      '/federal_district' => [Validator::DISTRICT, 'district field'],
      '/federal_donotcall' => [Validator::BOOLEAN, 'boolean value indicating if this user is on the U.S. Federal Do Not Call list'],
      '/fire_district' => [Validator::DISTRICT, 'district field'],
      '/first_donated_at' => [Validator::ISO_TIMESTAMP, 'date and time of this person\'s first donation'],
      '/first_fundraised_at' => [Validator::ISO_TIMESTAMP, 'date and time that this person first raised donation'],
      '/first_invoice_at' => [Validator::ISO_TIMESTAMP, 'date and time of this person\'s first invoice'],
      '/first_name' => [Validator::STRING, 'the person\'s first name and middle names'],
      '/first_prospect_at' => [Validator::ISO_TIMESTAMP, 'date and time that this user first became a prospect'],
      '/first_recruited_at' => [Validator::ISO_TIMESTAMP, 'date and time that this user was first recruited'],
      '/first_supporter_at' => [Validator::ISO_TIMESTAMP, 'date and time that this user became a supporter for the first time'],
      '/first_volunteer_at' => [Validator::ISO_TIMESTAMP, 'date and time that this person first volunteered'],
      '/full_name' => [Validator::STRING, 'this person’s full name'],
      '/has_facebook' => [Validator::BOOLEAN, 'a boolean representing whether this person has Facebook information'],
      '/home_address' => [Validator::ADDRESS, 'an address resource representing the home address'],
      '/id' => [Validator::INT, 'the NationBuilder ID of the person, specific to the authorized nation'],
      '/import_id' => [Validator::STRING, 'the ID associated with this person when they were imported'],
      '/inferred_party' => [Validator::STRING, 'the party the person is believed to be associated with'],
      '/inferred_support_level' => [Validator::INT, 'a possible support level'],
      '/invoice_payments_amount_in_cents' => [Validator::INT, 'total invoice payment amount (cents)'],
      '/invoice_payments_referred_amount_in_cents' => [Validator::INT, 'the aggregate amount of invoice payments made by recruits of this person (cents)'],
      '/invoices_amount_in_cents' => [Validator::INT, 'the aggregate amount of all of this person’s invoices (cents)'],
      '/invoices_count' => [Validator::INT, 'the number of invoices this person has'],
      '/is_absentee_voter' => [Validator::BOOLEAN, 'undocumented field'],
      '/is_active_voter' => [Validator::BOOLEAN, 'undocumented field'],
      '/is_deceased' => [Validator::BOOLEAN, 'a boolean field that indicates if the person is alive or not'],
      '/is_donor' => [Validator::BOOLEAN, 'a boolean field that indicates if the person has donated'],
      '/is_dropped_from_file' => [Validator::BOOLEAN, 'undocumented field'],
      '/is_early_voter' => [Validator::BOOLEAN, 'undocumented field'],
      '/is_fundraiser' => [Validator::BOOLEAN, 'a boolean value that indicates if this person has previously fundraised'],
      '/is_ignore_donation_limits' => [Validator::BOOLEAN, 'a boolean that indicates whether this person is not subject to donation limits associated with the nation'],
      '/is_leaderboardable' => [Validator::BOOLEAN, 'a boolean that tells if this person is allowed to show up on the leaderboard'],
      '/is_mobile_bad' => [Validator::BOOLEAN, 'a boolean reflecting whether this person’s cell number is invalid'],
      '/is_permanent_absentee_voter' => [Validator::BOOLEAN, 'undocumented field'],
      '/is_possible_duplicate' => [Validator::BOOLEAN, 'a boolean field that indicates if the NationBuilder matching algorithm thinks this person is a match to someone else in the nation'],
      '/is_profile_private' => [Validator::BOOLEAN, 'a boolean that tells if this person’s profile is private'],
      '/is_profile_searchable' => [Validator::BOOLEAN, 'a boolean that tells if this person’s profile is allowed to show up in search results'],
      '/is_prospect' => [Validator::BOOLEAN, 'a boolean field that indicates if this person is a prospect'],
      '/is_supporter' => [Validator::BOOLEAN, 'a boolean field that indicates if this person is a supporter'],
      '/is_survey_question_private' => [Validator::BOOLEAN, 'a boolean field that indicates if this person’s survey responses are private'],
      '/is_twitter_follower' => [Validator::BOOLEAN, 'whether the person is a Twitter follower of one of the nation’s broadcasters'],
      '/is_volunteer' => [Validator::BOOLEAN, 'a boolean field that indicates whether the person has volunteered'],
      '/judicial_district' => [Validator::DISTRICT, 'a district field'],
      '/labour_region' => [Validator::DISTRICT, 'a district field'],
      '/language' => [Validator::STRING, 'this person’s primary language'],
      '/last_call_id' => [Validator::INT, 'the id of the last contact to this person'],
      '/last_contacted_at' => [Validator::ISO_TIMESTAMP, 'the time and date of the last time this person was contacted'],
      '/last_contacted_by' => [Validator::ABBR_PERSON, 'an abbreviated person resource representing the last user who contacted this person'],
      '/last_donated_at' => [Validator::ISO_TIMESTAMP, 'the time and date of this person’s last donation'],
      '/last_fundraised_at' => [Validator::ISO_TIMESTAMP, 'the time and date of the last time this person fundraised'],
      '/last_invoice_at' => [Validator::ISO_TIMESTAMP, 'the time and date of this person’s last invoice'],
      '/last_name' => [Validator::STRING, 'this person\'s last name'],
      '/last_rule_violation_at' => [Validator::ISO_TIMESTAMP, 'the time and date of this person’s last rule violation'],
      '/legal_name' => [Validator::STRING, 'the full (legal) name of this person'],
      '/linkedin_id' => [Validator::STRING, 'this person’s ID from LinkedIn'],
      '/locale' => [Validator::STRING, 'the ISO locale that the user has their administrative account set to (US, ES, FR etc.)'],
      '/mailing_address' => [Validator::ADDRESS, 'an address resource representing the mailing address'],
      '/marital_status' => [Validator::STRING, 'the person’s marital status'],
      '/media_market_name' => [Validator::STRING, 'the name of this person’s media market'],
      '/meetup_address' => [Validator::ADDRESS, 'an address resource based on this person’s profile in Meetup'],
      '/meetup_id' => [Validator::STRING, 'this person’s ID from Meetup'],
      '/membership_expires_at' => [Validator::ISO_TIMESTAMP, 'the time and date that this user’s membership expires'],
      '/membership_level_name' => [Validator::STRING, 'the name of the level of this person’s membership'],
      '/membership_started_at' => [Validator::ISO_TIMESTAMP, 'the time and date that this user started a membership'],
      '/middle_name' => [Validator::STRING, 'this person’s middle name'],
      '/mobile_normalized' => [Validator::PHONE, 'this person\'s cell phone number in normalized form'],
      '/mobile_opt_in' => [Validator::BOOLEAN, 'a boolean representing whether the person has opted-in to mobile correspondence'],
      '/mobile' => [Validator::PHONE, 'this person\'s cell phone number'],
      '/nbec_guid' => [Validator::STRING, 'this person\'s ID from the NationBuilder Election Center'],
      '/nbec_precinct_code' => [Validator::STRING, 'the unique identifier assigned to this person in the NationBuilder Election Center'],
      '/ngp_id' => [Validator::STRING, 'this person’s ID from NGP'],
      '/note_updated_at' => [Validator::ISO_TIMESTAMP, 'the date and time the note attached to this person was updated'],
      '/note' => [Validator::STRING, 'a note to attach to the person\'s profile'],
      '/occupation' => [Validator::STRING, 'the type of work this person does'],
      '/outstanding_invoices_amount_in_cents' => [Validator::INT, 'the aggregate value of all this person’s outstanding invoices in cents'],
      '/outstanding_invoices_count' => [Validator::INT, 'the number of outstanding invoices this person has'],
      '/overdue_invoices_count' => [Validator::INT, 'the number of overdue invoices this person has'],
      '/page_slug' => [Validator::STRING, 'the page this person first signed up from'],
      '/parent_id' => [Validator::INT, 'the NationBuilder ID of this person’s point person'],
      '/parent' => [Validator::ABBR_PERSON, 'an abbreviated person resource representing this person’s point person'],
      '/party_member' => [Validator::BOOLEAN, 'a boolean that tells if this person is a member of a political party'],
      '/party' => [Validator::STRING, 'a one-letter code representing provincial parties for nations'],
      '/pf_strat_id' => [Validator::STRING, 'a person’s historical ID from PoliticalForce'],
      '/phone_normalized' => [Validator::PHONE, 'this person\'s home phone number in normalized form'],
      '/phone_time' => [Validator::STRING, 'the time that has been selected as the best time to call this person'],
      '/phone' => [Validator::PHONE, 'this person\'s home phone number'],
      '/precinct_code' => [Validator::STRING, 'the code of the precinct that this person lives in'],
      '/precinct_id' => [Validator::STRING, 'the ID of the precinct associated with this person'],
      '/precinct_name' => [Validator::STRING, 'the name of the precinct that this person votes in'],
      '/prefix' => [Validator::STRING, 'the name prefix of this person, i.e. Mr., Mrs.'],
      '/previous_party' => [Validator::STRING, 'the party this person had selected before their current party selection'],
      '/primary_address' => [Validator::ADDRESS, 'an address resource representing the primary address'],
      '/primary_email_id' => [Validator::INT, 'the id of the primary email address associated with this person, one of: 1, 2, 3 or 4. This id corresponds to the 4 email addresses a person can have: email1, email2, email3 and email4.'],
      '/priority_level_changed_at' => [Validator::ISO_TIMESTAMP, 'the date and time that this person’s priority level changed'],
      '/priority_level' => [Validator::INT, 'the priority level associated with this person'],
      '/profile_content_html' => [Validator::STRING, 'the profile content for this person’s public profile in HTML'],
      '/profile_content' => [Validator::STRING, 'the content for this person’s public profile'],
      '/profile_headline' => [Validator::STRING, 'the headline for this person’s public profile'],
      '/profile_image_url_ssl' => [Validator::URL, 'the HTTPS image URL for this person’s public profile'],
      '/received_capital_amount_in_cents' => [Validator::INT, 'the aggregate amount of political capital ever received by this person'],
      '/recruiter_id' => [Validator::INT, 'the ID of the person who recruited this person'],
      '/recruiter' => [Validator::ABBR_PERSON, 'an abbreviated person resource representing the person who recruited this person'],
      '/recruits_count' => [Validator::INT, 'the number of people that were recruited by this person'],
      '/registered_address' => [Validator::ADDRESS, 'an address resource representing the registered address'],
      '/registered_at' => [Validator::ISO_TIMESTAMP, 'the date this person registered to become a voter'],
      '/religion' => [Validator::STRING, 'this person’s religion'],
      '/rnc_id' => [Validator::STRING, 'this person’s ID from the RNC'],
      '/rnc_regid' => [Validator::STRING, 'this person’s registration ID from the RNC'],
      '/rule_violations_count' => [Validator::INT, 'the number of times this person has violated one of the nation’s rules'],
      '/salesforce_id' => [Validator::STRING, 'this person’s ID from Salesforce'],
      '/school_district' => [Validator::DISTRICT, 'a district field'],
      '/school_sub_district' => [Validator::DISTRICT, 'a district field'],
      '/sex' => [Validator::STRING, 'this person\'s gender (M, F or O)'],
      '/signup_type' => [Validator::STRING, 'this person\'s signup type'],
      '/spent_capital_amount_in_cents' => [Validator::INT, 'the aggregate amount of capital ever spent by this person (in cents)'],
      '/state_file_id' => [Validator::STRING, 'this person’s ID from a state voter file'],
      '/state_lower_district' => [Validator::DISTRICT, 'a district field'],
      '/state_upper_district' => [Validator::DISTRICT, 'a district field'],
      '/submitted_address' => [Validator::ADDRESS, 'the address this person submitted'],
      '/subnations' => [Validator::ARRAY_OF_STRINGS, 'an array of subnations that this person belongs to'],
      '/suffix' => [Validator::STRING, 'the suffix this person uses w/their name, i.e. Jr., Sr. or III'],
      '/support_level_changed_at' => [Validator::ISO_TIMESTAMP, 'the time and date that this person’s support level changed'],
      '/support_level' => [Validator::INT, 'the level of support this person has for your nation, expressed as a number between 1 and 5, 1 being Strong support, 5 meaning strong opposition, and 3 meaning undecided.'],
      '/support_probability_score' => [Validator::STRING, 'the likelihood that this person will support you at election time'],
      '/supranational_district' => [Validator::DISTRICT, 'district field'],
      // '/tags' => [Validator::ARRAY_OF_STRINGS, 'the tags assigned to this person, as an array of strings'],
      '/tags' => [Validator::ARRAY_OF_STRINGS, 'the tags assigned to this person, as one line of comma-separated values (CSV)'],
      '/township' => [Validator::DISTRICT, 'undocumented field'],
      '/turnout_probability_score' => [Validator::STRING, 'the probability that this person will turn out to vote'],
      '/twitter_address' => [Validator::ADDRESS, 'this person’s location based on their Twitter profile'],
      '/twitter_description' => [Validator::STRING, 'the description that this person provided in their Twitter profile'],
      '/twitter_followers_count' => [Validator::INT, 'the number of followers this person has on Twitter'],
      '/twitter_friends_count' => [Validator::INT, 'the number of friends this person has on Twitter'],
      '/twitter_id' => [Validator::STRING, 'this person’s ID from Twitter'],
      '/twitter_location' => [Validator::ADDRESS, 'an address resource representing this person’s address based on Twitter’s location data'],
      '/twitter_login' => [Validator::STRING, 'this person’s Twitter login name'],
      '/twitter_name' => [Validator::STRING, 'this person’s Twitter handle, e.g. FoobarSoftwares'],
      '/twitter_updated_at' => [Validator::ISO_TIMESTAMP, 'the last time this person’s Twitter record was updated'],
      '/twitter_website' => [Validator::URL, 'the URL of the website that this person included in their Twitter profile'],
      '/unsubscribed_at' => [Validator::ISO_TIMESTAMP, 'the date/time that this person unsubscribed from emails'],
      '/updated_at' => [Validator::ISO_TIMESTAMP, 'the timestamp representing when this person was last updated'],
      '/user_submitted_address' => [Validator::ADDRESS, 'an address resource representing the address this person submitted'],
      '/username' => [Validator::STRING, 'this person’s NationBuilder username'],
      '/van_id' => [Validator::STRING, 'this person’s ID from VAN'],
      '/village_district' => [Validator::DISTRICT, 'a district field'],
      '/voter_updated_at' => [Validator::ISO_TIMESTAMP, 'undocumented field'],
      '/ward' => [Validator::DISTRICT, 'undocumented field'],
      '/warnings_count' => [Validator::INT, 'the number of warnings this person has received'],
      '/website' => [Validator::URL, 'the URL of this person’s website'],
      '/work_address' => [Validator::ADDRESS, 'an address resource representing this person’s work address'],
      '/work_phone_number' => [Validator::PHONE, 'a work phone number for this person'],
    ];
  }

  static protected function getPrefixedAddressFields($jsonPointerPrefix) {
    $addressFields = [
      '/address1' => [Validator::STRING, 'first address line'],
      '/address2' => [Validator::STRING, 'second address line'],
      '/address3' => [Validator::STRING, 'third address line'],
      '/city' => [Validator::STRING, 'city'],
      '/state' => [Validator::STRING, 'state'],
      '/zip' => [Validator::STRING, 'zip code'],
      '/country_code' => [Validator::STRING, 'country code'],
      '/lat' => [Validator::STRING, 'latitude (using WGS-84)'],
      '/lng' => [Validator::STRING, 'longitude (using WGS-84)'],
    ];
    $prefixedAddressFields = [];
    foreach ($addressFields as $jsonPointer => $fieldDefinition) {
      $prefixedAddressFields[$jsonPointerPrefix . $jsonPointer] = $fieldDefinition;
    }
    return $prefixedAddressFields;
  }

  static protected function getPrefixedAbbrPersonFields($jsonPointerPrefix) {
    $abbrPersonFields = [];
    $simpleFields = static::getSimplePersonFields();
    $abbrPersonFields = array_intersect_key($simpleFields, array_flip([
      '/birthdate', '/city_district', '/civicrm_id', '/county_district',
      '/county_file_id', '/created_at', '/do_not_call', '/do_not_contact',
      '/dw_id', '/email_opt_in', '/email', '/employer', '/external_id',
      '/federal_district', '/fire_district', '/first_name', '/has_facebook',
      '/id', '/is_twitter_follower', '/is_volunteer', '/judicial_district',
      '/labour_region', '/last_name', '/linkedin_id', '/mobile_opt_in',
      '/mobile', '/nbec_guid', '/ngp_id', '/note', '/occupation', '/party',
      '/pf_strat_id', '/phone', '/precinct_id', 

      // '/primary_address', // Sub-nested structures are not supported yet.

      '/recruiter_id', '/rnc_id', '/rnc_regid', '/salesforce_id',
      '/school_district', '/school_sub_district', '/sex', '/state_file_id',
      '/state_lower_district', '/state_upper_district', '/support_level',
      '/supranational_district', '/tags', '/twitter_id', '/twitter_name',
      '/updated_at', '/van_id', '/village_district',
    ]));
    $prefixedAbbrPersonFields = [];
    foreach ($abbrPersonFields as $jsonPointer => $fieldDefinition) {
      $prefixedAbbrPersonFields[$jsonPointerPrefix . $jsonPointer] = $fieldDefinition;
    }
    return $prefixedAbbrPersonFields;
  }

  public function count() {
    $response = $this->apiGet('people/count');
    $this->throwIfError($response);

    if (
        isset($response['body']['people_count'])
        &&
        is_numeric($response['body']['people_count'])
    ) {
        return $response['body']['people_count'];
    }
    else {
        throw new \Exception('NationBuilder API did not return results for /people/count: ' . $response['statusCode'] . ' (' . $response['body']['code'] . ') ' . $response['body']['message']);
    }
  }

  public function index($pageLimit = 10) {
    $response = $this->apiGet('people', ['/limit' => [Validator::INT, 'maximum number of results to return']], ['limit' => $pageLimit]);
    $this->throwIfError($response);

    if (
        isset($response['body']['results'])
        &&
        is_array($response['body']['results'])
    ) {
        return $response['body'];
    }
    else {
        throw new \Exception('NationBuilder API did not return results for /people/: ' . $response['statusCode'] . ' (' . $response['body']['code'] . ') ' . $response['body']['message']);
    }
  }

  public function show($id) {
    $response = $this->apiGet('people/' . (int) $id);
    $this->throwIfError($response);

    if (
        isset($response['body']['person'])
        &&
        is_array($response['body']['person'])
    ) {
        return $response['body'];
    }
    else {
        throw new \Exception('NationBuilder API did not return results for /people/' . $id . ': ' . $response['statusCode'] . ' (' . $response['body']['code'] . ') ' . $response['body']['message']);
    }
  }

  public function match(array $params) {
    if (isset($params['/phone'])) {
      $params['/phone'] = preg_replace('{\D}', '', $params['/phone']); // Remove all non-digits, only then it matches.
    }
    $response = $this->apiGet('people/match', [
      '/email' => [Validator::STRING, ''],
      '/first_name' => [Validator::STRING, ''],
      '/last_name' => [Validator::STRING, ''],
      '/phone' => [Validator::STRING, ''],
      '/mobile' => [Validator::STRING, ''],
    ], $params);

    if (
        (400 == $response['statusCode'])
        &&
        (
            isset($response['body']['code'], $response['body']['message'])
            &&
            ('no_matches' == $response['body']['code'])
            // &&
            // ('No people matched the given criteria.' == $response['body']['message'])
        )
    ) {
        return [];
    }

    $this->throwIfError($response);

    if (
        isset($response['body']['person'])
        &&
        is_array($response['body']['person'])
    ) {
        return $response['body'];
    }
    else {
        throw new \Exception('NationBuilder API did not return results for /people/match: ' . $response['statusCode'] . ' (' . $response['body']['code'] . ') ' . $response['body']['message']);
    }
  }

  public function search(array $params) {
    $response = $this->apiGet('people/search', [
      '/first_name' => [Validator::STRING, ''],
      '/last_name' => [Validator::STRING, ''],
      '/city' => [Validator::STRING, ''],
      '/state' => [Validator::STRING, ''],
      '/sex' => [Validator::STRING, ''],
      '/birthdate' => [Validator::STRING, ''],
      '/updated_since' => [Validator::STRING, ''],
      '/with_mobile' => [Validator::STRING, ''],
      '/custom_values' => [Validator::STRING, ''],
      '/civicrm_id' => [Validator::STRING, ''],
      '/county_file_id' => [Validator::STRING, ''],
      '/datatrust_id' => [Validator::STRING, ''],
      '/dw_id' => [Validator::STRING, ''],
      '/external_id' => [Validator::STRING, ''],
      '/media_market_id' => [Validator::STRING, ''],
      '/membership_level_id' => [Validator::STRING, ''],
      '/ngp_id' => [Validator::STRING, ''],
      '/pf_strat_id' => [Validator::STRING, ''],
      '/rnc_id' => [Validator::STRING, ''],
      '/rnc_regid' => [Validator::STRING, ''],
      '/salesforce_id' => [Validator::STRING, ''],
      '/state_file_id' => [Validator::STRING, ''],
      '/van_id' => [Validator::STRING, ''],
      '/__token' => [Validator::STRING, 'pagination token'],
      '/__nonce' => [Validator::STRING, 'pagination nonce'],
      '/limit' => [Validator::INT, 'maximum number of results to return'],
    ], $params);

    $this->throwIfError($response);

    if (
        isset($response['body']['results'])
        &&
        is_array($response['body']['results'])
    ) {
        return $response['body'];
    }
    else {
        throw new \Exception('NationBuilder API did not return results for /people/search: ' . $response['statusCode'] . ' (' . $response['body']['code'] . ') ' . $response['body']['message']);
    }
  }

  public function nearby(array $params) {
    if (isset($params['latitude'], $params['longitude'])) {
      $params['location'] = $params['latitude'] . ',' . $params['longitude'];
    }
    $response = $this->apiGet('people/nearby', [
      '/location' => [Validator::STRING, 'origin of search in the format "latitude,longitude"'],
      '/distance' => [Validator::INT, 'radius in miles for which to include results'],
      '/__token' => [Validator::STRING, 'pagination token'],
      '/__nonce' => [Validator::STRING, 'pagination nonce'],
      '/limit' => [Validator::INT, 'maximum number of results to return'],
    ], $params);

    $this->throwIfError($response);

    if (
        isset($response['body']['results'])
        &&
        is_array($response['body']['results'])
    ) {
        return $response['body'];
    }
    else {
        throw new \Exception('NationBuilder API did not return results for /people/nearby: ' . $response['statusCode'] . ' (' . $response['body']['code'] . ') ' . $response['body']['message']);
    }
  }

  public function me() {
    $response = $this->apiGet('people/me');

    $this->throwIfError($response);

    if (
        isset($response['body']['person'])
        &&
        is_array($response['body']['person'])
    ) {
        return $response['body'];
    }
    else {
        throw new \Exception('NationBuilder API did not return results for /people/me: ' . $response['statusCode'] . ' (' . $response['body']['code'] . ') ' . $response['body']['message']);
    }
  }

  public function register($id) {
    $response = $this->apiGet('people/' . (int) $id . '/register');

    $this->throwIfError($response);

    if (
        isset($response['body']['status'])
        &&
        is_string($response['body']['status'])
    ) {
        return 'success' == $response['body']['status'];
    }
    else {
        throw new \Exception('NationBuilder API did not return results for /people/' . (int) $id . '/register: ' . $response['statusCode'] . ' (' . $response['body']['code'] . ') ' . $response['body']['message']);
    }
  }

  public function taggings($id) {
    $response = $this->apiGet('people/' . (int) $id . '/taggings');

    $this->throwIfError($response);

    if (
        isset($response['body']['taggings'])
        &&
        is_array($response['body']['taggings'])
    ) {
        return $response['body']['taggings'];
    }
    else {
        throw new \Exception('NationBuilder API did not return results for /people/' . (int) $id . '/taggings: ' . $response['statusCode'] . ' (' . $response['body']['code'] . ') ' . $response['body']['message']);
    }
  }

  public function addTags($id, array $tags) {
    $response = $this->apiPut('people/' . (int) $id . '/taggings', ['/tagging/tag' => [Validator::ARRAY_OF_STRINGS, '']], ['tagging' => ['tag' => $tags]]);

    $this->throwIfError($response);

    if (
        isset($response['body']['taggings'])
        &&
        is_array($response['body']['taggings'])
    ) {
        return $response['body']['taggings'];
    }
    else {
        throw new \Exception('NationBuilder API did not return results for PUT /people/' . (int) $id . '/taggings: ' . $response['statusCode'] . ' (' . $response['body']['code'] . ') ' . $response['body']['message']);
    }
  }

  public function deleteTag($id, $tag) {
    $response = $this->apiDelete('people/' . (int) $id . '/taggings/' . (string) $tag);
    if (204 == $response['statusCode']) {
        return true;
    }

    $this->throwIfError($response);
    return false;
  }

  public function deleteTags($id, array $tags) {
    $response = $this->apiDelete('people/' . (int) $id . '/taggings', ['/tagging/tag' => [Validator::ARRAY_OF_STRINGS, '']], ['tagging' => ['tag' => $tags]]);
    if (204 == $response['statusCode']) {
        return true;
    }

    $this->throwIfError($response);
    return false;
  }

  public function create($person) {
    $person = Validator::normalize($person, array_merge(static::getPersonFields(), $this->getCustomFields()));
    $person = Validator::inlineJsonPointer($person);
    $response = $this->apiPost('people', ['/person' => [Validator::ABBR_PERSON, '']], ['person' => $person]);

    if (409 == $response['statusCode']) {
        // TODO Handle duplication conflict.
    }

    $this->throwIfError($response);
    if (
        (201 == $response['statusCode'])
        &&
        isset($response['body']['person'])
        &&
        is_array($response['body']['person'])
    ) {
        return $response['body'];
    }
    else {
        throw new \Exception('NationBuilder API did not return results for POST /people: ' . $response['statusCode'] . ' (' . $response['body']['code'] . ') ' . $response['body']['message']);
    }
  }

  public function update($id, $person) {
    $person = Validator::normalize($person, array_merge(static::getPersonFields(), $this->getCustomFields()));
    $person = Validator::inlineJsonPointer($person);
    $response = $this->apiPut('people/' . (int) $id, ['/person' => [Validator::ABBR_PERSON, '']], ['person' => $person]);

    $this->throwIfError($response);
    if (
        isset($response['body']['person'])
        &&
        is_array($response['body']['person'])
    ) {
        return $response['body'];
    }
    else {
        throw new \Exception('NationBuilder API did not return results for POST /people' . (int) $id . ': ' . $response['statusCode'] . ' (' . $response['body']['code'] . ') ' . $response['body']['message']);
    }
  }

  public function delete($id) {
    return $this->apiDelete('people/' . (int) $id);
    if (204 == $response['statusCode']) {
        return true;
    }

    $this->throwIfError($response);
    return false;
  }

  public function destroy($id) {
    return $this->delete($id);
  }

  public function getCustomFields() {
    $index = $this->index();
    if (isset($index[0]['id'])) {
      $firstId = $index[0]['id'];
      $firstPerson = $this->show($firstId);
      $existingFieldNames = array_keys($firstPerson['person']);
      array_walk($existingFieldNames, function (& $value) {$value = '/' . $value;});
      $expectedFieldNames = array_keys(static::getSimplePersonFields());
      $customFieldNames = array_diff($existingFieldNames, $expectedFieldNames);
      return array_fill_keys($customFieldNames, [Validator::STRING, 'custom field']);
    }
    return [];
  }
}
