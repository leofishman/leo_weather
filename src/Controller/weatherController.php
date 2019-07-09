<?php

namespace Drupal\leo_weather\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
//use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
//use \Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Component\Utility\Html;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\ClientInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use GuzzleHttp\Client;

/**
 * Class weatherController.
 */
class weatherController extends ControllerBase {

    /**
     * Base uri of openweather api.
     *
     * @var Drupal\openweather
     */
    public static $baseUri = 'http://api.openweathermap.org/';

    /**
     * The HTTP client to fetch the feed data with.
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;

    /**
     * Constructs a database object.
     *
     * @param \GuzzleHttp\ClientInterface $http_client
     *   The Guzzle HTTP client.
     */
    public function __construct() {
        $client = \Drupal::httpClient();
        $this->httpClient = $client;
    }

    /**
     * Get a complete query for the API.
     */
    public function createRequest($options) {
        $query = [];
        $appid_config = \Drupal::config('leo_weather.settings')->get('appid');
        $query['appid'] = Html::escape($appid_config);
        $city_name = Html::escape($options['city_name']);
        $query['q'] = $city_name;
        $query['units']='metric'; // Celcious Degrees

        return $query;

    }

    /**
     * Return the data from the API in xml format.
     */
    public function getWeatherInformation($options) {
        try {
            $response = $this->httpClient->request('GET', self::$baseUri . '/data/2.5/weather',
                [
                    'query' => $this->createRequest($options),
                ]);
        }
        catch (GuzzleException $e) {
            watchdog_exception('leo_weather', $e);
            return [];
        }

        return $response->getBody()->getContents();
    }



    /**
    * weather.
    *
    * @return json
    *   Return weather json.
    */
    public function weather()
    {
      $options['city_name'] = 'Buenos Aires';
      $output = json_decode($this->getWeatherInformation($options));

        $html['temp'] = round($output->main->temp) . '°C';
        $html['name'] = $output->name;
        $html['humidity'] = $output->main->humidity . '%';
        $html['pressure'] = $output->main->pressure;
        $html['temp_max'] = round($output->main->temp_max) . '°C';
        $html['temp_min'] = round($output->main->temp_min) . '°C';
        $html['weather']['desc'] = $output->weather[0]->description;
        $html['weather']['image'] = $output->weather[0]->icon;
        $html['country'] = $output->sys->country;

//print '<pre>';
//var_dump($output->name, $html);die;
        $build[] = [
            '#theme' => 'leo_weather',
            '#leo_weather_detail' => $html,
            '#attached' => array(
                'library' => array(
                    'leo_weather/leo_weather_theme',
                ),
            ),
            '#cache' => array('max-age' => 0),
        ];

        return $build;
    }

}
