<?php

namespace FennB\Phirehose;

/**
 * Example of using Phirehose to display a live filtered stream using track words 
 */
class FilterTrackConsumer extends OauthPhirehose
{
    private $mongo;
    private $db;
    private $collection;
    
    /**
    * Setup the MongoDB connection
    */
    public function setup($mongoConnectString, $mongoDbName, $mongoCollectionName)
    {
        $this->tags = $tags;
        
        // connect
        $this->mongo = new MongoClient($mongoConnectString);

        // select a database
        $this->db = $this->mongo->$mongoDbName;

        // select a collection
        $this->collection = $this->db->$mongoCollectionName;
    }

    /**
    * Stubbed out to do nothing
    */
    public function log($message)
    {
    }
 
    /**
    * Enqueue each status
    *
    * @param string $status
    */
    public function enqueueStatus($status)
    {
        // insert the tweet into the mongo DB
        $document = array( "tweet" => $status );
        $this->collection->insert($document);
        $data = json_decode($status, true);
        if (is_array($data) && isset($data['user']['screen_name'])) 
        {
            //print $data['user']['screen_name'] . ': ' . urldecode($data['text']) . "\n";
            $hashtags = array();
            foreach ($data['entities']['hashtags'] as $hashtag) 
            {
                $hashtags[] = $hashtag['text'];
            }
        
            print implode(",", $hashtags) . "\n";
        }
    }
}
