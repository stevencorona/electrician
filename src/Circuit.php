<?php

class Circuit {

  public $name;

  public function __construct($circuit_name) {
    $this->name = $circuit_name;
  }

  public function isCircuitOpen() {
    return true;
  }

  public function isCircuitClosed() {
    return false;
  }
  
}
