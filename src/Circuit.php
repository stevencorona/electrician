<?php

class Circuit {

  public $name;

  public function __construct($circuit_name) {
    $this->name = $circuit_name;
  }

  public function isCircuitOpen() {
    return false;
  }

  public function isCircuitClosed() {
    return true;
  }
  
  public function run(Closure $closed, Closure $open) {
    
    
  }
  
}
