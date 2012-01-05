<?php

require_once 'UnitTestCommon.php';

class Services_Facebook_StreamTest extends Services_Facebook_UnitTestCommon
{
    protected $mock = array('callMethod');
    
    public function testPublish()
    {
        $post_id = '100000675718177_101794323186424';
        $response = '<?xml version="1.0" encoding="UTF-8"?>
            <stream_publish_response xmlns="http://api.facebook.com/1.0/"
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd"
                >'.$post_id.'</stream_publish_response>';

        $this->instance->sessionKey = 'user_session_key';
        
        $this->instance->expects($this->once())
            ->method('callMethod')
            ->with('stream.publish', array(
                'session_key' => 'user_session_key',
                'message' => 'status update'))
            ->will($this->returnValue(simplexml_load_string($response)));
            
        $response = $this->instance->publish('status update');
        $this->assertEquals($post_id, (string)$response);
    }
    
    public function testRemove()
    {
        $post_id = '100000675718177_101794323186424';
        $response = '<?xml version="1.0" encoding="UTF-8"?>
            <stream_remove_response xmlns="http://api.facebook.com/1.0/"
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd"
                >1</stream_remove_response>';

        $this->instance->sessionKey = 'user_session_key';
        
        $this->instance->expects($this->once())
            ->method('callMethod')
            ->with('stream.remove', array(
                'session_key' => 'user_session_key',
                'post_id' => $post_id))
            ->will($this->returnValue(simplexml_load_string($response)));
            
        $response = $this->instance->remove($post_id);
        $this->assertEquals('1', (string)$response);
    }
    
    public function testGet()
    {
        $response = '<?xml version="1.0" encoding="UTF-8"?>
            <stream_get_response xmlns="http://api.facebook.com/1.0/"
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd"
                >STREAM_GET</stream_get_response>';

        $this->instance->sessionKey = 'user_session_key';
        
        $this->instance->expects($this->once())
            ->method('callMethod')
            ->with('stream.get', array(
                'session_key' => 'user_session_key',
                'limit' => 42))
            ->will($this->returnValue(simplexml_load_string($response)));
            
        $response = $this->instance->get(42);
        $this->assertEquals('STREAM_GET', (string)$response);
    }
    
    public function testAddComment()
    {
        $post_id = '100000675718177_101794323186424';
        $response = '<?xml version="1.0" encoding="UTF-8"?>
            <stream_add_comment_response xmlns="http://api.facebook.com/1.0/"
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd"
                >returned_comment_id</stream_add_comment_response>';

        $this->instance->sessionKey = 'user_session_key';
        
        $this->instance->expects($this->once())
            ->method('callMethod')
            ->with('stream.addComment', array(
                'session_key' => 'user_session_key',
                'post_id' => $post_id,
                'comment' => 'comment text'))
            ->will($this->returnValue(simplexml_load_string($response)));
            
        $response = $this->instance->addComment($post_id, 'comment text');
        $this->assertEquals('returned_comment_id', (string)$response);
    }
    
    public function testRemoveComment()
    {
        $response = '<?xml version="1.0" encoding="UTF-8"?>
            <stream_remove_comment_response xmlns="http://api.facebook.com/1.0/"
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd"
                >1</stream_remove_comment_response>';

        $this->instance->sessionKey = 'user_session_key';
        
        $this->instance->expects($this->once())
            ->method('callMethod')
            ->with('stream.removeComment', array(
                'session_key' => 'user_session_key',
                'comment_id' => 'given_comment_id'))
            ->will($this->returnValue(simplexml_load_string($response)));
            
        $response = $this->instance->removeComment('given_comment_id');
        $this->assertEquals('1', (string)$response);
    }
    
    public function testAddLike()
    {
        $post_id = '100000675718177_101794323186424';
        $response = '<?xml version="1.0" encoding="UTF-8"?>
            <stream_add_like_response xmlns="http://api.facebook.com/1.0/"
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd"
                >1</stream_add_like_response>';

        $this->instance->sessionKey = 'user_session_key';
        
        $this->instance->expects($this->once())
            ->method('callMethod')
            ->with('stream.addLike', array(
                'session_key' => 'user_session_key',
                'post_id' => $post_id))
            ->will($this->returnValue(simplexml_load_string($response)));
            
        $response = $this->instance->addLike($post_id);
        $this->assertEquals('1', (string)$response);
    }
    
    public function testRemoveLike()
    {
        $post_id = '100000675718177_101794323186424';
        $response = '<?xml version="1.0" encoding="UTF-8"?>
            <stream_remove_like_response xmlns="http://api.facebook.com/1.0/"
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd"
                >1</stream_remove_like_response>';

        $this->instance->sessionKey = 'user_session_key';
        
        $this->instance->expects($this->once())
            ->method('callMethod')
            ->with('stream.removeLike', array(
                'session_key' => 'user_session_key',
                'post_id' => $post_id))
            ->will($this->returnValue(simplexml_load_string($response)));
            
        $response = $this->instance->removeLike($post_id);
        $this->assertEquals('1', (string)$response);
    }
    
}

?>
