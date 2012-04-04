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
 * Filename: Content.php
 *
 * Put some description here
 *
 * @author Yanko Simeonoff <ysimeonoff@collectorsquest.com>
 * @since 3/29/12
 * Id: $Id$
 */
require_once dirname(__FILE__) . '/../Entry.php';

class ContentEntry extends MagnifyEntry
{

  /**
   * @param \SimpleXMLElement $entry
   * @param MagnifyResource $parser
   */
  public function __construct($entry, $parser)
  {
    $this->title      = (string)$entry->title;
    $this->content    = (string)$entry->content;
    $this->updated_at = strtotime($entry->updated);

    $magnify = $entry->children($parser::$ns['magnify']);
    $media   = $entry->children($parser::$ns['media']);

    $this->id        = (string)$magnify->id;
    $this->thumbnail = (string)$media->thumbnail->attributes()->url;
    $this->iframeUrl = (string)$media->content->attributes()->url;

    //links
    list($this->self, $this->user, $this->alternate) = $parser::extractLinks($entry, 'self', 'user', 'alternate');

    //author
    $this->author    = (string)$entry->author->name;
    $this->authorId  = (string)$entry->author->children($parser::$ns['magnify'])->id;
    $this->authorUrl = (string)$entry->author->uri;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function getThumbnail()
  {
    return $this->thumbnail;
  }

  public function getUpdatedAt($format = 'Y-m-d H:i')
  {
    return date($format, $this->updated_at);
  }

  public function getContent()
  {
    return $this->content;
  }

  public function getIframeUrl()
  {
    return $this->iframeUrl;
  }

}
