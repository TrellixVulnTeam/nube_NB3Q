<?php

namespace Drupal\consum_mundoconsum;

use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

/**
 * Class ProductsConnection.
 *
 * @package Drupal\consum_mundoconsum\Services
 */
class MundoconsumConnection {

  /**
   * @var \Drupal\Core\Config\Config Mundoconsum settings.
   */
  protected $config  = NULL;

  /**
   * ProductsConnection constructor.
   */
  public function __construct() {
    $this->config = \Drupal::config('mundoconsum.settings');
  }

  /**
   * Gets a list of the users cheque-crece.
   *
   * @return bool|\Symfony\Component\HttpFoundation\Response
   *   API call response.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getChequeCrece($user, $remmitance, $from_date, $to_date) {
    $client  = new GuzzleClient();
    $request = new GuzzleRequest('GET',  $this->config->get('url') . '/rest/drupal/cheque-crece?socio=344');
    $response = json_decode($client->send($request, ['timeout' => 30])->getBody());
    /*$response = [
      'total' => '2',
      'data' => [
        '0' => [
          'id' => '1',
          'remesa' => '456',
          'fecha_desde' => '2020-10-30',
          'fecha_hasta' => '2020-11-30',
          'idioma' => 'ES',
        ],
        '1' => [
          'id' => '2',
          'remesa' => '456',
          'fecha_desde' => '2020-11-05',
          'fecha_hasta' => '2020-11-05',
          'idioma' => 'ES',
        ],
      ],
      'desglose' => [
        '0' => [
          'id' => '3',
          'fecha_compra' => '30/10/20',
          'pvp' => '2.00',
          'tipo_descuento' => null,
          'importe_descuento' => '0.10',
          'importe_descuento_acumulado' => null,
          'tipo_oferta' => null,
          'id_tienda' => [],
          'id_cheque_crece' => [
            'id' => '1',
            'remesa' => '456',
            'fecha_desde' => '2020-10-30',
            'fecha_hasta' => '2020-11-30',
            'idioma' => 'ES',
          ],
          'id_cheque_crece_datos_socio' => [
            'id' => '4',
            'tipo_texto' => 'Pl',
            'importe_texto' => '10.00',
            'descuento_acumulado_desde' => '2020-11-03',
            'importe_descuento' => '1.00',
            'id_socio' => [
              'id' => '1',
              'codigo_socio' => '344',
              'id_tienda' => [
                'id' => '1',
                'codigo_tienda' => '22',
              ],
            ],
          ],
          'id_articulo' => [
            'id' => '1',
            'codigo_area' => '200',
            'codigo_categoría' => '45',
            'codigo_segmento' => '67',
            'cosigo_subsegmento' => '68',
            'codigo_articulo' => '4759',
          ],
        ],
        '1' => [
          'id' => '4',
          'fecha_compra' => '05/11/20',
          'pvp' => '10.00',
          'tipo_descuento' => null,
          'importe_descuento' => '0.10',
          'importe_descuento_acumulado' => null,
          'tipo_oferta' => null,
          'id_tienda' => [],
          'id_cheque_crece' => [
            'id' => '1',
            'remesa' => '456',
            'fecha_desde' => '2020-10-30',
            'fecha_hasta' => '2020-11-30',
            'idioma' => 'ES',
          ],
          'id_cheque_crece_datos_socio' => [
            'id' => '4',
            'tipo_texto' => 'Pl',
            'importe_texto' => '10.00',
            'descuento_acumulado_desde' => '2020-11-03',
            'importe_descuento' => '1.00',
            'id_socio' => [
              'id' => '1',
              'codigo_socio' => '344',
              'id_tienda' => [
                'id' => '1',
                'codigo_tienda' => '22',
              ],
            ],
          ],
          'id_articulo' => [
            'id' => '1',
            'codigo_area' => '200',
            'codigo_categoría' => '45',
            'codigo_segmento' => '67',
            'cosigo_subsegmento' => '68',
            'codigo_articulo' => '4743',
          ],
        ],
      ],
    ];*/

    return $response;
  }

}
