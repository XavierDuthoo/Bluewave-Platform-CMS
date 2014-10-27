<?php

    $agenda = $this->agendaDAO->deleteForSite($_GET['id']);
    $document = $this->documentDAO->deleteForSite($_GET['id']);
    $event = $this->eventDAO->deleteForSite($_GET['id']);
    $landing = $this->landingDAO->deleteForSite($_GET['id']);
    $paragraph = $this->paragraphDAO->deleteForSite($_GET['id']);
    $popup = $this->popupDAO->deleteForSite($_GET['id']);
    $slide = $this->slideDAO->deleteForSite($_GET['id']);
    $slider = $this->sliderDAO->deleteForSite($_GET['id']);
    $timer = $this->timerDAO->deleteForSite($_GET['id']);
    $ssid = $this->ssidDAO->deleteForSite($_GET['id']);

    $agenda && $document && $event && $landing && $paragraph && $popup && $slide && $slider && $timer && $ssid ? $deleted = true : $deleted = false;