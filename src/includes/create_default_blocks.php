<?php 
    
    $post['site_id'] = $site_id;
    $post['url'] = '';
    $landing = $this->landingDAO->add($post, $_SESSION['bluewavePlatformUserID']);
    
    $post['active'] = 0;
    $post['content'] = '';
    $popup = $this->popupDAO->add($post, $_SESSION['bluewavePlatformUserID']);

    $post['seconds'] = 0;
    $timer = $this->timerDAO->add($post, $_SESSION['bluewavePlatformUserID']);

    $post['ssid'] = '*FREE WIFI';
    $ssid = $this->ssidDAO->add($post, $_SESSION['bluewavePlatformUserID']);

    $landing && $popup && $timer && $ssid ? $added = true : $added = false;