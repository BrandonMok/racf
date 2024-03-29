<?php

namespace Drupal\qr_code\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Cache\Cache;

/**
 * Provides a 'QR' Block.
 *
 * @Block(
 *   id = "qr_code",
 *   admin_label = @Translation("QR Code block"),
 *   category = @Translation("QR Code"),
 * )
 */
class QRBlock extends BlockBase {

  /**
   * {@inheritDoc}
   */
  public function defaultConfiguration() {
    return [
      'coupon_code' => '',
      'url' => '',
      'how_to_use' => '',
      'terms_conditions' => '',
    ] + parent::defaultConfiguration();
  }


  /**
   * {@inheritDoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['coupon_code'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Coupon code'),
      '#description' => $this->t('Enter a coupon code for the event.'),
      '#default_value' => $this->configuration['coupon_code'],
    ];

    $form['url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL'),
      '#description' => $this->t('Enter a URL to allow users to purchase tickets from your organization.'),
      '#default_value' => $this->configuration['url'],
    ];

    $form['how_to_use'] = [
      '#type' => 'textarea',
      '#title' => $this->t('How to use this Pass.'),
      '#default_value' => $this->configuration['how_to_use'],
    ];

    $form['terms_conditions'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Terms & Conditions'),
      '#default_value' => $this->configuration['terms_conditions'],
    ];

    return $form;
  }


  /**
   * {@inheritDoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['coupon_code'] = $form_state->getValue('coupon_code');
    $this->configuration['url'] = $form_state->getValue('url');
    $this->configuration['how_to_use'] = $form_state->getValue('how_to_use');
    $this->configuration['terms_conditions'] = $form_state->getValue('terms_conditions');
  }


  /**
   * {@inheritdoc}
   */
  public function build() {
    global $base_url;

    $currNode = \Drupal::routeMatch()->getParameter('node');

    if (is_null($currNode)) {
      $nodeType = "";
    }
    else {
      $nodeType = $currNode->bundle();
      $nid = $currNode->id();
    }

    $currentUID = \Drupal::currentUser()->id();
    $title = "";

    // Make sure it's an event content type and user is logged in.
    if ($nodeType === "event" && $currentUID != 0) {
      // Get title of the event
      $title = $currNode->getTitle();
      $gen = "";

      // Before generating pass, check if today's date isn't past the event's end date.
      $eventDate = $currNode->get('field_date')->getString();
      $startDate = new \DateTime(substr($eventDate, 0, 12));
      $endDate = new \DateTime(substr($eventDate, 12, 21));
      $today = new \DateTime('now');

      $today = $today->format("Y-m-d");
      $endDate = $endDate->format("Y-m-d");

      // Check dates.
      // Today should fall within the event's date range to obtain an Access Pass.
      if ($today <= $endDate) {
        $urlEncoded = urlencode("$base_url/checkin/$nid/$currentUID");
        $markup = "http://api.qrserver.com/v1/create-qr-code/?size=150x150&data=$urlEncoded";

        // Reformat date for display on Printed Version of Access Pass.
        $displayDate = date_format($startDate,"l, F d Y");

        // Display a time for the Printed Version of Access Pass.
        $fullTime = $currNode->get('field_time')->getString();
        $start = substr($fullTime, 0, 5);
        $end = substr($fullTime, 7, strlen($fullTime));

        $startTimeDate = gmdate('h:i A', $start);
        $endTimeDate = gmdate('h:i A', $end);
        $eventTime = "$startTimeDate - $endTimeDate";


        // CHECK: if user has already redeemed the pass. The display depends on this.
        $redeemed = $this->checkList($currNode, $currentUID);
      }
      else {
        $markup = "/modules/custom/qr_code/images/x-mark.png";
        $gen = "Uh oh! This event has already concluded!";
      }
    }
    elseif ($nodeType === "general_event" && $currentUID != 0) {
        $title = $currNode->getTitle();
        $gen = "";

        $urlEncoded = urlencode("$base_url/checkin/$nid/$currentUID");
        $markup = "http://api.qrserver.com/v1/create-qr-code/?size=150x150&data=$urlEncoded";

        // CHECK: if user has already redeemed the pass. The display depends on this.
        $redeemed = $this->checkList($currNode, $currentUID);
    }
    else { 
      // Not loggedin.
      $markup = "/modules/custom/qr_code/images/x-mark.png";
      $gen = "QR Code unavailable. Please login to generate pass.";
    }

    return [
      '#theme' => 'qr_themeable_block',
      '#title' => '',
      '#content' => $markup,
      '#gen_error' => $gen,
      '#event' => $title ?? '',
      '#coupon_code' => $this->configuration['coupon_code'] ?? '',
      '#url' => $this->configuration['url'] ?? '',
      '#how_to_use' => $this->configuration['how_to_use'] ?? '',
      '#terms_conditions' => $this->configuration['terms_conditions'] ?? '',
      '#event_date' => $displayDate ?? '',
      '#event_time' => $eventTime ?? '',
      '#already_redeemed' => $redeemed ?? '',
      '#attached' => [
        'library' => [
          'qr_code/assets',
          'qr_code/htmltocanvas',
        ],
        'drupalSettings' => [
          "eventID" => $nid,
        ],
      ],
    ];
  }

  /**
   * CheckList
   * @param Node, UID
   * Checks this event's attendee list to see if the user has already generated a pass.
   */
  public function checkList($currentNode, $uid) {
    $attendeeList = $currentNode->get('field_attendees')->getString();
    $retVal = false;
    if ( str_contains($attendeeList, $uid . "\r\n") !== false ) {
      $retVal = true; // set to true, so JS knows whether to automatically show or hide the pass' contents.
    }
    return $retVal;
  }


  /**
   * {@inheritdoc}
   */
  public function cacheContexts() {
    return Cache::mergeContexts(parent::getCacheContexts(), array('user'));
  }
}
