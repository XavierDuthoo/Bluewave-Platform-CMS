<?php

    # Agendas
    $values['sort']['agenda'] = $this->agendaDAO->getAll($_GET['id']);
        for($i = 0; $i < count($values['sort']['agenda']); $i++):
            $values['sort']['agenda'][$i]['events'] = $this->eventDAO->getAll($values['sort']['agenda'][$i]['id']);
        endfor;

    # Documents
    $values['sort']['document'] = $this->documentDAO->getAll($_GET['id']);

    # Paragraphs
    $values['sort']['paragraph'] = $this->paragraphDAO->getAll($_GET['id']);

    # Sliders
    $values['sort']['slider'] = $this->sliderDAO->getAll($_GET['id']);
        for($i = 0; $i < count($values['sort']['slider']); $i++):
            $values['sort']['slider'][$i]['slides'] = $this->slideDAO->getAll($values['sort']['slider'][$i]['id']);
        endfor;

    $values['sorted'] = array();
    foreach($values['sort'] as $key => $component):
        foreach($component as $subcomponent):
            $values['sorted'][$subcomponent['order_number']] = $subcomponent;
            $values['sorted'][$subcomponent['order_number']]['context'] = $key;
        endforeach;
    endforeach;

    ksort($values['sorted']);
    // foreach($values['sorted'] as $sorted_component) {
    //     trace($sorted_component);
    //     echo $sorted_component['order_number'] . '<br/>';
    // }

    // trace($sorted);
    // die();