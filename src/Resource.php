<?php
/**
 * Copyright 2011 Collectors' Quest, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

/**
 * File: Resource.php
 *
 * @author zecho
 * @version $Id$
 *
 */

require_once dirname(__FILE__) . '/Exception/Magnify.php';

abstract class MagnifyResource
{

  /**
   * @var \Entry|null
   */
  protected $parser = null;
  /**
   * @var \Magnify|null
   */
  protected $dispatcher = null;
  protected $baseUrl = 'api';
  public static $ns = null;

  abstract public function getResourceGroup();

  public function __construct(Magnify $dispatcher, Entry $parser = null)
  {
    $this->dispatcher = $dispatcher;
    $this->parser = $parser;
  }

  /**
   * @param string $action
   * @param array $params
   * @return \SimpleXMLElement/home/zecho/shared/sf_plugins/iceMagnifyPlugin/lib/vendor/Magnify/Resource.php
   * @throws MagnifyException
   */
  protected function fetch($action, $params = array())
  {
    $url = sprintf('http://%s/%s/%s/%s?%s',
      $this->dispatcher->getChannel(),
      $this->baseUrl,
      $this->getResourceGroup(),
      $action,
      http_build_query($params)
    );

    var_dump($url);

    $headers = array(
      'X-Magnify-Key: ' . $this->dispatcher->getApiKey(),
    );
    $session = curl_init($url);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($session, CURLOPT_HTTPHEADER, $headers);

    $reply = curl_exec($session);

    if (false === $reply)
    {
      throw new MagnifyException(curl_error($session), curl_errno($session));
    }

    curl_close($session);

    try
    {
      $xml = new SimpleXMLElement($reply);
      $this->setNamespaces($xml->getDocNamespaces());
    }
    catch (Exception $e)
    {
      throw new MagnifyException('Could not parse response XML', $e->getCode(), $e);
    }

    return $xml;
  }

  public function getNamespaces()
  {
    return self::$ns;
  }

  public function setNamespaces($ns)
  {
    self::$ns = $ns;
  }

  /**
   * @param $xml
   * @return \MagnifyFeed
   */
  public function parse($xml)
  {
    $feedClass = ucfirst($this->getResourceGroup()) . 'Feed';

    return new $feedClass($this->dispatcher, $xml, $this);
  }

  public function show($id)
  {
    $xml = $this->fetch('show', array('id'=> $id));

    return $this->parse($xml)->first();
  }

  public static function extractLinks()
  {
    $args = func_get_args();

    /* @var $xml SimpleXMLElement */
    $xml = array_shift($args);
    if (!($xml instanceof SimpleXMLElement))
    {
      throw new MagnifyException('first argument should be instance of SimpleXMLElement');
    }

    $xml->registerXPathNameSpace('atom', 'http://www.w3.org/2005/Atom');

    $links = array();
    foreach ($args as $rel)
    {
      $result = $xml->xpath("atom:link[@rel='$rel']/@href");
      $links[] = $result ? (string)$result[0] : NULL;
    }

    return $links;
  }

}

