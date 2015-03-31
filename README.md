# electrician
a scalable php circuit breaker library

doesn't rely on apc/memcached/redis (because what's the point? who circuit-breaks the circuit-breaker data store?)

## Why another circuit breaker library?

Many of the PHP circuit breakers I have found aren't scalable in a high-traffic production environment— typically, they depend on APC or Memcache.

APC is deprecated in PHP 5.4— the replacement, Zend OpCache, doesn't offer shared cross-process memory like APC did. There is an unsupported extension, APCu, that brings just the shared memory functionality from APC back into PHP 5.4+, but I don't want to trust production on an unsupported extension.

Memcache on the other hand.. what's the point? Network overhead aside, what's going to circuit break your Memcache instance? While convenient, it adds a single point of failure.

## How does it work?

Electrician is totally self-contained. There tradeoff is that while circuits are shared between the entire PHP-FPM on a single server, there's no cross-communication or coordination of circuits between multiple servers.

Circuits are shared between all PHP-FPM workers on a single server. There's no cross-communication or coordination or circuits between servers.

The overhead of each circuit check? One stat.

The library uses the presence of a file (not the contents) to determine if a circuit has been tripped (it's open). The creation time of the file is also used to determine when the circuit should be retryed.

Here's some psuedocode of how it works

    $circuit = "test";
    $circuit_path = "/tmp/{$circuit}";
    
    $circuit_info = stat($circuit_path);
    
    // If the circuit is open but has been open >= 60 seconds, close it.
    if ($circuit_info != false && (time() - $circuit_info["atime"] >= 60) {
      @unlink($circuit_path);
    }
    
    // False from stat means that the file doesn't exist!
    // Circuit is closed
    if ($circuit_info == false) {
     
     try {
        // Circuit is closed, make your HTTP request here
        // Cause a failure...
        throw new Exception();
      } catch (Exception $ex) {
        // Circuit is tripped, break the circuit
        touch($circuit_path);
      }
     
      
      
    } else {
      // Failure path
    
    }

## How to use it

**Define a new Circuit**

    $circuit = new Circuit("github_api");
    
