<?php

namespace Drupal\qr_code\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use GuzzleHttp\Exception\RequestException;

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
   * {@inheritdoc}
   */
  public function build() {

    $currNode = \Drupal::routeMatch()->getParameter('node');
    $nodeType = $currNode->bundle();

    $currentUID = \Drupal::currentUser()->id();


    if ($nodeType === "event" && $currentUID != 0) {
      $title = $currNode->getTitle();

      $urlEncoded = urlencode("https://dev-racf.pantheonsite.io/checkin&event=$title&uid=$currentUID");
      $markup = "<img src=http://api.qrserver.com/v1/create-qr-code/?size=150x150&data=$urlEncoded />";
    }
    else { 
       // have block show something like it's not an event or not available
       $markup = $this->t("QR code unavailable. Please login to access this pass!");
    }


    // For use elsewhere!
    // $entityTypeManager = \Drupal::entityTypeManager();
    // $userStorage = $entityTypeManager->getStorage('user');

    // $user = $userStorage->loadByProperties([
    //   'uid' => $currentUID
    // ]);

    // $currUsr = array_pop($user);
    // $snap = $currUsr->get('field_snap_number')->getString();  // snap number



    // $httpClient = \Drupal::httpClient();
  
            // $request = $httpClient->post("https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode("example"), array('headers' => array('Accept' => 'image/png','Content-Type' => 'application/json')));
      // $request = $httpClient->request("POST", $api, array('headers' => array('Accept' => 'image/png','Content-Type' => 'image/png')));

      // $request = $httpClient->post('https://api.qrserver.com/v1/create-qr-code/', [
      //   'body' => json_encode("?size=150x150&data=https://dev-racf.pantheonsite.io/checkin&event=$title&snap=$snap&format=png"),
      //   'headers'=> [
      //     'Accept' => 'image/png',
      //     'Content-Type' => 'image/png'
      //   ]
      // ]);


    //   $request = $httpClient->post('https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode("example"), [
    //     'headers' => [
    //       'Accept' => 'image/png',
    //       'Content-Type' => 'application/json'
    //     ]
    //   ]);


    //   $data = $request->getBody()->getContents();
  

    // $urlEncoded = urlencode("https://dev-racf.pantheonsite.io/checkin&event=$title&uid=$currentUID");

    // return [
    //   '#type' => 'markup',
    //   '#markup' => "<img src=http://api.qrserver.com/v1/create-qr-code/?size=150x150&data=$urlEncoded />",
    // ];



    return [
      '#type' => 'markup',
      '#markup' => $markup,
    ];

    
  }
}
