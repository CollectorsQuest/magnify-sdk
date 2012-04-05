<?php
/**
 * Copyright 2012 Collectors' Quest, Inc.
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
 * Filename: Feed.php
 *
 * Base Feed class
 *
 * @package Magnify
 * @subpackage Feed
 * @author Yanko Simeonoff <ysimeonoff@collectorsquest.com>
 * @since 3/29/12
 * Id: $Id$
 */

class MagnifyFeed implements Iterator
{

  public $title, $content, $updated;
  public $totalResults, $itemsPerPage, $startIndex;

  private $_self, $_next, $_previous, $alternate, $user;
  private $_entries = array();
  private $_valid = false;

  /**
   * (PHP 5 &gt;= 5.1.0)<br/>
   * Return the current element
   * @link http://php.net/manual/en/iterator.current.php
   * @return MagnifyEntry|false Can return any type.
   */
  public function current()
  {
    return current($this->_entries);
  }

  /**
   * (PHP 5 &gt;= 5.1.0)<br/>
   * Move forward to next element
   * @link http://php.net/manual/en/iterator.next.php
   * @return void Any returned value is ignored.
   */
  public function next()
  {
    $this->_valid = (false !== next($this->_entries));
  }

  /**
   * (PHP 5 &gt;= 5.1.0)<br/>
   * Return the key of the current element
   * @link http://php.net/manual/en/iterator.key.php
   * @return scalar scalar on success, integer
   * 0 on failure.
   */
  public function key()
  {
    return key($this->_entries);
  }

  /**
   * (PHP 5 &gt;= 5.1.0)<br/>
   * Checks if current position is valid
   * @link http://php.net/manual/en/iterator.valid.php
   * @return boolean The return value will be casted to boolean and then evaluated.
   * Returns true on success or false on failure.
   */
  public function valid()
  {
    return $this->_valid;
  }

  /**
   * (PHP 5 &gt;= 5.1.0)<br/>
   * Rewind the Iterator to the first element
   * @link http://php.net/manual/en/iterator.rewind.php
   * @return void Any returned value is ignored.
   */
  public function rewind()
  {
    $this->_valid = (false !== reset($this->_entries));
  }

  /**
   * @return MagnifyEntry|false
   */
  public function first()
  {
    $this->rewind();

    return $this->current();
  }

  public function __construct(Magnify $dispatcher, SimpleXMLElement $feed, MagnifyResource $parser)
  {
    // normal Atom elements
    $this->setTitle($feed->title);
    $this->setContent($feed->content);
    $this->setUpdatedAt($feed->updated);

    $opensearch = $feed->children($parser::$ns['opensearch']);

    $this->setTotalResults($opensearch->totalResults);
    $this->setItemsPerPage($opensearch->itemsPerPage);
    $this->setStartIndex($opensearch->startIndex);

    // link elements
    list($this->self, $this->next, $this->previous) = $parser::extractLinks($feed, 'self', 'next', 'previous');

    $entryClass = ucfirst($parser->getResourceGroup()) . 'Entry';
    require_once dirname(__FILE__) . '/Entry/' . ucfirst($parser->getResourceGroup()) . '.php';

    foreach ($feed->entry as $entry)
    {
      $this->_entries[] = new $entryClass($entry, $parser);
    }
  }

  public function setAlternate($alternate)
  {
    $this->alternate = (string)$alternate;
  }

  public function getAlternate()
  {
    return $this->alternate;
  }

  public function setContent($content)
  {
    $this->content = (string)$content;
  }

  public function getContent()
  {
    return $this->content;
  }

  public function setItemsPerPage($itemsPerPage)
  {
    $this->itemsPerPage = (int)$itemsPerPage;
  }

  public function getItemsPerPage()
  {
    return $this->itemsPerPage;
  }

  public function setStartIndex($startIndex)
  {
    $this->startIndex = (int)$startIndex;
  }

  public function getStartIndex()
  {
    return $this->startIndex;
  }

  public function setTitle($title)
  {
    $this->title = (string)$title;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function setTotalResults($totalResults)
  {
    $this->totalResults = (int)$totalResults;
  }

  public function getTotalResults()
  {
    return $this->totalResults;
  }

  public function setUpdatedAt($updated)
  {
    $this->updated = is_int($updated) ? $updated : strtotime($updated);
  }

  public function getUpdatedAt($format = 'Y-m-d H:i')
  {
    return $this->updated;
  }

  public function setUser($user)
  {
    $this->user = (string)$user;
  }

  public function getUser()
  {
    return $this->user;
  }

}
