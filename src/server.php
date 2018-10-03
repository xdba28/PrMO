<?php
  require __DIR__ . '/vendor/autoload.php';

  $options = array(
    'cluster' => 'ap1',
    'useTLS' => true
  );
  $pusher = new Pusher\Pusher(
    '6afb55a56f2b4a235c4b',
    '272e2a850479a8abd2aa',
    '606760',
    $options
  );

  $pusher->trigger('my-channel', 'my-event', $_POST['data']);
//   $pusher->trigger('prmo', 'my-event', $_POST['log']);
  
?>