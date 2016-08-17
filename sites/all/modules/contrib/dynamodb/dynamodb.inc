<?php

/**
 * @file
 * Include file to define cache methods for DynamoDB integration.
 */

$sdk_path = variable_get('dynamodb_sdk_path', dirname(__FILE__)) . '/vendor/autoload.php';
require $sdk_path;

use Aws\DynamoDb\DynamoDbClient;

class DynamodbCache implements DrupalCacheInterface {
  protected $dynamo;

  function __construct($bin) {
    global $conf;

    $this->bin = $bin;

    $this->dynamo = DynamoDbClient::factory(array(
      'key'    => $conf['dynamodb_access_key'],
      'secret' => $conf['dynamodb_secret_key'],
      'region' => $conf['dynamodb_region_name'],
    ));
  }

  /**
   * Implements DrupalCacheInterface::get().
   */
  function get($cid) {
    global $conf;

    // Currently, anything that gets set/got after (or in) the DrupalCacheArray
    // destructor is causing problems with the AWS SDK. There is an open issue on
    // github to address this https://github.com/guzzle/guzzle/issues/520
    // Until then, this will ignore those variables and prevent a loop which
    // devoids the site of all memory.
    if (strpos($cid, 'schema:runtime') === 0 || strpos($cid, 'theme_registry:runtime') === 0) {
      return FALSE;
    }

    $result = $this->dynamo->getItem(array(
      'ConsistentRead' => true,
      'TableName' => $conf['dynamodb_table'],
      'Key'       => array(
        'cid'   => array('S' => $cid),
        'bin' => array('S' => $this->bin),
      )
    ));

    // Check to see if we have a result before attempting to gzuncompress or
    // PHP will error out.
    if ($data = $result['Item']['data']['B']) {
      $cache = new stdClass();

      // AWS runs a base64_encode on any binary type data stored in DynamoDB.
      // Because of this, a base64_decode is required first on data retrieved
      // from DynamoDB.
      $cache->data = unserialize(gzuncompress(base64_decode($data)));
      $cache->expire = $result['Item']['expire']['N'];
      $cache->cid = $cid;
      $cache->bin = $this->bin;

      return $this->isValid($cache) ? $cache : FALSE;
    }

    return FALSE;
  }

  /**
   * Implements DrupalCacheInterface::get().
   */
  function getMultiple(&$cid) {
    return array();
  }

  /**
   * Implements DrupalCacheInterface::set().
   */
  function set($cid, $data, $expire = CACHE_PERMANENT) {
    global $conf;

    // Currently, anything that gets set/got after (or in) the DrupalCacheArray
    // destructor is causing problems with the AWS SDK. There is an open issue on
    // github to address this https://github.com/guzzle/guzzle/issues/520
    // Until then, this will ignore those variables and prevent a loop which
    // devoids the site of all memory.
    if (strpos($cid, 'schema:runtime') === 0 || strpos($cid, 'theme_registry:runtime') === 0) {
      return FALSE;
    }

    // Since DynamoDB has a max item size of 64k, we must compress the data to
    // be cached in order to store it.
    $data = gzcompress(serialize($data));

    $result = $this->dynamo->putItem(array(
      'TableName' => $conf['dynamodb_table'],
      'Item' => array(
        'cid'      => array('S' => $cid),
        'bin'    => array('S' => $this->bin),
        'data'   => array('B' => $data),
        'expire' => array('N' => $expire),
      ),
    ));

  }

  function isValid($cache) {
    // TODO CACHE_PERMANENT
    if (isset($cache->expire) && $cache->expire <= REQUEST_TIME) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Implements DrupalCacheInterface::clear().
   */
  function clear($cid = NULL, $wildcard = FALSE) {
    global $conf;

    if (empty($cid)) {
      if (variable_get('cache_lifetime', 0)) {
        // Logic has been copied from DrupalDatabaseCache::clear()
        $cache_flush = variable_get('cache_flush_' . $this->bin, 0);
        if ($cache_flush == 0) {
          // This is the first request to clear the cache, start a timer.
          variable_set('cache_flush_' . $this->bin, REQUEST_TIME);
        }
        elseif (REQUEST_TIME > ($cache_flush + variable_get('cache_lifetime', 0))) {
          /*
          $iterator = $this->dynamo->getIterator('Query', array(
            'TableName'     => $conf['dynamodb_table'],
            'KeyConditions' => array(
              'expire' => array(
                'AttributeValueList' => array(
                  array('N' => 1),
                  array('N' => REQUEST_TIME)
                ),
                'ComparisonOperator' => 'BETWEEN'
              ),
              'bin' => array(
                'AttributeValueList' => array(
                  array('S' => $this->bin),
                ),
                'ComparisonOperator' => 'EQ'
              ),
            ),
          ));
          foreach ($iterator as $item) {
            $this->dynamo->deleteItem(array(
              'TableName' => $conf['dynamodb_table'],
              'Key' => array(
                'cid'   => array('S' => $item['cid']['S']),
                'bin'   => array('S' => $this->bin),
              )
            ));
          }
          variable_set('cache_flush_' . $this->bin, 0);
          */
        }
      }
      else {
        // TODO use scan here instead as we must use $bin
        /*
                $iterator = $this->dynamo->getIterator('Query', array(
                  'TableName'     => $conf['dynamodb_table'],
                  'KeyConditions' => array(
                    'bin' => array(
                      'AttributeValueList' => array(
                        array('S' => $this->bin),
                      ),
                      'ComparisonOperator' => 'EQ'
                    ),
                    'expire' => array(
                      'AttributeValueList' => array(
                        array('N' => 1),
                        array('N' => REQUEST_TIME)
                      ),
                      'ComparisonOperator' => 'BETWEEN'
                    ),
                  ),
                ));
                foreach ($iterator as $item) {
                  $this->dynamo->deleteItem(array(
                    'TableName' => $conf['dynamodb_table'],
                    'Key' => array(
                      'cid'   => array('S' => $item['cid']['S']),
                      'bin'   => array('S' => $this->bin),
                    )
                  ));
                }
        */
      }
    }
    else {
      if ($wildcard) {
        if ($cid == '*') {
          $count = 0;
          $cids = array();
          // Flush an entire bin.
          $iterator = $this->dynamo->getIterator('Query', array(
            'TableName'     => $conf['dynamodb_table'],
            'KeyConditions' => array(
              'bin' => array(
                'AttributeValueList' => array(
                  array('S' => $this->bin)
                ),
                'ComparisonOperator' => 'EQ'
              ),
            ),
          ));
          foreach ($iterator as $item) {
            if ($item['cid']['S']) {
              $cids[] = $item['cid']['S'];
              $count++;
              // The batchWriteItem method AWS uses has a limit of 25 requests
              // at once.
              if ($count == 25) {
                $this->batchClear($cids);
              }
            }

            $this->batchClear($cids);
          }

        }
        else {
          $iterator = $this->dynamo->getIterator('Query', array(
            'TableName'     => $conf['dynamodb_table'],
            'KeyConditions' => array(
              'cid' => array(
                'AttributeValueList' => array(
                  array('S' => $cid)
                ),
                'ComparisonOperator' => 'BEGINS_WITH'
              ),
              'bin' => array(
                'AttributeValueList' => array(
                  array('S' => $this->bin)
                ),
                'ComparisonOperator' => 'EQ'
              ),
            ),
          ));
          foreach ($iterator as $item) {
            $this->dynamo->deleteItem(array(
              'TableName' => $conf['dynamodb_table'],
              'Key' => array(
                'cid'   => array('S' => $item['cid']['S']),
                'bin'   => array('S' => $this->bin),
              )
            ));
          }
        }
      }
      elseif (is_array($cid)) {
        // deal with array
      }
      else {
        // Flush an exact cid and bin.
        $this->dynamo->deleteItem(array(
          'TableName' => $conf['dynamodb_table'],
          'Key' => array(
            'cid'   => array('S' => $cid),
            'bin'   => array('S' => $this->bin),
          )
        ));
      }
    }
  }

  /**
   * Implementation of batchWriteItem for cache clears. This allows multiple
   * cache ids to be cleared in one API call.
   *
   * @param array $cids
   */
  function batchClear(Array &$cids) {
    global $conf;

    // Create the structure AWS expects out of cids to be deleted.
    $requests = array();
    foreach ($cids as $index => $cid) {
      $requests[] = array(
        'DeleteRequest' => array(
          'Key' => array(
            'cid' => array('S' => $cid),
            'bin' => array('S' => $this->bin),
          ),
        ),
      );
      unset($cids[$index]);

    }

    $this->dynamo->batchWriteItem(array(
      'RequestItems' => array(
        $conf['dynamodb_table'] => $requests,
      ),
    ));

  }

  /**
   * Implements DrupalCacheInterface::isEmpty().
   */
  function isEmpty() {
    return FALSE;
  }

}
