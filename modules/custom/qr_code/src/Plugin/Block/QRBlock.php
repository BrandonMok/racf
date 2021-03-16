<?php

namespace Drupal\qr_code\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

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
    ];

    $form['url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL'),
      '#description' => $this->t('Enter a URL to allow users to purchase tickets from your organization.'),
      '#required' => TRUE,
    ];

    return $form;
  }


  /**
   * {@inheritDoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['coupon_code'] = $form_state->getValue('coupon_code');
    $this->configuration['url'] = $form_state->getValue('url');
  }


  /**
   * {@inheritdoc}
   */
  public function build() {

    $currNode = \Drupal::routeMatch()->getParameter('node');

    if (is_null($currNode)) {
      $nodeType = "";
    }
    else {
      $nodeType = $currNode->bundle();
    }

    $currentUID = \Drupal::currentUser()->id();

    $title = "";
    $loggedin = "false";

    // Make sure it's an event content type and user is logged in
    if ($nodeType === "event" && $currentUID != 0) {
      // Get title of the event
      $title = $currNode->getTitle();
      $gen = "";
      $loggedin = "true";


      // before generating pass, check if today's date isn't past the event's end date
      $eventDate = $currNode->get('field_date')->getString();
      $endDate = new \DateTime(substr($eventDate, 12, 21));
      $today = new \DateTime('now');

      $today = $today->format("Y-m-d");
      $endDate = $endDate->format("Y-m-d");

      // Check dates
      if ($today <= $endDate) {
        // $urlEncoded = urlencode("https://dev-racf.pantheonsite.io/checkin&event=$title&uid=$currentUID");
        $urlEncoded = urlencode("https://dev-racf.pantheonsite.io/checkin/$title/$currentUID");
        $markup = "http://api.qrserver.com/v1/create-qr-code/?size=150x150&data=$urlEncoded";
      }
      else {
        $markup = "/modules/custom/qr_code/images/x-mark.png";
        $gen = "Uh oh! This event has already concluded!";
      }
    }
    else { 
      // not loggedin
      $markup = "/modules/custom/qr_code/images/x-mark.png";
      $gen = "QR Code unavailable. Please login to generate pass.";
    }

    
    return [
      '#theme' => 'qr_themeable_block',
      '#content' => $markup,
      '#loggedin' => $loggedin,
      '#gen_error' => $gen,
      '#event' => $title ?? '',
      '#title' => '',
      '#coupon_code' => $this->configuration['coupon_code'] ?? '',
      '#url' => $this->configuration['url'] ?? '',
      '#attached' => [
        'library' => [
          'qr_code/assets',
        ],
      ],
    ];
    
  }
}
