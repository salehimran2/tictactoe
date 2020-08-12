<?php

function formatSeeks($seeks, $userId, $admin) {
  $s = '<div id="ttt-seeks-container">
         <table>
           <tr>
             <th>
               user
             </th>
             <th>
               created
             </th>
             <th>
               action
             </th>
           </tr>';

  foreach ($seeks as $seek) {
    $s .= formatSeek($seek, $userId, $admin);
  }

  return $s . '</table>
       </div>';
}

function formatSeek($seek, $userId, $admin) {
  $s = '<tr class="ttt-seek" id="ttt-seek-' . $seek['id'] . '">
     <td> ' . $seek['username'] . '</td>
     <td>
       ' . $seek['timestamp'] .
    '</td>';

  if ((int)$seek['user_id'] === $userId) {
    $s .= '<td><a href="javascript:void(0)">remove</a></td>';
  }
  else {
    $s .= '<td><a href="javascript:void(0)">join</a>';

    if ($admin) {
      $s .= ' [admin <a href="javascript:void(0)">remove</a>]';
    }

    $s .= '</td>';
  }

  return $s . '</tr>';
}

?>
